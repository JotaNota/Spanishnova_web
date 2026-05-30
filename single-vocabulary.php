<?php get_header(); ?>

<main class="sn-lesson-page sn-vocabulary-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');

      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();

      $breadcrumb_parts = array('Vocabulary');

      foreach ($topic_terms as $term) {
        $breadcrumb_parts[] = $term->name;
      }

      ob_start();
      ?>
        <div class="sn-breadcrumb"><?php echo esc_html(implode(' / ', $breadcrumb_parts)); ?></div>
        <div class="sn-meta-row">
          <span class="sn-pill">Vocabulary</span>
          <?php foreach ($level_terms as $term) : ?>
            <span class="sn-pill"><?php echo esc_html($term->name); ?></span>
          <?php endforeach; ?>
          <?php foreach ($topic_terms as $term) : ?>
            <span class="sn-pill"><?php echo esc_html($term->name); ?></span>
          <?php endforeach; ?>
        </div>
      <?php
      $metadata_html = ob_get_clean();
      $content = apply_filters('the_content', get_the_content());
      echo preg_replace('/<h1\b/', $metadata_html . '<h1', $content, 1);
    ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
