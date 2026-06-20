<?php get_header(); ?>

<?php
$current_term = get_queried_object();
$is_level_term = $current_term instanceof WP_Term && $current_term->taxonomy === 'level_tax';
$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));

$content_query = new WP_Query([
  'post_type' => ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'],
  'post_status' => 'publish',
  'posts_per_page' => 18,
  'paged' => $paged,
  'ignore_sticky_posts' => true,
  'orderby' => [
    'post_type' => 'ASC',
    'title' => 'ASC',
  ],
  'order' => 'ASC',
  'tax_query' => [
    [
      'taxonomy' => 'level_tax',
      'field' => 'term_id',
      'terms' => $is_level_term ? $current_term->term_id : 0,
    ],
  ],
]);

if (!function_exists('spanishnova_level_type_label')) {
  function spanishnova_level_type_label($post_type) {
    $object = get_post_type_object($post_type);

    if (!$object) {
      return ucfirst((string) $post_type);
    }

    return $object->labels->singular_name ?: $object->labels->name;
  }
}
?>

<main class="level-route-page">
  <section class="panel level-route-hero">
    <p class="level-route-eyebrow">Learning level</p>
    <h1><?php single_term_title(); ?></h1>
    <?php if (term_description()) : ?>
      <div class="level-route-description">
        <?php echo wp_kses_post(term_description()); ?>
      </div>
    <?php else : ?>
      <p class="level-route-description">Explore all published lessons, vocabulary, readings, conversations, practice, and resources for this level.</p>
    <?php endif; ?>
  </section>

  <section class="panel level-content-panel" aria-labelledby="level-content-title">
    <div class="level-more-details">
      <div class="level-general-heading">
        <div>
          <p class="level-route-eyebrow">All content</p>
          <h2 id="level-content-title" class="level-more-title"><?php single_term_title(); ?> content</h2>
        </div>
        <span class="level-route-count"><?php echo esc_html($content_query->found_posts); ?> results</span>
      </div>

      <?php if ($content_query->have_posts()) : ?>
        <div class="activity-list level-content-list">
          <?php while ($content_query->have_posts()) : $content_query->the_post(); ?>
            <a class="activity-row level-content-row" href="<?php the_permalink(); ?>">
              <span class="label"><?php echo esc_html(spanishnova_level_type_label(get_post_type())); ?></span>
              <div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 18)); ?></p>
              </div>
              <span class="arrow" aria-hidden="true">&rarr;</span>
            </a>
          <?php endwhile; ?>
        </div>

        <?php
        $pagination = paginate_links([
          'total' => $content_query->max_num_pages,
          'current' => $paged,
          'type' => 'list',
        ]);
        ?>

        <?php if ($pagination) : ?>
          <nav class="pagination level-pagination" aria-label="Level content pagination">
            <?php echo wp_kses_post($pagination); ?>
          </nav>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="empty-state">No published content for this level yet.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>
