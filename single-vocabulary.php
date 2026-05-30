<?php get_header(); ?>

<main class="sn-lesson-page sn-vocabulary-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
