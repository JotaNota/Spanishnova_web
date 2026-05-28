<?php get_header(); ?>

<main>
  <section class="panel">
    <h1>Grammar</h1>

    <h2>Browse by category</h2>

    <div class="activity-list">
      <?php
      $grammar_categories = [
        'tenses',
        'verbs',
        'moods',
        'parts-of-speech',
      ];

      foreach ($grammar_categories as $grammar_category_slug) :
        $grammar_category = get_term_by('slug', $grammar_category_slug, 'grammar_tax');
        $grammar_category_link = get_term_link($grammar_category_slug, 'grammar_tax');

        if (!$grammar_category || is_wp_error($grammar_category) || is_wp_error($grammar_category_link)) {
          continue;
        }
      ?>
        <a class="activity-row" href="<?php echo esc_url($grammar_category_link); ?>">
          <span class="label">Grammar</span>
          <div>
            <h3><?php echo esc_html($grammar_category->name); ?></h3>
          </div>
          <span class="arrow">→</span>
        </a>
      <?php endforeach; ?>
    </div>

    <h2>Latest grammar lessons</h2>

    <?php
    $latest_grammar_lessons = new WP_Query([
      'post_type' => 'grammar',
      'posts_per_page' => 6,
      'post_status' => 'publish',
      'ignore_sticky_posts' => true,
    ]);
    ?>

    <?php if ($latest_grammar_lessons->have_posts()) : ?>
      <div class="activity-list">
        <?php while ($latest_grammar_lessons->have_posts()) : $latest_grammar_lessons->the_post(); ?>
          <a class="activity-row" href="<?php echo esc_url(get_permalink()); ?>">
            <span class="label">Grammar</span>
            <div>
              <h3><?php echo esc_html(get_the_title()); ?></h3>
              <p><?php echo esc_html(get_the_excerpt()); ?></p>
            </div>
            <span class="arrow">→</span>
          </a>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p>No grammar lessons yet.</p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
  </section>
</main>

<?php get_footer(); ?>
