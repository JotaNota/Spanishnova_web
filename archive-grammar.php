<?php get_header(); ?>

<?php
$grammar_terms = get_terms([
  'taxonomy'   => 'grammar_tax',
  'hide_empty' => true,
  'parent'     => 0,
  'orderby'    => 'name',
  'order'      => 'ASC',
]);
?>

<main>
  <section class="panel">
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

          $parent_lessons = new WP_Query([
            'post_type'              => 'grammar',
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'ignore_sticky_posts'    => true,
            'tax_query'              => [
              [
                'taxonomy'         => 'grammar_tax',
                'field'            => 'term_id',
                'terms'            => $parent_term->term_id,
                'include_children' => false,
              ],
            ],
          ]);
          ?>

          <details class="grammar-accordion-item">
            <summary><?php echo esc_html($parent_term->name); ?></summary>

            <?php if ($parent_lessons->have_posts()) : ?>
              <div class="activity-list">
                <?php while ($parent_lessons->have_posts()) : $parent_lessons->the_post(); ?>
                  <a class="activity-row" href="<?php echo esc_url(get_permalink()); ?>">
                    <span class="label">Grammar</span>
                    <div>
                      <h3><?php echo esc_html(get_the_title()); ?></h3>
                      <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    </div>
                    <span class="arrow">→</span>
                  </a>
                <?php endwhile; ?>
              </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

            <?php if (!empty($child_terms) && !is_wp_error($child_terms)) : ?>
              <?php foreach ($child_terms as $child_term) : ?>
                <?php
                $child_lessons = new WP_Query([
                  'post_type'              => 'grammar',
                  'post_status'            => 'publish',
                  'posts_per_page'         => -1,
                  'ignore_sticky_posts'    => true,
                  'tax_query'              => [
                    [
                      'taxonomy'         => 'grammar_tax',
                      'field'            => 'term_id',
                      'terms'            => $child_term->term_id,
                      'include_children' => false,
                    ],
                  ],
                ]);
                ?>

                <?php if ($child_lessons->have_posts()) : ?>
                  <div class="grammar-child-group">
                    <h2><?php echo esc_html($child_term->name); ?></h2>
                    <div class="activity-list">
                      <?php while ($child_lessons->have_posts()) : $child_lessons->the_post(); ?>
                        <a class="activity-row" href="<?php echo esc_url(get_permalink()); ?>">
                          <span class="label">Grammar</span>
                          <div>
                            <h3><?php echo esc_html(get_the_title()); ?></h3>
                            <p><?php echo esc_html(get_the_excerpt()); ?></p>
                          </div>
                          <span class="arrow">→</span>
                        </a>
                      <?php endwhile; ?>
                    </div>
                  </div>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </details>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p>No grammar lessons yet.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
