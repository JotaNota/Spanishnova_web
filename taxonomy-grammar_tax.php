<?php
$current_term = get_queried_object();

if ($current_term instanceof WP_Term && 'grammar_tax' === $current_term->taxonomy) {
  wp_safe_redirect(add_query_arg('grammar_tax', $current_term->slug, home_url('/grammar/')), 301);
  exit;
}

get_header();
?>

<main>
  <section class="panel grammar-archive-panel">
    <h1>Grammar</h1>
    <p>No grammar term found.</p>
  </section>
</main>

<?php get_footer(); ?>
