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
  <section class="home-practice-layout">
    <section class="hero practice-hero">
    <h1>Practice Spanish</h1>

    <form class="search-bar" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" onsubmit="if (!this.s.value.trim()) { window.location.href='<?php echo esc_url(home_url('/explore/')); ?>'; return false; }">
      <input type="search" name="s" placeholder="Search lessons, readings, vocabulary..." value="<?php echo get_search_query(); ?>">
      <button class="search-icon" type="submit" aria-label="Search">⌕</button>
    </form>

    <div class="practice-paths" aria-label="Practice paths">
      <a href="<?php echo esc_url(home_url('/grammar/')); ?>">Learn more grammar</a>
      <a href="<?php echo esc_url(home_url('/vocabulary/')); ?>">Expand vocabulary</a>
      <a href="<?php echo esc_url(home_url('/readings/')); ?>">Read interesting stories</a>
      <a href="<?php echo esc_url(home_url('/practice/')); ?>">Practice with exercises</a>
      <a href="<?php echo esc_url(home_url('/explore/')); ?>">Explore all content</a>
    </div>
  </section>

    <aside class="homepage-sidebar">
      <div class="panel side-card">
        <h2>Explore by level</h2>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/level/beginner/')); ?>">Beginner</a></li>
          <li><a href="<?php echo esc_url(home_url('/level/intermediate/')); ?>">Intermediate</a></li>
          <li><a href="<?php echo esc_url(home_url('/level/advanced/')); ?>">Advanced</a></li>
          <li><a href="<?php echo esc_url(home_url('/resources/worksheets/')); ?>">Worksheets</a></li>
        </ul>
      </div>
    </aside>
  </section>

  <section class="content-layout latest-layout">
    <div class="panel">
      <h2>Latest lessons and activities</h2>

      <div class="activity-list">
        <?php if ($featured_query->have_posts()) : ?>
          <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
            <?php
            $post_type = get_post_type();
            $label = $post_type_labels[$post_type] ?? ucfirst($post_type);
            $excerpt = spanishnova_get_card_excerpt(get_the_ID());
            ?>

            <a class="activity-row" href="<?php the_permalink(); ?>">
              <span class="label"><?php echo esc_html($label); ?></span>
              <div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html(wp_trim_words($excerpt, 22)); ?></p>
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
