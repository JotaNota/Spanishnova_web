<?php get_header(); ?>

<main class="sn-lesson-page sn-conversations-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');

      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();
      $conversations_url = get_post_type_archive_link('conversations');
      $conversations_url = $conversations_url ? $conversations_url : home_url('/conversations/');

      ob_start();
      ?>
        <div class="sn-breadcrumb">
          <a href="<?php echo esc_url($conversations_url); ?>">Conversations</a>
          <?php foreach ($topic_terms as $term) : ?>
            <?php $term_link = get_term_link($term); ?>
            <?php if (!is_wp_error($term_link)) : ?>
              <span>/</span>
              <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="sn-meta-row">
          <span class="sn-pill"><a href="<?php echo esc_url($conversations_url); ?>">Conversations</a></span>
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
      $h1_position = strpos($content, '<h1');

      if ($h1_position !== false) {
        echo substr($content, 0, $h1_position) . $metadata_html . substr($content, $h1_position);
      } else {
        echo $metadata_html . $content;
      }
    ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
