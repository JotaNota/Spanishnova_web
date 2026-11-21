<?php
$sn_info_slug = '';
if (is_page('classes')) {
  $sn_info_slug = 'classes';
} elseif (is_page(['donations', 'donate'])) {
  $sn_info_slug = 'donations';
} elseif (is_page('contact')) {
  $sn_info_slug = 'contact';
}
if ($sn_info_slug) {
  $sn_info_kickers = [
    'classes' => 'Learn with SpanishNova',
    'donations' => 'Support SpanishNova',
    'contact' => 'Get in touch',
  ];
  $sn_info_descriptions = [
    'classes' => 'Explore Spanish classes and learning options.',
    'donations' => 'Help support SpanishNova lessons and resources.',
    'contact' => 'Questions about classes, lessons or the website? Contact SpanishNova.',
  ];
  get_header();
  ?>
  <main class="sn-info-page sn-info-page--<?php echo esc_attr($sn_info_slug); ?>" id="sn-info-page">
    <?php while (have_posts()) : the_post(); ?>
      <header class="sn-info-hero">
        <p class="sn-info-eyebrow"><?php echo esc_html($sn_info_kickers[$sn_info_slug]); ?></p>
        <h1><?php the_title(); ?></h1>
        <p class="sn-info-subtitle"><?php echo esc_html($sn_info_descriptions[$sn_info_slug]); ?></p>
      </header>
      <div class="sn-info-content sn-post-content" id="sn-info-content">
        <?php the_content(); ?>
      </div>
    <?php endwhile; ?>
  </main>
  <?php get_footer(); ?>
  <?php
  return;
}
?>
<?php get_header(); ?>

<main class="sn-single-page sn-page">
  <?php while (have_posts()) : the_post(); ?>
    <h1 class="sn-post-title"><?php the_title(); ?></h1>

    <div class="sn-post-content">
      <?php the_content(); ?>
    </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
