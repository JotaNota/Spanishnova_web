<?php get_header(); ?>

<?php
$current_term = get_queried_object();

function spanishnova_get_taxonomy_posts_by_grammar_term($term_id) {
  return new WP_Query([
    'post_type'           => ['grammar', 'reading', 'conversations', 'practice'],
    'post_status'         => 'publish',
    'posts_per_page'      => -1,
    'ignore_sticky_posts' => true,
    'orderby'             => 'title',
    'order'               => 'ASC',
    'tax_query'           => [
      [
        'taxonomy'         => 'grammar_tax',
        'field'            => 'term_id',
        'terms'            => $term_id,
        'include_children' => false,
      ],
    ],
  ]);
}

function spanishnova_sort_grammar_terms_logically($terms, $parent_slug) {
  if (empty($terms) || is_wp_error($terms)) {
    return $terms;
  }

  $orders = [
    'tenses' => [
      'present',
      'past',
      'future',
      'conditional',
      'perfect-tenses',
      'progressive',
    ],
  ];

  if (empty($orders[$parent_slug])) {
    return $terms;
  }

  $order_map = array_flip($orders[$parent_slug]);

  usort($terms, function ($a, $b) use ($order_map) {
    $a_index = $order_map[$a->slug] ?? 999;
    $b_index = $order_map[$b->slug] ?? 999;

    if ($a_index === $b_index) {
      return strcasecmp($a->name, $b->name);
    }

    return $a_index <=> $b_index;
  });

  return $terms;
}

$child_terms = get_terms([
  'taxonomy'   => 'grammar_tax',
  'parent'     => $current_term->term_id,
  'hide_empty' => false,
]);

$child_terms = spanishnova_sort_grammar_terms_logically($child_terms, $current_term->slug);
?>

<main>
  <section class="panel grammar-archive-panel">
    <h1><?php echo esc_html($current_term->name); ?></h1>

    <?php if (!is_wp_error($child_terms) && !empty($child_terms)) : ?>
      <div class="grammar-taxonomy-accordion">
        <?php foreach ($child_terms as $child_term) : ?>
          <?php $child_posts = spanishnova_get_taxonomy_posts_by_grammar_term($child_term->term_id); ?>

          <details class="grammar-taxonomy-item">
            <summary class="grammar-taxonomy-summary grammar-child-summary">
              <?php echo esc_html($child_term->name); ?>
            </summary>

            <?php if ($child_posts->have_posts()) : ?>
              <ul class="grammar-lesson-list grammar-taxonomy-lesson-list">
                <?php while ($child_posts->have_posts()) : $child_posts->the_post(); ?>
                  <li><a class="sn-use-line" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>
          </details>

          <?php wp_reset_postdata(); ?>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <?php $term_posts = spanishnova_get_taxonomy_posts_by_grammar_term($current_term->term_id); ?>

      <?php if ($term_posts->have_posts()) : ?>
        <ul class="grammar-lesson-list grammar-taxonomy-lesson-list">
          <?php while ($term_posts->have_posts()) : $term_posts->the_post(); ?>
            <li><a class="sn-use-line" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></li>
          <?php endwhile; ?>
        </ul>
      <?php else : ?>
        <p>No content yet.</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
