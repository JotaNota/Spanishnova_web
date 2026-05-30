<?php get_header(); ?>

<?php
$grammar_terms = get_terms([
  'taxonomy'   => 'grammar_tax',
  'hide_empty' => true,
  'parent'     => 0,
  'orderby'    => 'name',
  'order'      => 'ASC',
]);

function spanishnova_get_grammar_lessons_by_term($term_id) {
  return new WP_Query([
    'post_type'           => 'grammar',
    'post_status'         => 'publish',
    'posts_per_page'      => -1,
    'ignore_sticky_posts' => true,
    'orderby'             => 'title',
    'order'               => 'ASC',
    'tax_query'           => [
      [
        'taxonomy'         => 'grammar_tax',
        'field'            => 'term_id',
        'terms'            => $term_id,
        'include_children' => false,
      ],
    ],
  ]);
}
?>

<main>
  <section class="panel grammar-archive-panel">
    <h1>Grammar</h1>

    <?php if (!empty($grammar_terms) && !is_wp_error($grammar_terms)) : ?>
      <div class="grammar-accordion">
        <?php foreach ($grammar_terms as $parent_term) : ?>
          <?php
          $child_terms = get_terms([
            'taxonomy'   => 'grammar_tax',
            'hide_empty' => true,
            'parent'     => $parent_term->term_id,
            'orderby'    => 'name',
            'order'      => 'ASC',
          ]);

          $has_child_terms = !empty($child_terms) && !is_wp_error($child_terms);
          $parent_lessons = spanishnova_get_grammar_lessons_by_term($parent_term->term_id);
          ?>

          <details class="grammar-accordion-item">
            <summary class="grammar-parent-summary"><?php echo esc_html($parent_term->name); ?></summary>

            <?php if ($has_child_terms) : ?>
              <div class="grammar-child-list">
                <?php foreach ($child_terms as $child_term) : ?>
                  <?php $child_lessons = spanishnova_get_grammar_lessons_by_term($child_term->term_id); ?>

                  <?php if ($child_lessons->have_posts()) : ?>
                    <details class="grammar-child-item" open>
                      <summary class="grammar-child-summary"><?php echo esc_html($child_term->name); ?></summary>
                      <ul class="grammar-lesson-list">
                        <?php while ($child_lessons->have_posts()) : $child_lessons->the_post(); ?>
                          <li><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></li>
                        <?php endwhile; ?>
                      </ul>
                    </details>
                  <?php endif; ?>

                  <?php wp_reset_postdata(); ?>
                <?php endforeach; ?>
              </div>
            <?php elseif ($parent_lessons->have_posts()) : ?>
              <ul class="grammar-lesson-list grammar-lesson-list-parent">
                <?php while ($parent_lessons->have_posts()) : $parent_lessons->the_post(); ?>
                  <li><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
          </details>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p>No grammar lessons yet.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
