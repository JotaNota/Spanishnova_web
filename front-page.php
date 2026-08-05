<?php
wp_enqueue_style('spanishnova-archives', get_template_directory_uri() . '/assets/css/archives.css', ['spanishnova-main'], '0.1.0');
get_header();
?>

<?php
$paged = max(1, get_query_var('paged'), get_query_var('page'));

$featured_query = new WP_Query([
  'post_type'      => ['grammar', 'vocabulary', 'reading'],
  'post_status'    => 'publish',
  'posts_per_page' => 8,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'paged'          => $paged,
]);

$post_type_labels = [
  'grammar'    => 'Grammar',
  'vocabulary' => 'Vocabulary',
  'reading'    => 'Reading',
];

$level_slugs = [
  'starter' => 'Starter',
  'beginner' => 'Beginner',
  'intermediate' => 'Intermediate',
  'upper-intermediate' => 'Upper-Intermediate',
  'advanced' => 'Advanced',
];
$level_links = [];

foreach ($level_slugs as $level_slug => $level_label) {
  $level_term = get_term_by('slug', $level_slug, 'level_tax');

  if (!$level_term || is_wp_error($level_term)) {
    continue;
  }

  $level_url = get_term_link($level_term);

  if (is_wp_error($level_url)) {
    continue;
  }

  $level_links[] = [
    'label' => $level_label,
    'url' => $level_url,
  ];
}
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
      <a href="<?php echo esc_url(home_url('/explore/')); ?>">Explore all content</a>
    </div>
  </section>

    <aside class="homepage-sidebar">
      <div class="panel side-card">
        <h2>By level</h2>
        <ul>
          <?php foreach ($level_links as $level_link) : ?>
            <li>
              <a href="<?php echo esc_url($level_link['url']); ?>">
                <?php echo esc_html($level_link['label']); ?>
              </a>
            </li>
          <?php endforeach; ?>
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

          <?php
          $pagination = paginate_links([
            'total'   => $featured_query->max_num_pages,
            'current' => $paged,
            'type'    => 'list',
            'mid_size' => 1,
            'end_size' => 1,
            'prev_text' => '←',
            'next_text' => 'Next →',
          ]);
          ?>

          <?php if ($pagination) : ?>
            <nav class="pagination explore-pagination" aria-label="Latest lessons and activities pagination">
              <?php echo wp_kses_post($pagination); ?>
            </nav>
          <?php endif; ?>
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

</main>

<?php get_footer(); ?>
