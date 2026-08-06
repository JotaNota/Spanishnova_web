<?php get_header(); ?>

<main class="sn-single-page sn-grammar-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $post_id = get_the_ID();
      $grammar_terms = get_the_terms($post_id, 'grammar_tax');
      $level_terms = get_the_terms($post_id, 'level_tax');
      $topic_terms = get_the_terms($post_id, 'topic_tax');

      $grammar_terms = (!is_wp_error($grammar_terms) && $grammar_terms) ? $grammar_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();
      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();

      $grammar_url = get_post_type_archive_link('grammar');
      $grammar_url = $grammar_url ? $grammar_url : home_url('/grammar/');
      $duration = get_post_meta($post_id, '_sn_lesson_duration', true);
      $pdf_url = get_post_meta($post_id, '_sn_lesson_pdf_url', true);
      $audio_url = get_post_meta($post_id, '_sn_lesson_audio_url', true);
      $slides_url = get_post_meta($post_id, '_sn_lesson_slides_url', true);
      $flashcard_word = get_post_meta($post_id, '_sn_flashcard_word', true);
      $flashcard_meaning = get_post_meta($post_id, '_sn_flashcard_meaning', true);
      $next_lesson_id = absint(get_post_meta($post_id, '_sn_next_lesson_id', true));
      $next_lesson = $next_lesson_id ? get_post($next_lesson_id) : null;

      $primary_grammar_term = null;

      foreach ($grammar_terms as $term) {
        if (!$primary_grammar_term || count(get_ancestors($term->term_id, 'grammar_tax')) > count(get_ancestors($primary_grammar_term->term_id, 'grammar_tax'))) {
          $primary_grammar_term = $term;
        }
      }

      $breadcrumb_terms = array();

      if ($primary_grammar_term) {
        $ancestor_ids = array_reverse(get_ancestors($primary_grammar_term->term_id, 'grammar_tax'));

        foreach ($ancestor_ids as $ancestor_id) {
          $ancestor = get_term($ancestor_id, 'grammar_tax');

          if ($ancestor && !is_wp_error($ancestor)) {
            $breadcrumb_terms[] = $ancestor;
          }
        }

        $breadcrumb_terms[] = $primary_grammar_term;
      }

      $topic_term_ids = wp_list_pluck($topic_terms, 'term_id');
      $level_term_ids = wp_list_pluck($level_terms, 'term_id');

      $get_topic_recommendations = static function ($post_type, $limit, $excluded_ids) use ($topic_term_ids, $level_term_ids) {
        if (!$topic_term_ids) {
          return array();
        }

        $base_args = array(
          'post_type' => $post_type,
          'post_status' => 'publish',
          'posts_per_page' => $limit,
          'post__not_in' => $excluded_ids,
          'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC'),
          'tax_query' => array(array(
            'taxonomy' => 'topic_tax',
            'field' => 'term_id',
            'terms' => $topic_term_ids,
          )),
        );

        $recommendations = array();

        if ($level_term_ids) {
          $same_level_args = $base_args;
          $same_level_args['tax_query'] = array(
            'relation' => 'AND',
            $base_args['tax_query'][0],
            array(
              'taxonomy' => 'level_tax',
              'field' => 'term_id',
              'terms' => $level_term_ids,
            ),
          );
          $same_level_query = new WP_Query($same_level_args);
          $recommendations = $same_level_query->posts;
        }

        $remaining = $limit - count($recommendations);

        if ($remaining > 0) {
          $fallback_args = $base_args;
          $fallback_args['posts_per_page'] = $remaining;
          $fallback_args['post__not_in'] = array_merge($excluded_ids, wp_list_pluck($recommendations, 'ID'));
          $fallback_query = new WP_Query($fallback_args);
          $recommendations = array_merge($recommendations, $fallback_query->posts);
        }

        return $recommendations;
      };

      $continue_practicing = $get_topic_recommendations('practice', 1, array());
      $related_lessons = $get_topic_recommendations('grammar', 3, array($post_id));
      $has_resources = $pdf_url || $audio_url || $slides_url;
    ?>

    <article class="sn-lesson">
      <nav class="sn-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url($grammar_url); ?>">Grammar</a>
        <?php foreach ($breadcrumb_terms as $term) : ?>
          <?php $term_link = get_term_link($term); ?>
          <?php if (!is_wp_error($term_link)) : ?>
            <span>/</span>
            <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </nav>

      <header class="sn-lesson-header">
        <h1 class="sn-post-title"><?php the_title(); ?></h1>
        <div class="sn-meta-row">
          <?php foreach ($level_terms as $term) : ?>
            <?php $term_link = get_term_link($term); ?>
            <?php if (!is_wp_error($term_link)) : ?>
              <span class="sn-pill"><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a></span>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if ($duration) : ?><span class="sn-pill"><?php echo esc_html($duration); ?></span><?php endif; ?>
          <span class="sn-pill"><a href="<?php echo esc_url($grammar_url); ?>">Grammar</a></span>
        </div>
      </header>

      <div class="sn-lesson-layout">
        <div class="sn-content sn-post-content">
          <?php the_content(); ?>

          <?php if ($continue_practicing) : ?>
            <section class="sn-lesson-cta">
              <h2>Practice this lesson</h2>
              <p>Complete exercises and check your answers.</p>
              <a class="sn-button" href="<?php echo esc_url(get_permalink($continue_practicing[0])); ?>">Go to practice</a>
            </section>
          <?php endif; ?>
        </div>

        <?php if ($has_resources || $flashcard_word || $next_lesson || $related_lessons) : ?>
          <aside class="sn-sidebar" aria-label="Lesson resources">
            <?php if ($has_resources || $continue_practicing) : ?>
              <section class="sn-sidebar-box">
                <h2>Lesson materials</h2>
                <nav class="sn-resource-list">
                  <?php if ($continue_practicing) : ?><a href="<?php echo esc_url(get_permalink($continue_practicing[0])); ?>">Practice exercises</a><?php endif; ?>
                  <?php if ($audio_url) : ?><a href="<?php echo esc_url($audio_url); ?>">Listen to the lesson</a><?php endif; ?>
                  <?php if ($pdf_url) : ?><a href="<?php echo esc_url($pdf_url); ?>">Download PDF</a><?php endif; ?>
                  <?php if ($slides_url) : ?><a href="<?php echo esc_url($slides_url); ?>">View slides</a><?php endif; ?>
                </nav>
              </section>
            <?php endif; ?>

            <?php if ($flashcard_word) : ?>
              <section class="sn-sidebar-box">
                <h2>Flashcard</h2>
                <div class="sn-flashcard">
                  <p><strong><?php echo esc_html($flashcard_word); ?></strong></p>
                  <?php if ($flashcard_meaning) : ?><p><em><?php echo esc_html($flashcard_meaning); ?></em></p><?php endif; ?>
                </div>
              </section>
            <?php endif; ?>

            <?php if ($next_lesson || $related_lessons) : ?>
              <section class="sn-sidebar-box">
                <h2>Continue learning</h2>
                <?php if ($next_lesson && $next_lesson->post_status === 'publish') : ?>
                  <a class="sn-next-lesson-link" href="<?php echo esc_url(get_permalink($next_lesson)); ?>">Next lesson: <?php echo esc_html(get_the_title($next_lesson)); ?> →</a>
                <?php else : ?>
                  <nav class="sn-related-list">
                    <?php foreach ($related_lessons as $lesson) : ?>
                      <a href="<?php echo esc_url(get_permalink($lesson)); ?>"><?php echo esc_html(get_the_title($lesson)); ?></a>
                    <?php endforeach; ?>
                  </nav>
                <?php endif; ?>
              </section>
            <?php endif; ?>
          </aside>
        <?php endif; ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
