<?php
get_header();

$selected_slug = isset($_GET['grammar_tax']) ? sanitize_title(wp_unslash($_GET['grammar_tax'])) : '';
$selected_term = $selected_slug ? get_term_by('slug', $selected_slug, 'grammar_tax') : null;
$selected_term = ($selected_term && !is_wp_error($selected_term)) ? $selected_term : null;
$show_other = !$selected_term && isset($_GET['grammar_group']) && sanitize_key(wp_unslash($_GET['grammar_group'])) === 'other';

$preferred_parent_slugs = ['tenses', 'verbs', 'moods', 'parts-of-speech'];
$parent_terms = [];
foreach ($preferred_parent_slugs as $slug) {
  $term = get_term_by('slug', $slug, 'grammar_tax');
  if ($term && !is_wp_error($term)) {
    $parent_terms[] = $term;
  }
}

// Only published grammar lessons make a category visible.
$grammar_has_lessons = static function ($term_id) {
  $check = new WP_Query([
    'post_type' => 'grammar',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'fields' => 'ids',
    'no_found_rows' => true,
    'ignore_sticky_posts' => true,
    'tax_query' => [[
      'taxonomy' => 'grammar_tax',
      'field' => 'term_id',
      'terms' => (int) $term_id,
      'include_children' => true,
    ]],
  ]);
  return $check->have_posts();
};

$parent_terms = array_values(array_filter($parent_terms, static function ($term) use ($grammar_has_lessons) {
  return $grammar_has_lessons($term->term_id);
}));

$grammar_filter_url = static function ($slug = '') {
  $url = get_post_type_archive_link('grammar') ?: home_url('/grammar/');
  return $slug ? add_query_arg('grammar_tax', $slug, $url) : $url;
};
$other_url = add_query_arg('grammar_group', 'other', $grammar_filter_url());

$active_parent = null;
if ($selected_term) {
  $ancestors = get_ancestors($selected_term->term_id, 'grammar_tax', 'taxonomy');
  $top_id = $ancestors ? (int) end($ancestors) : $selected_term->term_id;
  $active_parent = get_term($top_id, 'grammar_tax');
}
$child_terms = [];
if ($active_parent && !is_wp_error($active_parent)) {
  $terms = get_terms([
    'taxonomy' => 'grammar_tax',
    'parent' => $active_parent->term_id,
    'hide_empty' => false,
  ]);
  if (!is_wp_error($terms)) {
    foreach ($terms as $term) {
      if ($grammar_has_lessons($term->term_id)) {
        $child_terms[] = $term;
      }
    }
    usort($child_terms, static function ($a, $b) {
      return strcasecmp($a->name, $b->name);
    });
  }
}

$parent_ids = array_map(static function ($term) { return (int) $term->term_id; }, $parent_terms);
$other_tax_query = $parent_ids ? [[
  'taxonomy' => 'grammar_tax',
  'field' => 'term_id',
  'terms' => $parent_ids,
  'include_children' => true,
  'operator' => 'NOT IN',
]] : [];

$preview_args = [
  'post_type' => 'grammar',
  'post_status' => 'publish',
  'posts_per_page' => 4,
  'orderby' => 'title',
  'order' => 'ASC',
  'ignore_sticky_posts' => true,
];
$group_previews = [];
if (!$selected_term && !$show_other) {
  foreach ($parent_terms as $parent_term) {
    $group_args = $preview_args;
    $group_args['tax_query'] = [[
      'taxonomy' => 'grammar_tax',
      'field' => 'term_id',
      'terms' => $parent_term->term_id,
      'include_children' => true,
    ]];
    $group_previews[] = [
      'term' => $parent_term,
      'query' => new WP_Query($group_args),
    ];
  }
  $other_args = $preview_args;
  if ($other_tax_query) {
    $other_args['tax_query'] = $other_tax_query;
  }
  $other_preview = new WP_Query($other_args);
}

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
if ($selected_term || $show_other) {
  $query_args = [
    'post_type' => 'grammar',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
    'ignore_sticky_posts' => true,
    'orderby' => 'title',
    'order' => 'ASC',
  ];
  if ($selected_term) {
    $query_args['tax_query'] = [[
      'taxonomy' => 'grammar_tax',
      'field' => 'term_id',
      'terms' => $selected_term->term_id,
      'include_children' => true,
    ]];
  } elseif ($other_tax_query) {
    $query_args['tax_query'] = $other_tax_query;
  }
  $grammar_query = new WP_Query($query_args);
}
?>

