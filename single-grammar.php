<?php get_header(); ?>

<main class="sn-single-page sn-grammar-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $grammar_terms = get_the_terms(get_the_ID(), 'grammar_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');

      $grammar_terms = (!is_wp_error($grammar_terms) && $grammar_terms) ? $grammar_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();

      $grammar_url = get_post_type_archive_link('grammar');
      $grammar_url = $grammar_url ? $grammar_url : home_url('/grammar/');

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
    ?>

    <div class="sn-breadcrumb">
      <a href="<?php echo esc_url($grammar_url); ?>">Grammar</a>
      <?php foreach ($breadcrumb_terms as $term) : ?>
        <?php $term_link = get_term_link($term); ?>
        <?php if (!is_wp_error($term_link)) : ?>
          <span>/</span>
          <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="sn-meta-row">
      <span class="sn-pill"><a href="<?php echo esc_url($grammar_url); ?>">Grammar</a></span>
      <?php foreach ($level_terms as $term) : ?>
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

    <?php
      // Chronological navigation within Grammar; no lesson sequence metadata exists yet.
      $next_lesson = get_next_post();
      $next_url = $next_lesson ? get_permalink($next_lesson) : $grammar_url;
    ?>
    <section class="sn-lesson-end" aria-label="Lesson completion and navigation">
      <div class="sn-lesson-complete">
        <span class="sn-lesson-check" aria-hidden="true">✓</span>
        <div>
          <h2>Lesson complete</h2>
          <p>You've reached the end of this lesson.</p>
        </div>
      </div>

      <div class="sn-lesson-actions">
        <a class="sn-end-button sn-end-primary" href="<?php echo esc_url($next_url); ?>">
          <?php echo esc_html($next_lesson ? 'Next lesson →' : 'More lessons →'); ?>
        </a>
        <?php if (comments_open()) : ?>
          <a class="sn-end-button" href="#respond">Leave a comment</a>
        <?php endif; ?>
        <button class="sn-end-button sn-lesson-contact-button" type="button">Contact us</button>
      </div>
    </section>
    <?php if (comments_open() || get_comments_number()) : ?>
      <?php comments_template(); ?>
    <?php endif; ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>