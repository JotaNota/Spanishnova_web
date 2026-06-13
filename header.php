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

$sn_term_link = function ($slug, $taxonomy, $fallback = '/') {
  $term_link = get_term_link($slug, $taxonomy);

  if (is_wp_error($term_link)) {
    return esc_url(home_url($fallback));
  }

  return esc_url($term_link);
};

$sn_render_menu_link = function ($label, $url) {
  printf(
    '<li><a href="%s">%s</a></li>',
    esc_url($url),
    esc_html($label)
  );
};

$sn_topic_items = [
  'daily-life' => 'Daily Life',
  'travel'     => 'Travel',
  'food'       => 'Food',
  'work'       => 'Work',
  'culture'    => 'Culture',
];

$sn_render_topic_links = function ($base_path = '/') use ($sn_topic_items) {
  foreach ($sn_topic_items as $slug => $label) {
    $url = add_query_arg('topic_tax', $slug, home_url($base_path));
    printf('<li><a href="%s">%s</a></li>', esc_url($url), esc_html($label));
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
        <li class="menu-item has-submenu">
          <a href="<?php echo $sn_home_link('/grammar/'); ?>">Grammar</a>

          <ul class="submenu">
            <li><a href="<?php echo $sn_home_link('/grammar/'); ?>">All grammar</a></li>

            <li class="menu-item has-submenu">
              <a href="<?php echo $sn_term_link('tenses', 'grammar_tax', '/grammar/'); ?>">Tenses</a>
              <ul class="submenu">
                <?php
                foreach ([
                  'present'            => 'Present',
                  'past'               => 'Past',
                  'future'             => 'Future',
                  'conditional'        => 'Conditional',
                  'progressive-tenses' => 'Progressive Tenses',
                  'perfect-tenses'     => 'Perfect Tenses',
                ] as $slug => $label) {
                  $sn_render_menu_link($label, $sn_term_link($slug, 'grammar_tax', '/grammar/'));
                }
                ?>
              </ul>
            </li>

            <li class="menu-item has-submenu">
              <a href="<?php echo $sn_term_link('moods', 'grammar_tax', '/grammar/'); ?>">Moods</a>
              <ul class="submenu">
                <?php
                foreach ([
                  'subjunctive' => 'Subjunctive',
                  'imperative'  => 'Imperative',
                ] as $slug => $label) {
                  $sn_render_menu_link($label, $sn_term_link($slug, 'grammar_tax', '/grammar/'));
                }
                ?>
              </ul>
            </li>

            <li class="menu-item has-submenu">
              <a href="<?php echo $sn_term_link('verbs', 'grammar_tax', '/grammar/'); ?>">Verbs</a>
              <ul class="submenu">
                <?php
                $sn_verbs_parent = get_term_by('slug', 'verbs', 'grammar_tax');

                if ($sn_verbs_parent && !is_wp_error($sn_verbs_parent)) {
                  $sn_verb_terms = get_terms([
                    'taxonomy'   => 'grammar_tax',
                    'parent'     => $sn_verbs_parent->term_id,
                    'hide_empty' => true,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                  ]);

                  if (!is_wp_error($sn_verb_terms)) {
                    foreach ($sn_verb_terms as $sn_verb_term) {
                      $sn_render_menu_link($sn_verb_term->name, get_term_link($sn_verb_term));
                    }
                  }
                }
                ?>
              </ul>
            </li>

            <li class="menu-item has-submenu">
              <a href="<?php echo $sn_term_link('parts-of-speech', 'grammar_tax', '/grammar/'); ?>">Parts of Speech</a>
              <ul class="submenu">
                <?php
                foreach ([
                  'pronouns'     => 'Pronouns',
                  'adjectives'   => 'Adjectives',
                  'adverbs'      => 'Adverbs',
                  'prepositions' => 'Prepositions',
                  'connectors'   => 'Connectors',
                  'articles'     => 'Articles',
                ] as $slug => $label) {
                  $sn_render_menu_link($label, $sn_term_link($slug, 'grammar_tax', '/grammar/'));
                }
                ?>
              </ul>
            </li>
          </ul>
        </li>

        <li class="menu-item has-submenu">
          <a href="<?php echo $sn_home_link('/vocabulary/'); ?>">Vocabulary</a>
          <ul class="submenu">
            <li><a href="<?php echo $sn_home_link('/vocabulary/'); ?>">All vocabulary</a></li>
            <?php $sn_render_topic_links('/vocabulary/'); ?>
          </ul>
        </li>

        <li class="menu-item has-submenu">
          <a href="<?php echo $sn_home_link('/readings/'); ?>">Readings</a>
          <ul class="submenu">
            <li><a href="<?php echo $sn_home_link('/readings/'); ?>">All readings</a></li>
            <?php $sn_render_topic_links('/readings/'); ?>
          </ul>
        </li>

        <li class="menu-item has-submenu">
          <a href="<?php echo $sn_home_link('/conversations/'); ?>">Conversations</a>
          <ul class="submenu">
            <li><a href="<?php echo $sn_home_link('/conversations/'); ?>">All conversations</a></li>
            <?php $sn_render_topic_links('/conversations/'); ?>
          </ul>
        </li>

                <li class="menu-item has-submenu">
          <a href="<?php echo $sn_home_link('/practice/'); ?>">Practice</a>
          <ul class="submenu">
            <li><a href="<?php echo $sn_home_link('/practice/'); ?>">All practice</a></li>
            <?php
            foreach ([
              'tenses'          => 'Tenses',
              'moods'           => 'Moods',
              'verbs'           => 'Verbs',
              'parts-of-speech' => 'Parts of Speech',
            ] as $slug => $label) {
              $sn_render_menu_link($label, $sn_term_link($slug, 'grammar_tax', '/practice/'));
            }
            ?>
          </ul>
        </li>
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
</header>

