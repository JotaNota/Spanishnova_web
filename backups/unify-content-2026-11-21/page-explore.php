<?php
/**
 * Explore content within the selected post type.
 * Dynamic topics and client-side search are based on published posts.
 */
$allowed_types = ['vocabulary', 'readings', 'conversations'];
$type_labels = [
  'vocabulary' => 'Vocabulary',
  'readings' => 'Readings',
  'conversations' => 'Conversations',
];
$selected_type = isset($_GET['type']) ? sanitize_key(wp_unslash($_GET['type'])) : '';
$selected_topic = isset($_GET['topic']) ? sanitize_title(wp_unslash($_GET['topic'])) : '';
if (!in_array($selected_type, $allowed_types, true)) {
  $selected_type = '';
}
$post_types = $selected_type ? [$selected_type] : $allowed_types;
$explore_posts = get_posts([
  'post_type' => $post_types,
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'date',
  'order' => 'DESC',
  'suppress_filters' => false,
]);
$topics_by_id = [];
$items = [];
foreach ($explore_posts as $post) {
  $post_topics = wp_get_post_terms($post->ID, 'topic_tax');
  $post_topics = is_wp_error($post_topics) ? [] : $post_topics;
  $post_levels = wp_get_post_terms($post->ID, 'level_tax');
  $post_levels = is_wp_error($post_levels) ? [] : $post_levels;
  $topic_ids = [];
  $topic_names = [];
  foreach ($post_topics as $term) {
    $topic_ids[] = (int) $term->term_id;
    $topic_names[] = $term->name;
    $topics_by_id[(int) $term->term_id] = [
      'id' => (int) $term->term_id,
      'slug' => $term->slug,
      'name' => $term->name,
    ];
  }
  $description = wp_trim_words(spanishnova_get_card_excerpt($post->ID), 32);
  $items[] = [
    'id' => (int) $post->ID,
    'type' => $post->post_type,
    'typeLabel' => $type_labels[$post->post_type] ?? $post->post_type,
    'title' => get_the_title($post->ID),
    'description' => $description,
    'url' => get_permalink($post->ID),
    'topics' => $topic_ids,
    'topicNames' => $topic_names,
    'level' => $post_levels ? implode(', ', wp_list_pluck($post_levels, 'name')) : 'Level not specified',
    'keywords' => implode(' ', $topic_names) . ' ' . implode(' ', wp_list_pluck($post_levels, 'name')) . ' ' .
      wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 180, ''),
  ];
}
usort($topics_by_id, static function ($a, $b) {
  return strcasecmp($a['name'], $b['name']);
});
$explore_data = [
  'items' => $items,
  'topics' => array_values($topics_by_id),
  'selectedTopic' => $selected_topic,
];

$explore_css = get_template_directory() . '/assets/css/archives.css';
wp_enqueue_style(
  'spanishnova-archives',
  get_template_directory_uri() . '/assets/css/archives.css',
  ['spanishnova-main'],
  file_exists($explore_css) ? filemtime($explore_css) : '0.1.0'
);
$explore_js = get_template_directory() . '/assets/js/explore-search.js';
wp_enqueue_script(
  'spanishnova-explore-search',
  get_template_directory_uri() . '/assets/js/explore-search.js',
  [],
  file_exists($explore_js) ? filemtime($explore_js) : '0.1.0',
  true
);

get_header();
?>
<main class="explore-page" id="sn-explore">
  <section class="panel explore-panel">
    <h1><?php echo esc_html($selected_type ? $type_labels[$selected_type] : 'Explore'); ?></h1>
    <p class="explore-intro">Find <?php echo esc_html($selected_type ? strtolower($type_labels[$selected_type]) : 'lessons'); ?> by keyword or topic.</p>
    <div class="sn-explore-search">
      <label class="screen-reader-text" for="sn-explore-query">Search lessons</label>
      <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="m16 16 5 5" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>
      <input type="search" id="sn-explore-query" placeholder="Search by title, word or topic…" autocomplete="off">
      <button type="button" id="sn-explore-clear" aria-label="Clear search" hidden>×</button>
    </div>
    <div class="sn-explore-topic-area" id="sn-explore-topic-area">
      <h2>Topics</h2>
      <nav class="sn-explore-topic-list" id="sn-explore-topics" aria-label="Explore topics"></nav>
    </div>
    <div class="sn-explore-results-heading">
      <h2 id="sn-explore-results-title">Lessons</h2>
      <span id="sn-explore-count"></span>
    </div>
    <div id="sn-explore-results" class="explore-results" aria-live="polite"></div>
    <nav class="sn-explore-pagination" id="sn-explore-pagination" aria-label="Explore result pages"></nav>
    <noscript><p>Enable JavaScript to use the lesson search and topic filters.</p></noscript>
    <script type="application/json" id="sn-explore-data"><?php echo wp_json_encode($explore_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
  </section>
</main>
<?php get_footer(); ?>
