<?php get_header(); ?>

<?php
$featured_query = new WP_Query([
  'post_type'      => ['grammar', 'vocabulary', 'reading'],
  'post_status'    => 'publish',
  'posts_per_page' => 6,
  'orderby'        => 'date',
  'order'          => 'DESC',
]);

$post_type_labels = [
  'grammar'    => 'Grammar',
  'vocabulary' => 'Vocabulary',
  'reading'    => 'Reading',
];
?>

<main>
  <section class="hero">
    <form class="search-bar" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input type="search" name="s" placeholder="Search lessons, readings, vocabulary..." value="<?php echo get_search_query(); ?>">
      <button class="search-icon" type="submit" aria-label="Search">⌕</button>
    </form>

    <div class="eyebrow">Simple Spanish.</div>
    <h1>Practice Spanish with useful lessons, readings, and examples.</h1>
    <p class="hero-intro">Start with grammar, vocabulary, short readings, and practice content organized by level and topic.</p>

    <div class="hero-actions">
      <a class="btn btn-primary" href="<?php echo esc_url(home_url('/grammar/')); ?>">Start with grammar</a>
      <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/readings/')); ?>">Browse readings</a>
    </div>
  </section>

  <section class="content-layout">
    <div class="panel">
      <h2>Latest lessons and activities</h2>

      <div class="activity-list">
        <?php if ($featured_query->have_posts()) : ?>
          <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
            <?php
            $post_type = get_post_type();
            $label = $post_type_labels[$post_type] ?? ucfirst($post_type);
            $excerpt = get_the_excerpt();

            if (!$excerpt) {
              $excerpt = wp_trim_words(wp_strip_all_tags(get_the_content()), 18);
            }
            ?>

            <a class="activity-row" href="<?php the_permalink(); ?>">
              <span class="label"><?php echo esc_html($label); ?></span>
              <div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html(wp_trim_words($excerpt, 18)); ?></p>
              </div>
              <span class="arrow">→</span>
            </a>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php else : ?>
          <p class="empty-state">No lessons published yet.</p>
        <?php endif; ?>
      </div>
    </div>

    <aside>
      <div class="panel side-card">
        <h2>Resources</h2>
        <ul>
          <li>Beginner grammar checklist</li>
          <li>Vocabulary PDFs</li>
          <li>Reading PDFs</li>
          <li>Worksheets</li>
        </ul>
      </div>

      <div class="panel side-card">
        <h2>Level</h2>
        <ul>
          <li>Beginner</li>
          <li>Intermediate</li>
          <li>Advanced</li>
        </ul>
      </div>
    </aside>
  </section>

  <section class="category-strip">
    <a class="category-card" href="<?php echo esc_url(home_url('/grammar/')); ?>">
      <h3>Grammar</h3>
      <p>Verb tenses, pronouns, adjectives, and sentence structure.</p>
    </a>

    <a class="category-card" href="<?php echo esc_url(home_url('/vocabulary/')); ?>">
      <h3>Vocabulary</h3>
      <p>Useful words and phrases organized by topic.</p>
    </a>

    <a class="category-card" href="<?php echo esc_url(home_url('/readings/')); ?>">
      <h3>Readings</h3>
      <p>Short texts for practice and comprehension.</p>
    </a>

    <a class="category-card" href="<?php echo esc_url(home_url('/practice/')); ?>">
      <h3>Practice</h3>
      <p>Exercises, answer reveals, and worksheets.</p>
    </a>
  </section>

  <section class="audience-box">
    <div>
      <h2>For students</h2>
      <p>Use lessons, readings, and vocabulary pages to build Spanish step by step.</p>
    </div>

    <div>
      <h2>For teachers</h2>
      <p>Use readings, worksheets, and examples as classroom support.</p>
    </div>
  </section>

  <section class="support" id="donate">
    <div>
      <h2>Support SpanishNova.</h2>
      <p>Help support lessons, readings, worksheets, audio, and future learning content.</p>
    </div>
    <a class="btn btn-primary" href="<?php echo esc_url(home_url('/donate/')); ?>">Donate</a>
  </section>
</main>

<?php get_footer(); ?>