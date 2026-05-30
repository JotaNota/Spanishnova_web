<?php get_header(); ?>

<main class="sn-lesson-page sn-vocabulary-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');

      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();
      $vocabulary_url = get_post_type_archive_link('vocabulary');
      $vocabulary_url = $vocabulary_url ? $vocabulary_url : home_url('/vocabulary/');

      ob_start();
      ?>
        <div class="sn-breadcrumb">
          <a href="<?php echo esc_url($vocabulary_url); ?>">Vocabulary</a>
          <?php foreach ($topic_terms as $term) : ?>
            <?php $term_link = get_term_link($term); ?>
            <?php if (!is_wp_error($term_link)) : ?>
              <span>/</span>
              <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="sn-meta-row">
          <span class="sn-pill"><a href="<?php echo esc_url($vocabulary_url); ?>">Vocabulary</a></span>
          <?php foreach ($level_terms as $term) : ?>
            <?php $term_link = get_term_link($term); ?>
            <?php if (!is_wp_error($term_link)) : ?>
              <span class="sn-pill"><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a></span>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php foreach ($topic_terms as $term) : ?>
            <?php $term_link = get_term_link($term); ?>
            <?php if (!is_wp_error($term_link)) : ?>
              <span class="sn-pill"><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a></span>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php
      $metadata_html = ob_get_clean();
      $content = apply_filters('the_content', get_the_content());
      $content = preg_replace('/<div\s+class="sn-breadcrumb">.*?<\/div>\s*/s', '', $content, 1);
      $content = preg_replace('/<div\s+class="sn-meta-row">.*?<\/div>\s*/s', '', $content, 1);
      echo preg_replace('/<h1\b/', $metadata_html . '<h1', $content, 1);
    ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
