<?php get_header(); ?>

<main class="sn-single-page sn-vocabulary-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');

      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();
      $vocabulary_url = get_post_type_archive_link('vocabulary');
      $vocabulary_url = $vocabulary_url ? $vocabulary_url : home_url('/vocabulary/');

    ?>
        <div class="sn-breadcrumb">
          <a href="<?php echo esc_url($vocabulary_url); ?>">Vocabulary</a>
          <?php foreach ($topic_terms as $term) : ?>
            <?php $term_link = add_query_arg(array('type' => 'vocabulary', 'topic' => $term->slug), home_url('/explore/')); ?>
            <span>/</span>
            <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
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
            <?php $term_link = add_query_arg(array('type' => 'vocabulary', 'topic' => $term->slug), home_url('/explore/')); ?>
            <span class="sn-pill"><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a></span>
          <?php endforeach; ?>
        </div>
        <h1 class="sn-post-title"><?php the_title(); ?></h1>
        <div class="sn-post-content">
          <?php the_content(); ?>
        </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
