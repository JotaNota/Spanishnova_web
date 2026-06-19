<?php get_header(); ?>

<?php
$current_term = get_queried_object();
$is_level_term = $current_term instanceof WP_Term && $current_term->taxonomy === 'level_tax';
$is_beginner = $is_level_term && $current_term->slug === 'beginner';
$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));
$content_post_types = ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'];
$other_post_types = $is_beginner ? ['vocabulary', 'readings', 'conversations', 'practice', 'resources'] : $content_post_types;

$grammar_path_query = null;

if ($is_beginner) {
  $grammar_path_query = new WP_Query([
    'post_type'           => 'grammar',
    'post_status'         => 'publish',
    'posts_per_page'      => -1,
    'ignore_sticky_posts' => true,
    'orderby'             => [
      'menu_order' => 'ASC',
      'title'      => 'ASC',
    ],
    'order'               => 'ASC',
    'tax_query'           => [
      [
        'taxonomy' => 'level_tax',
        'field'    => 'term_id',
        'terms'    => $current_term->term_id,
      ],
    ],
  ]);
}

$content_query = new WP_Query([
  'post_type'           => $other_post_types,
  'post_status'         => 'publish',
  'posts_per_page'      => 18,
  'paged'               => $paged,
  'ignore_sticky_posts' => true,
  'orderby'             => [
    'post_type' => 'ASC',
    'title'     => 'ASC',
  ],
  'order'               => 'ASC',
  'tax_query'           => [
    [
      'taxonomy' => 'level_tax',
      'field'    => 'term_id',
      'terms'    => $is_level_term ? $current_term->term_id : 0,
    ],
  ],
]);

if (!function_exists('spanishnova_level_type_label')) {
  function spanishnova_level_type_label($post_type) {
    $object = get_post_type_object($post_type);

    if (!$object) {
      return ucfirst((string) $post_type);
    }

    return $object->labels->singular_name ?: $object->labels->name;
  }
}
?>

<main class="level-route-page">
  <section class="panel level-route-hero">
    <p class="level-route-eyebrow">Learning level</p>
    <h1><?php single_term_title(); ?></h1>
    <?php if (term_description()) : ?>
      <div class="level-route-description">
        <?php echo wp_kses_post(term_description()); ?>
      </div>
    <?php else : ?>
      <p class="level-route-description">Start with the lessons that match this level, then keep exploring vocabulary, readings, conversations, practice, and resources.</p>
    <?php endif; ?>
  </section>

  <?php if ($is_beginner) : ?>
    <section class="panel beginner-grammar-path" aria-labelledby="beginner-grammar-path-title">
      <div class="level-section-heading">
        <div>
          <p class="level-route-eyebrow">Start here</p>
          <h2 id="beginner-grammar-path-title">Beginner Grammar Path</h2>
        </div>
        <?php if ($grammar_path_query && $grammar_path_query->have_posts()) : ?>
          <span class="level-route-count"><?php echo esc_html($grammar_path_query->found_posts); ?> lessons</span>
        <?php endif; ?>
      </div>

      <?php if ($grammar_path_query && $grammar_path_query->have_posts()) : ?>
        <ol class="grammar-path-list">
          <?php $step = 1; ?>
          <?php while ($grammar_path_query->have_posts()) : $grammar_path_query->the_post(); ?>
            <li class="grammar-path-step">
              <div class="grammar-step-number"><?php echo esc_html(str_pad((string) $step, 2, '0', STR_PAD_LEFT)); ?></div>
              <article class="grammar-step-card">
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 18)); ?></p>
                <a class="grammar-step-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf('Open %s', get_the_title())); ?>">Open lesson</a>
              </article>
            </li>

            <?php if ($step % 4 === 0 && $step < $grammar_path_query->post_count) : ?>
              <li class="grammar-path-checkpoint">
                <span>Checkpoint</span>
              </li>
            <?php endif; ?>

            <?php $step++; ?>
          <?php endwhile; ?>
        </ol>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="empty-state">No published beginner grammar lessons yet.</p>
      <?php endif; ?>
    </section>
  <?php endif; ?>

  <section class="panel level-content-panel" aria-labelledby="level-content-title">
    <div class="level-section-heading">
      <div>
        <p class="level-route-eyebrow">Keep going</p>
        <h2 id="level-content-title">
          <?php if ($is_beginner) : ?>
            More beginner content
          <?php else : ?>
            <?php single_term_title(); ?> content
          <?php endif; ?>
        </h2>
      </div>
      <span class="level-route-count"><?php echo esc_html($content_query->found_posts); ?> results</span>
    </div>

    <?php if ($content_query->have_posts()) : ?>
      <div class="activity-list level-content-list">
        <?php while ($content_query->have_posts()) : $content_query->the_post(); ?>
          <a class="activity-row level-content-row" href="<?php the_permalink(); ?>">
            <span class="label"><?php echo esc_html(spanishnova_level_type_label(get_post_type())); ?></span>
            <div>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt(get_the_ID()), 22)); ?></p>
            </div>
            <span class="arrow" aria-hidden="true">&rarr;</span>
          </a>
        <?php endwhile; ?>
      </div>

      <?php
      $pagination = paginate_links([
        'total'   => $content_query->max_num_pages,
        'current' => $paged,
        'type'    => 'list',
      ]);
      ?>

      <?php if ($pagination) : ?>
        <nav class="pagination level-pagination" aria-label="Level content pagination">
          <?php echo wp_kses_post($pagination); ?>
        </nav>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="empty-state">No other published content for this level yet.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
