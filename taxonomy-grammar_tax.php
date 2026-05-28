<?php get_header(); ?>
<main>
  <section class="panel">
    <?php $current_term = get_queried_object(); ?>

    <h1><?php echo esc_html($current_term->name); ?></h1>

    <?php
    $child_terms = get_terms([
      'taxonomy' => 'grammar_tax',
      'parent' => $current_term->term_id,
      'hide_empty' => false,
    ]);
    ?>

    <?php if (!is_wp_error($child_terms) && !empty($child_terms)) : ?>
      <div class="activity-list">
        <?php foreach ($child_terms as $child_term) :
          $child_term_link = get_term_link($child_term);

          if (is_wp_error($child_term_link)) {
            continue;
          }
        ?>
          <a class="activity-row" href="<?php echo esc_url($child_term_link); ?>">
            <span class="label">Grammar</span>
            <div><h3><?php echo esc_html($child_term->name); ?></h3></div>
            <span class="arrow">&rarr;</span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else : ?>

      <?php
      $grammar_term_posts = new WP_Query([
        'post_type' => ['grammar', 'readings', 'conversations', 'practice'],
        'post_status' => 'publish',
        'tax_query' => [
          [
            'taxonomy' => 'grammar_tax',
            'field' => 'term_id',
            'terms' => $current_term->term_id,
            'include_children' => false,
          ],
        ],
      ]);
      ?>

      <?php if ($grammar_term_posts->have_posts()) : ?>
        <div class="activity-list">
        <?php while ($grammar_term_posts->have_posts()) : $grammar_term_posts->the_post(); ?>
          <a class="activity-row" href="<?php echo esc_url(get_permalink()); ?>">
            <span class="label"><?php echo esc_html(get_post_type()); ?></span>
            <div><h3><?php echo esc_html(get_the_title()); ?></h3><p><?php echo esc_html(get_the_excerpt()); ?></p></div>
            <span class="arrow">&rarr;</span>
          </a>
        <?php endwhile; ?>
        </div>
      <?php else : ?>
        <p>No content yet.</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
