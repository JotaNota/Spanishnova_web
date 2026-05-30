<?php get_header(); ?>
<?php
while (have_posts()) :
  the_post();

  $level_terms = get_the_terms(get_the_ID(), 'level_tax');
  $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');

  $level_name = (!empty($level_terms) && !is_wp_error($level_terms)) ? $level_terms[0]->name : '';
  $topic_name = (!empty($topic_terms) && !is_wp_error($topic_terms)) ? $topic_terms[0]->name : '';

  $breadcrumb_parts = array_filter(array('Conversations', $topic_name));
  $pills = array_filter(array('Conversations', $level_name, $topic_name));
?>
<main class="sn-lesson-page sn-conversations-page">
  <div class="sn-lesson-shell">
    <nav class="sn-lesson-breadcrumb" aria-label="Breadcrumb">
      <?php echo esc_html(implode(' / ', $breadcrumb_parts)); ?>
    </nav>

    <?php if (!empty($pills)) : ?>
      <div class="sn-lesson-pills" aria-label="Lesson metadata">
        <?php foreach ($pills as $pill) : ?>
          <span class="sn-lesson-pill"><?php echo esc_html($pill); ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="sn-lesson-content">
      <?php the_content(); ?>
    </div>
  </div>
</main>
<?php endwhile; ?>
<?php get_footer(); ?>
