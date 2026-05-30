<?php
get_header();
?>

<main class="sn-grammar-page">
  <?php
  while ( have_posts() ) :
    the_post();

    $level_terms   = get_the_terms( get_the_ID(), 'level_tax' );
    $grammar_terms = get_the_terms( get_the_ID(), 'grammar_tax' );

    $active_parent = null;
    $active_child  = null;

    if ( ! empty( $grammar_terms ) && ! is_wp_error( $grammar_terms ) ) {
      foreach ( $grammar_terms as $term ) {
        if ( 0 === (int) $term->parent && ! $active_parent ) {
          $active_parent = $term;
        }
      }

      foreach ( $grammar_terms as $term ) {
        if ( 0 !== (int) $term->parent ) {
          $parent = get_term( $term->parent, 'grammar_tax' );

          if ( $parent && ! is_wp_error( $parent ) ) {
            $active_parent = $parent;
            $active_child  = $term;
            break;
          }
        }
      }
    }
    ?>

    <article class="sn-grammar-content">

      <div class="sn-grammar-breadcrumb">
        Grammar
        <?php if ( $active_parent ) : ?>
          / <?php echo esc_html( $active_parent->name ); ?>
        <?php endif; ?>
        <?php if ( $active_child ) : ?>
          / <?php echo esc_html( $active_child->name ); ?>
        <?php endif; ?>
      </div>

      <div class="sn-grammar-pills">
        <span>Grammar</span>

        <?php if ( ! empty( $level_terms ) && ! is_wp_error( $level_terms ) ) : ?>
          <span><?php echo esc_html( $level_terms[0]->name ); ?></span>
        <?php endif; ?>
      </div>
    
      <h1><?php the_title(); ?></h1>

      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>

  <?php endwhile; ?>
</main>

<?php
get_footer();