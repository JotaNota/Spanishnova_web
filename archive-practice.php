<?php get_header(); ?>
<?php
$practice_search = isset($_GET['sn_search']) ? sanitize_text_field(wp_unslash($_GET['sn_search'])) : '';
$practice_topic = isset($_GET['sn_practice_category']) ? sanitize_title(wp_unslash($_GET['sn_practice_category'])) : '';
$practice_level = isset($_GET['sn_level']) ? sanitize_title(wp_unslash($_GET['sn_level'])) : '';
$practice_page = isset($_GET['sn_page']) ? max(1, absint($_GET['sn_page'])) : 1;
$practice_base = get_post_type_archive_link('practice') ?: home_url('/practice/');
$practice_levels = get_terms(['taxonomy' => 'level_tax', 'hide_empty' => true]);
if (is_wp_error($practice_levels)) $practice_levels = [];
$practice_categories = get_terms(['taxonomy' => 'grammar_tax', 'parent' => 0, 'hide_empty' => false]);
if (is_wp_error($practice_categories)) $practice_categories = [];
$practice_categories = array_values(array_filter($practice_categories, function ($term) {
  $query = new WP_Query([
    'post_type' => 'practice',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'fields' => 'ids',
    'no_found_rows' => true,
    'tax_query' => [[
      'taxonomy' => 'grammar_tax',
      'field' => 'term_id',
      'terms' => $term->term_id,
      'include_children' => true,
    ]],
  ]);
  return $query->have_posts();
}));
$practice_query_args = [
  'post_type' => 'practice',
  'post_status' => 'publish',
  'posts_per_page' => 12,
  'paged' => $practice_page,
  'orderby' => 'title',
  'order' => 'ASC',
];
if ($practice_search !== '') $practice_query_args['s'] = $practice_search;
$practice_filters = ['relation' => 'AND'];
if ($practice_topic !== '') {
  $selected_term = get_term_by('slug', $practice_topic, 'grammar_tax');
  if ($selected_term) {
    $practice_filters[] = [
      'taxonomy' => 'grammar_tax',
      'field' => 'term_id',
      'terms' => $selected_term->term_id,
      'include_children' => true,
    ];
  } else {
    $practice_topic = '';
  }
}
if ($practice_level !== '') {
  $selected_level = get_term_by('slug', $practice_level, 'level_tax');
  if ($selected_level) {
    $practice_filters[] = ['taxonomy' => 'level_tax', 'field' => 'term_id', 'terms' => $selected_level->term_id];
  } else {
    $practice_level = '';
  }
}
if (count($practice_filters) > 1) $practice_query_args['tax_query'] = $practice_filters;
$practice_query = new WP_Query($practice_query_args);
$practice_args = [];
if ($practice_search !== '') $practice_args['sn_search'] = $practice_search;
if ($practice_topic !== '') $practice_args['sn_practice_category'] = $practice_topic;
if ($practice_level !== '') $practice_args['sn_level'] = $practice_level;
?>
<main class="sn-practice-archive">
  <section class="panel practice-archive-panel">
    <h1>Practice</h1>
    <p class="grammar-catalog-intro">Find practice lessons by keyword, topic or level.</p>
    <form class="grammar-catalog-filters practice-catalog-filters" action="<?php echo esc_url($practice_base); ?>" method="get" role="search">
      <div class="grammar-filter-field grammar-search-field">
        <label for="practice-search">Search lessons</label>
        <input id="practice-search" type="search" name="sn_search" value="<?php echo esc_attr($practice_search); ?>" placeholder="Search practice lessons...">
      </div>
      <div class="grammar-filter-field">
        <label for="practice-category">Topic</label>
        <select id="practice-category" name="sn_practice_category">
          <option value="">All topics</option>
          <?php foreach ($practice_categories as $term) : ?>
            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($practice_topic, $term->slug); ?>><?php echo esc_html($term->name); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="grammar-filter-field">
        <label for="practice-level">Level</label>
        <select id="practice-level" name="sn_level">
          <option value="">All levels</option>
          <?php foreach ($practice_levels as $term) : ?>
            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($practice_level, $term->slug); ?>><?php echo esc_html($term->name); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="grammar-filter-actions">
        <a class="grammar-reset-button" href="<?php echo esc_url($practice_base); ?>">Reset</a>
        <button type="submit" class="grammar-fallback-submit">Apply filters</button>
      </div>
    </form>

    <div class="grammar-results-heading">
      <h2>Practice lessons</h2>
      <p><?php echo esc_html($practice_query->found_posts); ?> results</p>
    </div>

    <?php if ($practice_query->have_posts()) : ?>
      <div class="activity-list practice-catalog-list">
        <?php while ($practice_query->have_posts()) : $practice_query->the_post(); ?>
          <?php
          $levels = get_the_terms(get_the_ID(), 'level_tax');
          $level_label = $levels && !is_wp_error($levels) ? implode(', ', wp_list_pluck($levels, 'name')) : '';
          $terms = get_the_terms(get_the_ID(), 'grammar_tax');
          $topic_label = $terms && !is_wp_error($terms) ? implode(', ', wp_list_pluck($terms, 'name')) : '';
          ?>
          <a class="activity-row practice-catalog-card explore-result explore-result--practice" href="<?php the_permalink(); ?>">
            <div class="practice-catalog-content">
              <div class="sn-catalog-card-meta">
                <span>Practice</span>
                <?php if ($level_label !== '') : ?>
                  <span aria-hidden="true">·</span>
                  <span class="practice-card-level"><?php echo esc_html($level_label); ?></span>
                <?php endif; ?>
              </div>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p>
              <?php if ($topic_label !== '') : ?>
                <p class="practice-card-topic">Topics: <?php echo esc_html($topic_label); ?></p>
              <?php endif; ?>
            </div>
            <span class="arrow" aria-hidden="true">→</span>
          </a>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="empty-state">No practice lessons match these filters.</p>
    <?php endif; ?>

    <?php if ($practice_query->max_num_pages > 1) :
      $total = (int) $practice_query->max_num_pages;
      $current = min($practice_page, $total);
    ?>
      <nav class="grammar-catalog-pagination" aria-label="Practice pagination">
        <?php if ($current > 1) : ?>
          <a class="grammar-page-control" rel="prev" href="<?php echo esc_url(add_query_arg(array_merge($practice_args, ['sn_page' => $current - 1]), $practice_base)); ?>">← Previous</a>
        <?php else : ?>
          <span class="grammar-page-control is-disabled" aria-disabled="true">← Previous</span>
        <?php endif; ?>
        <span class="grammar-page-count"><?php echo esc_html($current . ' / ' . $total); ?></span>
        <?php if ($current < $total) : ?>
          <a class="grammar-page-control" rel="next" href="<?php echo esc_url(add_query_arg(array_merge($practice_args, ['sn_page' => $current + 1]), $practice_base)); ?>">Next →</a>
        <?php else : ?>
          <span class="grammar-page-control is-disabled" aria-disabled="true">Next →</span>
        <?php endif; ?>
      </nav>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
