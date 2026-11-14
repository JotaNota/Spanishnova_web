<?php get_header(); ?>

<?php
$selected_slug = isset($_GET['grammar_tax']) ? sanitize_title(wp_unslash($_GET['grammar_tax'])) : '';
$selected_term = $selected_slug ? get_term_by('slug', $selected_slug, 'grammar_tax') : null;

$main_filter_slugs = [
  'tenses',
  'verbs',
  'moods',
  'parts-of-speech',
];

$parent_terms = array_filter(array_map(function ($slug) {
  $term = get_term_by('slug', $slug, 'grammar_tax');

  return ($term && !is_wp_error($term)) ? $term : null;
}, $main_filter_slugs));

function spanishnova_get_grammar_filter_url($slug = '') {
  $base_url = get_post_type_archive_link('grammar');

  if (!$base_url) {
    $base_url = home_url('/grammar/');
  }

  if (!$slug) {
    return esc_url($base_url);
  }

  return esc_url(add_query_arg('grammar_tax', $slug, $base_url));
}

function spanishnova_sort_grammar_filter_terms($terms, $parent_slug = '') {
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

$active_parent = null;

if ($selected_term && !is_wp_error($selected_term)) {
  if ((int) $selected_term->parent === 0) {
    $active_parent = $selected_term;
  } else {
    $active_parent = get_term($selected_term->parent, 'grammar_tax');
  }
}

$child_terms = [];

if ($active_parent && !is_wp_error($active_parent)) {
  $child_terms = get_terms([
    'taxonomy'   => 'grammar_tax',
    'hide_empty' => true,
    'parent'     => $active_parent->term_id,
  ]);

  $child_terms = spanishnova_sort_grammar_filter_terms($child_terms, $active_parent->slug);
}

$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));

$query_args = [
  'post_type'           => 'grammar',
  'post_status'         => 'publish',
  'posts_per_page'      => 12,
  'paged'               => $paged,
  'ignore_sticky_posts' => true,
  'orderby'             => 'title',
  'order'               => 'ASC',
];

if ($selected_term && !is_wp_error($selected_term)) {
  $query_args['tax_query'] = [
    [
      'taxonomy'         => 'grammar_tax',
      'field'            => 'term_id',
      'terms'            => $selected_term->term_id,
      'include_children' => true,
    ],
  ];
}

$grammar_query = new WP_Query($query_args);
?>

<main>
  <section class="panel grammar-archive-panel grammar-explore-panel">
    <h1>Grammar</h1>

    <div class="grammar-filter-block">
      <h2>Sections</h2>
      <div class="grammar-filter-row">
        <a class="grammar-filter-chip <?php echo !$selected_term ? 'is-active' : ''; ?>" href="<?php echo spanishnova_get_grammar_filter_url(); ?>">All</a>

        <?php if (!empty($parent_terms) && !is_wp_error($parent_terms)) : ?>
          <?php foreach ($parent_terms as $parent_term) : ?>
            <?php
            $is_active_parent = $active_parent && !is_wp_error($active_parent) && (int) $active_parent->term_id === (int) $parent_term->term_id;
            ?>
            <a class="grammar-filter-chip <?php echo $is_active_parent ? 'is-active' : ''; ?>" href="<?php echo spanishnova_get_grammar_filter_url($parent_term->slug); ?>">
              <?php echo esc_html($parent_term->name); ?>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($child_terms) && !is_wp_error($child_terms)) : ?>
      <div class="grammar-filter-block grammar-subfilter-block">
        <h2><?php echo esc_html($active_parent->name); ?></h2>
        <div class="grammar-filter-row">
          <a class="grammar-filter-chip <?php echo ($selected_term && (int) $selected_term->term_id === (int) $active_parent->term_id) ? 'is-active' : ''; ?>" href="<?php echo spanishnova_get_grammar_filter_url($active_parent->slug); ?>">
            All <?php echo esc_html($active_parent->name); ?>
          </a>

          <?php foreach ($child_terms as $child_term) : ?>
            <a class="grammar-filter-chip <?php echo ($selected_term && (int) $selected_term->term_id === (int) $child_term->term_id) ? 'is-active' : ''; ?>" href="<?php echo spanishnova_get_grammar_filter_url($child_term->slug); ?>">
              <?php echo esc_html($child_term->name); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="grammar-results-heading">
      <h2>
        <?php if ($selected_term && !is_wp_error($selected_term)) : ?>
          <?php echo esc_html($selected_term->name); ?>
        <?php else : ?>
          All grammar lessons
        <?php endif; ?>
      </h2>
      <p><?php echo esc_html($grammar_query->found_posts); ?> results</p>
    </div>

    <?php if ($grammar_query->have_posts()) : ?>
      <div class="activity-list grammar-term-lessons">
        <?php while ($grammar_query->have_posts()) : $grammar_query->the_post(); ?>
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
        'total'   => $grammar_query->max_num_pages,
        'current' => $paged,
        'type'    => 'list',
        'add_args' => $selected_slug ? ['grammar_tax' => $selected_slug] : false,
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
