<?php get_header(); ?>
<main>
  <article class="panel">
    <h1><?php the_title(); ?></h1>
    <div class="entry-content"><?php while (have_posts()) : the_post(); the_content(); endwhile; ?></div>
  </article>
</main>
<?php get_footer(); ?>
