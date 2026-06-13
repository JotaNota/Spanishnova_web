<?php
wp_enqueue_style('spanishnova-archives', get_template_directory_uri() . '/assets/css/archives.css', array('spanishnova-main'), '0.1.0');
get_header();
?>
<?php
$allowed_types = array('vocabulary', 'readings', 'conversations');
$type_labels = array(
  'vocabulary' => 'Vocabulary',
  'readings' => 'Readings',
  'conversations' => 'Conversations',
);

$selected_type = isset($_GET['type']) ? sanitize_key(wp_unslash($_GET['type'])) : '';
$selected_topic = isset($_GET['topic']) ? sanitize_title(wp_unslash($_GET['topic'])) : '';
$is_valid_type = ($selected_type === '' || in_array($selected_type, $allowed_types, true));
$topic_term = $selected_topic ? get_term_by('slug', $selected_topic, 'topic_tax') : null;
$is_valid_topic = ($selected_topic === '' || $topic_term);
$query_post_types = $selected_type && $is_valid_type ? array($selected_type) : $allowed_types;
$explore_url = home_url('/explore/');
$topics = get_terms(array(
  'taxonomy' => 'topic_tax',
  'hide_empty' => false,
));

if (!$is_valid_type) {
  $query_post_types = array();
}

$query_args = array(
  'post_type' => $query_post_types,
  'post_status' => 'publish',
  'posts_per_page' => -1,
);

if ($selected_topic && $is_valid_topic) {
  $query_args['tax_query'] = array(
    array(
      'taxonomy' => 'topic_tax',
      'field' => 'term_id',
      'terms' => $topic_term->term_id,
    ),
  );
}

$explore_query = ($is_valid_type && $is_valid_topic) ? new WP_Query($query_args) : null;
$result_count = $explore_query ? (int) $explore_query->post_count : 0;
$result_count_label = $result_count === 1 ? 'result' : 'results';
$group_order = array('readings', 'vocabulary', 'conversations');
?>
<main class="explore-page">
  <section class="panel explore-panel">
    <h1><?php the_title(); ?></h1>
    <p class="explore-intro">Find content by type and topic.</p>

    <div class="explore-filters">
      <div class="explore-filter-group">
        <?php
        $all_type_args = array();
        if ($selected_topic) {
          $all_type_args['topic'] = $selected_topic;
        }
        ?>
        <div class="explore-filter-options">
          <a class="explore-pill <?php echo $selected_type === '' ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg($all_type_args, $explore_url)); ?>">All</a>
          <?php foreach ($type_labels as $type_slug => $type_label) : ?>
            <?php
            $type_args = array('type' => $type_slug);
            if ($selected_topic) {
              $type_args['topic'] = $selected_topic;
            }
            ?>
            <a class="explore-pill <?php echo $selected_type === $type_slug ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg($type_args, $explore_url)); ?>"><?php echo esc_html($type_label); ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="explore-filter-group">
        <h2 class="explore-filter-title">Topics</h2>
        <?php
        $all_topic_args = array();
        if ($selected_type) {
          $all_topic_args['type'] = $selected_type;
        }
        ?>
        <div class="explore-filter-options">
          <a class="explore-pill <?php echo $selected_topic === '' ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg($all_topic_args, $explore_url)); ?>">All topics</a>
          <?php if (!is_wp_error($topics)) : ?>
            <?php foreach ($topics as $topic) : ?>
              <?php
              $topic_args = array('topic' => $topic->slug);
              if ($selected_type) {
                $topic_args['type'] = $selected_type;
              }
              ?>
              <a class="explore-pill <?php echo $selected_topic === $topic->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg($topic_args, $explore_url)); ?>"><?php echo esc_html($topic->name); ?></a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <p class="explore-result-count"><?php echo esc_html($result_count); ?> <?php echo esc_html($result_count_label); ?></p>

    <?php if ($explore_query) : ?>
      <?php if ($explore_query->have_posts()) : ?>
        <?php if ($selected_type === '') : ?>
          <?php
          $grouped_posts = array();
          foreach ($allowed_types as $allowed_type) {
            $grouped_posts[$allowed_type] = array();
          }

          foreach ($explore_query->posts as $explore_post) {
            if (isset($grouped_posts[$explore_post->post_type])) {
              $grouped_posts[$explore_post->post_type][] = $explore_post;
            }
          }
          ?>
          <?php foreach ($group_order as $group_type) : ?>
            <?php if (empty($grouped_posts[$group_type])) : ?>
              <?php continue; ?>
            <?php endif; ?>
            <h3 class="explore-group-title"><?php echo esc_html($type_labels[$group_type]); ?></h3>
            <div class="activity-list">
            <?php foreach ($grouped_posts[$group_type] as $post) : setup_postdata($post); ?>
              <?php
              $post_type = get_post_type();
              $label = isset($type_labels[$post_type]) ? $type_labels[$post_type] : $post_type;
              ?>
              <a class="activity-row" href="<?php echo esc_url(get_permalink()); ?>">
                <span class="label"><?php echo esc_html($label); ?></span>
                <div><h3><?php the_title(); ?></h3><p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p></div>
                <span class="arrow">&rarr;</span>
              </a>
            <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
        <div class="activity-list">
        <?php while ($explore_query->have_posts()) : $explore_query->the_post(); ?>
          <?php
          $post_type = get_post_type();
          $label = isset($type_labels[$post_type]) ? $type_labels[$post_type] : $post_type;
          ?>
          <a class="activity-row" href="<?php echo esc_url(get_permalink()); ?>">
            <span class="label"><?php echo esc_html($label); ?></span>
            <div><h3><?php the_title(); ?></h3><p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p></div>
            <span class="arrow">&rarr;</span>
          </a>
        <?php endwhile; ?>
        </div>
        <?php endif; ?>
      <?php else : ?>
        <p>No content yet.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No content yet.</p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
