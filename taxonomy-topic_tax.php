<?php get_header(); ?>
<main>
  <section class="panel">
    <h1><?php single_term_title(); ?></h1>
    <?php if (have_posts()) : ?>
      <div class="activity-list">
      <?php while (have_posts()) : the_post(); ?>
        <a class="activity-row" href="<?php the_permalink(); ?>">
          <span class="label"><?php echo esc_html(get_post_type()); ?></span>
          <div><h3><?php the_title(); ?></h3><p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p></div>
          <span class="arrow">→</span>
        </a>
      <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p>No content yet.</p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
