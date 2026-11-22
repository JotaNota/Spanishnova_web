<?php get_header(); ?>
<main class="sn-practice-archive">
  <section class="panel">
    <h1><?php post_type_archive_title(); ?></h1>
    <?php if (have_posts()) : ?>
      <div class="activity-list">
        <?php while (have_posts()) : the_post(); ?>
          <a class="explore-result explore-result--practice" href="<?php the_permalink(); ?>">
            <span class="explore-result-meta">
              <span class="explore-result-type"><?php echo esc_html(get_post_type()); ?></span>
            </span>
            <h3 class="explore-result-title"><?php the_title(); ?></h3>
            <p class="explore-result-description"><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p>
            <span class="explore-result-arrow" aria-hidden="true">→</span>
          </a>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p>No content yet.</p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
