<?php
/**
 * Grammar navigator. Topics and search data come from published grammar posts.
 * Each lesson has one canonical location in this index, chosen from grammar_tax.
 */
if (!defined('ABSPATH')) { exit; }

$grammar_archive_url = get_post_type_archive_link('grammar') ?: home_url('/grammar/');
$terms = get_terms(['taxonomy' => 'grammar_tax', 'hide_empty' => false]);
$terms = is_wp_error($terms) ? [] : $terms;
$term_index = [];
foreach ($terms as $term) {
  $term_index[(int) $term->term_id] = [
    'id' => (int) $term->term_id,
    'parent' => (int) $term->parent,
    'name' => $term->name,
    'slug' => $term->slug,
  ];
}

$priority_slugs = ['tenses', 'verbs', 'moods', 'parts-of-speech'];
$rank = array_flip($priority_slugs);
$root_for = static function ($id) use (&$term_index) {
  $seen = [];
  while (isset($term_index[$id]) && $term_index[$id]['parent'] && !isset($seen[$id])) {
    $seen[$id] = true;
    $id = $term_index[$id]['parent'];
  }
  return $id;
};
$depth_of = static function ($id) use (&$term_index) {
  $depth = 0;
  $seen = [];
  while (isset($term_index[$id]) && $term_index[$id]['parent'] && !isset($seen[$id])) {
    $seen[$id] = true;
    $id = $term_index[$id]['parent'];
    $depth++;
  }
  return $depth;
};

$lessons = [];
$lesson_ids = get_posts([
  'post_type' => 'grammar',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'fields' => 'ids',
  'orderby' => 'title',
  'order' => 'ASC',
  'suppress_filters' => false,
]);

foreach ($lesson_ids as $post_id) {
  $assigned = wp_get_post_terms($post_id, 'grammar_tax');
  $assigned = is_wp_error($assigned) ? [] : $assigned;
  $choices = [];
  $term_names = [];
  foreach ($assigned as $term) {
    if (!isset($term_index[$term->term_id])) { continue; }
    $choices[] = (int) $term->term_id;
    $term_names[] = $term->name;
    foreach (get_ancestors($term->term_id, 'grammar_tax', 'taxonomy') as $ancestor_id) {
      if (isset($term_index[$ancestor_id])) {
        $term_names[] = $term_index[$ancestor_id]['name'];
      }
    }
  }
  usort($choices, static function ($a, $b) use ($depth_of, $root_for, $term_index, $rank) {
    $depth_diff = $depth_of($b) - $depth_of($a);
    if ($depth_diff) { return $depth_diff; }
    $a_root = $root_for($a);
    $b_root = $root_for($b);
    $a_rank = $rank[$term_index[$a_root]['slug'] ?? ''] ?? 999;
    $b_rank = $rank[$term_index[$b_root]['slug'] ?? ''] ?? 999;
    if ($a_rank !== $b_rank) { return $a_rank <=> $b_rank; }
    return strcmp($term_index[$a]['name'], $term_index[$b]['name']);
  });

  $body = get_post_field('post_content', $post_id);
  $lessons[] = [
    'id' => (int) $post_id,
    'title' => get_the_title($post_id),
    'description' => wp_trim_words(spanishnova_get_card_excerpt($post_id), 27),
    'url' => get_permalink($post_id),
    'term' => $choices ? $choices[0] : 0,
    'keywords' => implode(' ', array_unique($term_names)) . ' ' . wp_trim_words(wp_strip_all_tags(strip_shortcodes($body)), 200, ''),
  ];
}
$data = [
  'terms' => array_values($term_index),
  'lessons' => $lessons,
];

wp_enqueue_script(
  'spanishnova-grammar-navigator',
  get_template_directory_uri() . '/assets/js/grammar-navigator.js',
  [],
  filemtime(get_template_directory() . '/assets/js/grammar-navigator.js'),
  true
);
get_header();
?>
<main class="sn-grammar-library" id="sn-grammar-navigator">
  <section class="panel grammar-archive-panel grammar-explore-panel">
    <header class="sn-grammar-intro">
      <h1>Grammar</h1>
      <p>Find a lesson or explore grammar by topic.</p>
    </header>
    <div class="sn-grammar-search-wrap">
      <label class="screen-reader-text" for="sn-grammar-search">Search grammar lessons and topics</label>
      <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="m16 16 5 5" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>
      <input id="sn-grammar-search" type="search" placeholder="Try 'present regu', 'indirect object', 'subjunctive'…" autocomplete="off">
      <button id="sn-grammar-clear" type="button" aria-label="Clear search" hidden>×</button>
    </div>
    <nav id="sn-grammar-sections" class="sn-grammar-nav" aria-label="Grammar sections"></nav>
    <nav id="sn-grammar-breadcrumbs" class="sn-grammar-breadcrumbs" aria-label="Grammar navigation"></nav>
    <div id="sn-grammar-content" aria-live="polite"></div>
    <noscript><p>Enable JavaScript to browse grammar lessons. <a href="<?php echo esc_url($grammar_archive_url); ?>">Grammar archive</a></p></noscript>
    <script type="application/json" id="sn-grammar-data"><?php echo wp_json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
  </section>
</main>
<?php get_footer(); ?>
