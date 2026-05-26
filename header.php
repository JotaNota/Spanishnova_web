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
      <a href="<?php echo esc_url(home_url('/grammar/')); ?>">Grammar</a>
      <a href="<?php echo esc_url(home_url('/vocabulary/')); ?>">Vocabulary</a>
      <a href="<?php echo esc_url(home_url('/readings/')); ?>">Readings</a>
      <a href="<?php echo esc_url(home_url('/conversations/')); ?>">Conversations</a>
      <a href="<?php echo esc_url(home_url('/practice/')); ?>">Practice</a>
      <a href="<?php echo esc_url(home_url('/resources/')); ?>">Resources</a>
    </nav>

    <div class="top-actions">
      <a href="<?php echo esc_url(home_url('/classes/')); ?>">Classes</a>
      <a class="donate" href="<?php echo esc_url(home_url('/donate/')); ?>">Donate</a>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
    </div>
  </div>
</header>
