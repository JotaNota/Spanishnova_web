<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$sn_home_link = function ($path = '/') {
  return esc_url(home_url($path));
};

// Show only terms with published posts belonging to the target CPT.
$sn_has_content = function ($post_type, $taxonomy, $term_id) {
  static $cache = [];
  $key = $post_type . ':' . $taxonomy . ':' . $term_id;
  if (isset($cache[$key])) return $cache[$key];
  $query = new WP_Query([
    'post_type' => $post_type,
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'fields' => 'ids',
    'no_found_rows' => true,
    'tax_query' => [[
      'taxonomy' => $taxonomy,
      'field' => 'term_id',
      'terms' => (int) $term_id,
      'include_children' => true,
    ]],
  ]);
  return $cache[$key] = $query->have_posts();
};

$sn_menu_terms = function ($post_type, $taxonomy, $parent = 0) use ($sn_has_content) {
  $terms = get_terms([
    'taxonomy' => $taxonomy,
    'parent' => $parent,
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
  ]);
  if (is_wp_error($terms) || !$terms) return [];
  return array_values(array_filter($terms, function ($term) use ($post_type, $taxonomy, $sn_has_content) {
    return $sn_has_content($post_type, $taxonomy, $term->term_id);
  }));
};

$sn_archive_url = function ($post_type, $taxonomy = '', $term = null) {
  $base = get_post_type_archive_link($post_type) ?: home_url('/' . $post_type . '/');
  if (!$term) return esc_url($base);

  // Grammar archive uses custom parent/subtopic filters.
  if ($post_type === 'grammar' && $taxonomy === 'grammar_tax') {
    $ancestors = get_ancestors($term->term_id, 'grammar_tax', 'taxonomy');
    if ($ancestors) {
      $root = get_term(end($ancestors), 'grammar_tax');
      return esc_url(add_query_arg([
        'sn_category' => $root->slug,
        'sn_subtopic' => $term->slug,
      ], $base));
    }
    return esc_url(add_query_arg('sn_category', $term->slug, $base));
  }

  // Avoid WP treating archive links as taxonomy archives across CPTs.
  $param = $post_type === 'practice' ? 'sn_practice_category' : 'sn_topic';
  return esc_url(add_query_arg($param, $term->slug, $base));
};

$sn_render_taxonomy_menu = function ($post_type, $taxonomy, $nested = false) use ($sn_menu_terms, $sn_archive_url) {
  $roots = $sn_menu_terms($post_type, $taxonomy, 0);
  foreach ($roots as $root) {
    $children = $nested ? $sn_menu_terms($post_type, $taxonomy, $root->term_id) : [];
    if ($children) {
      echo '<li class="menu-item has-submenu">';
      echo '<a href="' . $sn_archive_url($post_type, $taxonomy, $root) . '">' . esc_html($root->name) . '</a>';
      echo '<ul class="submenu">';
      foreach ($children as $child) {
        echo '<li><a href="' . $sn_archive_url($post_type, $taxonomy, $child) . '">' . esc_html($child->name) . '</a></li>';
      }
      echo '</ul></li>';
    } else {
      echo '<li><a href="' . $sn_archive_url($post_type, $taxonomy, $root) . '">' . esc_html($root->name) . '</a></li>';
    }
  }
};
?>

<header class="top-header">
  <div class="top-inner">
    <a class="brand" href="<?php echo $sn_home_link('/'); ?>">
      <img
        class="brand-logo"
        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Logo_spanishnova-4-jul-2025.png'); ?>"
        alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
      >
      <span><?php bloginfo('name'); ?></span>
    </a>

    <nav class="center-nav" aria-label="Primary navigation">
      <ul class="sn-main-menu">
        <?php foreach ([
          'grammar' => ['Grammar', 'grammar_tax', true],
          'vocabulary' => ['Vocabulary', 'topic_tax', false],
          'readings' => ['Readings', 'topic_tax', false],
          'conversations' => ['Conversations', 'topic_tax', false],
          'practice' => ['Practice', 'grammar_tax', true],
        ] as $sn_type => $sn_config) : ?>
          <li class="menu-item has-submenu">
            <a href="<?php echo $sn_archive_url($sn_type); ?>"><?php echo esc_html($sn_config[0]); ?></a>
            <ul class="submenu">
              <li><a href="<?php echo $sn_archive_url($sn_type); ?>">All <?php echo esc_html(strtolower($sn_config[0])); ?></a></li>
              <?php $sn_render_taxonomy_menu($sn_type, $sn_config[1], $sn_config[2]); ?>
            </ul>
          </li>
        <?php endforeach; ?>
        <li class="menu-item header-search-item">
          <form class="header-search-form" role="search" method="get" action="<?php echo $sn_home_link('/'); ?>" onsubmit="if (!this.s.value.trim()) { window.location.href='<?php echo $sn_home_link('/explore/'); ?>'; return false; }">
            <label class="screen-reader-text" for="header-search-field">Search SpanishNova</label>
            <input
              id="header-search-field"
              type="search"
              name="s"
              placeholder="Search lessons..."
              value="<?php echo esc_attr(get_search_query()); ?>"
            >
            <button type="submit" aria-label="Search">⌕</button>
          </form>
        </li>
      </ul>
    </nav>

    <div class="top-actions">
      <a href="<?php echo $sn_home_link('/classes/'); ?>">Classes</a>
      <a class="donate" href="<?php echo $sn_home_link('/donations/'); ?>">Donate</a>
      <a href="<?php echo $sn_home_link('/contact/'); ?>">Contact</a>
    </div>
  </div>
</header>