<main class="sn-grammar-library">
  <section class="panel grammar-archive-panel grammar-explore-panel">
    <header class="sn-grammar-intro">
      <h1>Grammar</h1>
      <p>Browse grammar lessons by topic.</p>
    </header>

    <nav class="grammar-filter-block" aria-label="Grammar sections">
      <h2>Sections</h2>
      <div class="grammar-filter-row">
        <a class="grammar-filter-chip <?php echo (!$selected_term && !$show_other) ? 'is-active' : ''; ?>" href="<?php echo esc_url($grammar_filter_url()); ?>" <?php echo (!$selected_term && !$show_other) ? 'aria-current="page"' : ''; ?>>All</a>
        <?php foreach ($parent_terms as $term) : ?>
          <?php $active = $active_parent && !is_wp_error($active_parent) && (int) $active_parent->term_id === (int) $term->term_id; ?>
          <a class="grammar-filter-chip <?php echo $active ? 'is-active' : ''; ?>" href="<?php echo esc_url($grammar_filter_url($term->slug)); ?>" <?php echo $active && (int) $selected_term->term_id === (int) $term->term_id ? 'aria-current="page"' : ''; ?>><?php echo esc_html($term->name); ?></a>
        <?php endforeach; ?>
      </div>
    </nav>

    <?php if ($selected_term && $child_terms) : ?>
      <nav class="grammar-filter-block grammar-subfilter-block" aria-label="<?php echo esc_attr($active_parent->name); ?> topics">
        <h2><?php echo esc_html($active_parent->name); ?></h2>
        <div class="grammar-filter-row">
          <a class="grammar-filter-chip <?php echo (int) $selected_term->term_id === (int) $active_parent->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url($grammar_filter_url($active_parent->slug)); ?>">All <?php echo esc_html($active_parent->name); ?></a>
          <?php foreach ($child_terms as $child) : ?>
            <a class="grammar-filter-chip <?php echo (int) $selected_term->term_id === (int) $child->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url($grammar_filter_url($child->slug)); ?>"><?php echo esc_html($child->name); ?></a>
          <?php endforeach; ?>
        </div>
      </nav>
    <?php endif; ?>

    <?php if (!$selected_term && !$show_other) : ?>
      <?php foreach ($group_previews as $group) : ?>
        <?php $group_query = $group['query']; $group_term = $group['term']; ?>
        <?php if ($group_query->have_posts()) : ?>
          <section class="sn-grammar-group" aria-labelledby="sn-grammar-<?php echo (int) $group_term->term_id; ?>">
            <div class="sn-grammar-group-heading">
              <div>
                <h2 id="sn-grammar-<?php echo (int) $group_term->term_id; ?>"><?php echo esc_html($group_term->name); ?></h2>
                <span><?php echo esc_html($group_query->found_posts); ?> lessons</span>
              </div>
              <a href="<?php echo esc_url($grammar_filter_url($group_term->slug)); ?>">View all →</a>
            </div>
            <div class="sn-grammar-cards">
              <?php while ($group_query->have_posts()) : $group_query->the_post(); ?>
                <a class="sn-grammar-card" href="<?php the_permalink(); ?>">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 17)); ?></p>
                  <span>Read lesson →</span>
                </a>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
          </section>
        <?php endif; ?>
      <?php endforeach; ?>

      <?php if ($other_preview->have_posts()) : ?>
        <section class="sn-grammar-group" aria-labelledby="sn-grammar-other">
          <div class="sn-grammar-group-heading">
            <div>
              <h2 id="sn-grammar-other">Other grammar lessons</h2>
              <span><?php echo esc_html($other_preview->found_posts); ?> lessons</span>
            </div>
            <a href="<?php echo esc_url($other_url); ?>">View all →</a>
          </div>
          <div class="sn-grammar-cards">
            <?php while ($other_preview->have_posts()) : $other_preview->the_post(); ?>
              <a class="sn-grammar-card" href="<?php the_permalink(); ?>">
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 17)); ?></p>
                <span>Read lesson →</span>
              </a>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!$group_previews && !$other_preview->have_posts()) : ?>
        <p class="empty-state">No grammar lessons published yet.</p>
      <?php endif; ?>
    <?php else : ?>
      <div class="grammar-results-heading">
        <h2><?php echo $selected_term ? esc_html($selected_term->name) : 'Other grammar lessons'; ?></h2>
        <p><?php echo esc_html($grammar_query->found_posts); ?> results</p>
      </div>
      <?php if ($grammar_query->have_posts()) : ?>
        <div class="sn-grammar-cards">
          <?php while ($grammar_query->have_posts()) : $grammar_query->the_post(); ?>
            <a class="sn-grammar-card" href="<?php the_permalink(); ?>">
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 19)); ?></p>
              <span>Read lesson →</span>
            </a>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
        $pagination = paginate_links([
          'total' => $grammar_query->max_num_pages,
          'current' => $paged,
          'type' => 'list',
          'add_args' => $selected_term ? ['grammar_tax' => $selected_slug] : ['grammar_group' => 'other'],
        ]);
        ?>
        <?php if ($pagination) : ?>
          <nav class="pagination grammar-pagination" aria-label="Grammar pagination"><?php echo wp_kses_post($pagination); ?></nav>
        <?php endif; ?>
      <?php else : ?>
        <p class="empty-state">No lessons in this section yet.</p>
      <?php endif; ?>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
