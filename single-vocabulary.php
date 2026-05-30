<?php get_header(); ?>

<main class="sn-lesson-page sn-vocabulary-page">
  <?php while (have_posts()) : the_post(); ?>
    <article class="sn-lesson-shell">
      <nav class="sn-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        <span>/</span>
        <span>Vocabulary</span>
      </nav>

      <div class="sn-lesson-meta">
        <span class="sn-pill">Vocabulary</span>
      </div>

      <div class="entry-content sn-lesson-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>