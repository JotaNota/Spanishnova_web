<?php get_header(); ?>

<?php
$current_term = get_queried_object();

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
      'progressive-tenses',
    ],
  ];

  if (empty($orders[$parent_slug])) {
    usort($terms, function ($a, $b) {
      return strcasecmp($a->name, $b->name);
    });

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

function spanishnova_count_grammar_lessons_for_term($term_id, $include_children = true) {
  $count_query = new WP_Query([
    'post_type'           => 'grammar',
    'post_status'         => 'publish',
    'posts_per_page'      => 1,
    'fields'              => 'ids',
    'ignore_sticky_posts' => true,
    'tax_query'           => [
      [
        'taxonomy'         => 'grammar_tax',
        'field'            => 'term_id',
        'terms'            => $term_id,
        'include_children' => $include_children,
      ],
    ],
  ]);

  return (int) $count_query->found_posts;
}

$child_terms = get_terms([
  'taxonomy'   => 'grammar_tax',
  'parent'     => $current_term->term_id,
  'hide_empty' => true,
]);

$child_terms = spanishnova_sort_grammar_terms_logically($child_terms, $current_term->slug);

$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));

$lesson_query = new WP_Query([
  'post_type'           => 'grammar',
  'post_status'         => 'publish',
  'posts_per_page'      => 12,
  'paged'               => $paged,
  'ignore_sticky_posts' => true,
  'orderby'             => 'title',
  'order'               => 'ASC',
  'tax_query'           => [
    [
      'taxonomy'         => 'grammar_tax',
      'field'            => 'term_id',
      'terms'            => $current_term->term_id,
      'include_children' => false,
    ],
  ],
]);
?>

<main>
  <section class="panel grammar-archive-panel">
    <h1><?php echo esc_html($current_term->name); ?></h1>

    <?php if (!is_wp_error($child_terms) && !empty($child_terms)) : ?>
      <div class="grammar-hub-grid">
        <?php foreach ($child_terms as $child_term) : ?>
          <?php
          $lesson_count = spanishnova_count_grammar_lessons_for_term($child_term->term_id, true);
          $term_link = get_term_link($child_term);
          ?>

          <?php if (!is_wp_error($term_link)) : ?>
            <a class="grammar-hub-card" href="<?php echo esc_url($term_link); ?>">
              <span class="grammar-hub-title"><?php echo esc_html($child_term->name); ?></span>
              <span class="grammar-hub-meta"><?php echo esc_html($lesson_count); ?> lessons</span>
              <span class="grammar-hub-action">View lessons →</span>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php elseif ($lesson_query->have_posts()) : ?>
      <div class="activity-list grammar-term-lessons">
        <?php while ($lesson_query->have_posts()) : $lesson_query->the_post(); ?>
          <a class="activity-row" href="<?php the_permalink(); ?>">
            <span class="label">Grammar</span>
            <div>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p>
            </div>
            <span class="arrow">→</span>
          </a>
        <?php endwhile; ?>
      </div>

      <?php
      $pagination = paginate_links([
        'total'   => $lesson_query->max_num_pages,
        'current' => $paged,
        'type'    => 'list',
      ]);
      ?>

      <?php if ($pagination) : ?>
        <nav class="pagination grammar-pagination" aria-label="Grammar pagination">
          <?php echo wp_kses_post($pagination); ?>
        </nav>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No grammar lessons yet.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
