<?php get_header(); ?>

<main class="sn-single-page sn-readings-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');

      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();
      $readings_url = get_post_type_archive_link('readings');
      $readings_url = $readings_url ? $readings_url : home_url('/readings/');

    ?>
        <div class="sn-breadcrumb">
          <a href="<?php echo esc_url($readings_url); ?>">Readings</a>
          <?php foreach ($topic_terms as $term) : ?>
            <?php $term_link = get_term_link($term); ?>
            <?php if (!is_wp_error($term_link)) : ?>
              <span>/</span>
              <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="sn-meta-row">
          <span class="sn-pill"><a href="<?php echo esc_url($readings_url); ?>">Readings</a></span>
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
        <h1 class="sn-post-title"><?php the_title(); ?></h1>
        <div class="sn-post-content">
          <?php the_content(); ?>
        </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
