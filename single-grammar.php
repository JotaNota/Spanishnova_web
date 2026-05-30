<?php
get_header();
?>

<main class="sn-grammar-page">
  <?php
  while ( have_posts() ) :
    the_post();
    ?>

    <article class="sn-grammar-content">
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>

  <?php endwhile; ?>
</main>

<?php
get_footer();
