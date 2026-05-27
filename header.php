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
      <span class="brand-mark"><span class="brand-dot"></span></span>
      <span><?php bloginfo('name'); ?></span>
    </a>

   <nav class="center-nav" aria-label="Primary navigation">
  <ul class="sn-main-menu">

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/grammar/')); ?>">Grammar</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/grammar/')); ?>">All grammar</a></li>
        <li><a href="<?php echo esc_url(home_url('/grammar/verbs/')); ?>">Verbs</a></li>
        <li><a href="<?php echo esc_url(home_url('/grammar/questions/')); ?>">Questions</a></li>
        <li><a href="<?php echo esc_url(home_url('/grammar/negatives/')); ?>">Negatives</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/vocabulary/')); ?>">Vocabulary</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/vocabulary/')); ?>">All vocabulary</a></li>
        <li><a href="<?php echo esc_url(home_url('/vocabulary/food/')); ?>">Food</a></li>
        <li><a href="<?php echo esc_url(home_url('/vocabulary/travel/')); ?>">Travel</a></li>
        <li><a href="<?php echo esc_url(home_url('/vocabulary/daily-life/')); ?>">Daily life</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/readings/')); ?>">Readings</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/readings/')); ?>">All readings</a></li>
        <li><a href="<?php echo esc_url(home_url('/readings/beginner/')); ?>">Beginner</a></li>
        <li><a href="<?php echo esc_url(home_url('/readings/intermediate/')); ?>">Intermediate</a></li>
        <li><a href="<?php echo esc_url(home_url('/readings/biographies/')); ?>">Biographies</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/conversations/')); ?>">Conversations</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/conversations/')); ?>">All conversations</a></li>
        <li><a href="<?php echo esc_url(home_url('/conversations/everyday/')); ?>">Everyday</a></li>
        <li><a href="<?php echo esc_url(home_url('/conversations/travel/')); ?>">Travel</a></li>
        <li><a href="<?php echo esc_url(home_url('/conversations/restaurants/')); ?>">Restaurants</a></li>
      </ul>
    </li>

    <li class="menu-item has-submenu">
      <a href="<?php echo esc_url(home_url('/practice/')); ?>">Practice</a>
      <ul class="submenu">
        <li><a href="<?php echo esc_url(home_url('/practice/')); ?>">All practice</a></li>
        <li><a href="<?php echo esc_url(home_url('/practice/exercises/')); ?>">Exercises</a></li>
        <li><a href="<?php echo esc_url(home_url('/practice/translation/')); ?>">Translation</a></li>
        <li><a href="<?php echo esc_url(home_url('/practice/quizzes/')); ?>">Quizzes</a></li>
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
