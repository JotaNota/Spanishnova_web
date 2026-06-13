<?php get_header(); ?>

<main class="sn-single-page sn-page">
  <?php while (have_posts()) : the_post(); ?>
    <h1 class="sn-post-title"><?php the_title(); ?></h1>

    <div class="sn-post-content">
      <?php the_content(); ?>
    </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
