<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="top-header">
  <div class="top-inner">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
  <img
    class="brand-logo"
    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Logo_spanishnova-4-jul-2025.png'); ?>"
    alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
  >
  <span><?php bloginfo('name'); ?></span>
</a>

   <nav class="center-nav" aria-label="Primary navigation">
  <ul class="sn-main-menu">

  <?php
$tenses_link = get_term_link('tenses', 'grammar_tax');
if (is_wp_error($tenses_link)) {
  $tenses_link = home_url('/grammar/');
}

$moods_link = get_term_link('moods', 'grammar_tax');
if (is_wp_error($moods_link)) {
  $moods_link = home_url('/grammar/');
}

$verbs_link = get_term_link('verbs', 'grammar_tax');
if (is_wp_error($verbs_link)) {
  $verbs_link = home_url('/grammar/');
}

$parts_of_speech_link = get_term_link('parts-of-speech', 'grammar_tax');
if (is_wp_error($parts_of_speech_link)) {
  $parts_of_speech_link = home_url('/grammar/');
}

?>

<?php
$sn_grammar_term_link = function ($slug) {
  $term_link = get_term_link($slug, 'grammar_tax');

  if (is_wp_error($term_link)) {
    return home_url('/grammar/');
  }

  return $term_link;
};
?>

<li class="menu-item has-submenu">
  <a href="<?php echo esc_url(home_url('/grammar/')); ?>">Grammar</a>

  <ul class="submenu">
    <li><a href="<?php echo esc_url(home_url('/grammar/')); ?>">All grammar</a></li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url($sn_grammar_term_link('tenses')); ?>">Tenses</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url($sn_grammar_term_link('present')); ?>">Present</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('past')); ?>">Past</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('future')); ?>">Future</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('conditional')); ?>">Conditional</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('progressive-tenses')); ?>">Progressive Tenses</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('perfect-tenses')); ?>">Perfect Tenses</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url($sn_grammar_term_link('moods')); ?>">Moods</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url($sn_grammar_term_link('subjunctive')); ?>">Subjunctive</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('imperative')); ?>">Imperative</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url($sn_grammar_term_link('verbs')); ?>">Verbs</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url($sn_grammar_term_link('ser')); ?>">Ser</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('estar')); ?>">Estar</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('tener')); ?>">Tener</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('ir')); ?>">Ir</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('querer')); ?>">Querer</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('necesitar')); ?>">Necesitar</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('gustar')); ?>">Gustar</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('reflexive-verbs')); ?>">Reflexive Verbs</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url($sn_grammar_term_link('parts-of-speech')); ?>">Parts of Speech</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url($sn_grammar_term_link('pronouns')); ?>">Pronouns</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('adjectives')); ?>">Adjectives</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('adverbs')); ?>">Adverbs</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('prepositions')); ?>">Prepositions</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('connectors')); ?>">Connectors</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('articles')); ?>">Articles</a></li>
      </ul>
    </li>

  </ul>
</li>

    <?php
    $sn_topic_term_link = function ($slug) {
      $term_link = get_term_link($slug, 'topic_tax');

      if (is_wp_error($term_link)) {
        return home_url('/');
      }

      return $term_link;
    };

    $sn_level_term_link = function ($slug) {
      $term_link = get_term_link($slug, 'level_tax');

      if (is_wp_error($term_link)) {
        return home_url('/');
      }

      return $term_link;
    };
    ?>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/vocabulary/')); ?>">Vocabulary</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/vocabulary/')); ?>">All vocabulary</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('daily-life')); ?>">Daily Life</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('travel')); ?>">Travel</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('food')); ?>">Food</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('work')); ?>">Work</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('culture')); ?>">Culture</a></li>
      </ul>
    </li>
    
    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/readings/')); ?>">Readings</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/readings/')); ?>">All readings</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('daily-life')); ?>">Daily Life</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('travel')); ?>">Travel</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('food')); ?>">Food</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('work')); ?>">Work</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('culture')); ?>">Culture</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/conversations/')); ?>">Conversations</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/conversations/')); ?>">All conversations</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('daily-life')); ?>">Daily Life</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('travel')); ?>">Travel</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('food')); ?>">Food</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('work')); ?>">Work</a></li>
        <li><a href="<?php echo esc_url($sn_topic_term_link('culture')); ?>">Culture</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/practice/')); ?>">Practice</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/practice/')); ?>">All practice</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('tenses')); ?>">Tenses</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('moods')); ?>">Moods</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('verbs')); ?>">Verbs</a></li>
        <li><a href="<?php echo esc_url($sn_grammar_term_link('parts-of-speech')); ?>">Parts of Speech</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/resources/')); ?>">Resources</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/resources/')); ?>">All resources</a></li>
        <li><a href="<?php echo esc_url(home_url('/resources/pdfs/')); ?>">PDFs</a></li>
        <li><a href="<?php echo esc_url(home_url('/resources/worksheets/')); ?>">Worksheets</a></li>
        <li><a href="<?php echo esc_url(home_url('/resources/checklists/')); ?>">Checklists</a></li>
      </ul>
    </li>

  </ul>
</nav>

    <div class="top-actions">
      <a href="<?php echo esc_url(home_url('/classes/')); ?>">Classes</a>
      <a class="donate" href="<?php echo esc_url(home_url('/donate/')); ?>">Donate</a>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
    </div>
  </div>
</header>
