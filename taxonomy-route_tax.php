<?php
wp_enqueue_style('spanishnova-level-route', get_template_directory_uri() . '/assets/css/level-route.css', ['spanishnova-archives'], '0.1.0');
get_header();
?>

<?php
$current_term = get_queried_object();
$is_route_term = $current_term instanceof WP_Term && $current_term->taxonomy === 'route_tax';

$route_blocks = [
  '1' => [
    'label' => 'First sentences',
    'description' => 'Build simple Spanish sentences with ser, estar, hay, and basic nouns.',
  ],
  '2' => [
    'label' => 'Needs and obligation',
    'description' => 'Say what someone has, needs, or has to do.',
  ],
  '3' => [
    'label' => 'Places and movement',
    'description' => 'Talk about where people are, where they go, and near-future plans.',
  ],
  '4' => [
    'label' => 'Daily actions',
    'description' => 'Describe routines, common actions, and what is happening now.',
  ],
  '5' => [
    'label' => 'People and description',
    'description' => 'Describe people, ownership, and things around you.',
  ],
  '6' => [
    'label' => 'Food and preferences',
    'description' => 'Express wants, needs, choices, and preferences.',
  ],
  '7' => [
    'label' => 'Questions and small talk',
    'description' => 'Ask simple questions and talk about everyday frequency.',
  ],
];

$fallback_route_map = [
  'sustantivos-basicos' => ['block' => '1', 'step' => 5],
  'ser-presente' => ['block' => '1', 'step' => 10],
  'estar-presente' => ['block' => '1', 'step' => 20],
  'hay' => ['block' => '1', 'step' => 30],
  'tener-presente' => ['block' => '2', 'step' => 10],
  'hay-que' => ['block' => '2', 'step' => 20],
  'tener-que-deber' => ['block' => '2', 'step' => 30],
  'verbos-basicos-tener-que' => ['block' => '2', 'step' => 40],
  'ir-presente' => ['block' => '3', 'step' => 10],
  'ir-a-infinitivo' => ['block' => '3', 'step' => 20],
  'adverbios-lugar' => ['block' => '3', 'step' => 30],
  'hacer-presente' => ['block' => '4', 'step' => 10],
  'presente-indicativo-regulares' => ['block' => '4', 'step' => 20],
  'presente-indicativo-irregulares' => ['block' => '4', 'step' => 30],
  'estar-gerundio' => ['block' => '4', 'step' => 40],
  'adjetivos-posesivos' => ['block' => '5', 'step' => 10],
  'demostrativos' => ['block' => '5', 'step' => 20],
  'querer-preferir-necesitar' => ['block' => '6', 'step' => 10],
  'pronombres-interrogativos' => ['block' => '7', 'step' => 10],
  'adverbios-frecuencia' => ['block' => '7', 'step' => 20],
];

$route_groups = [];
$route_total = 0;

if (!function_exists('spanishnova_route_type_label')) {
  function spanishnova_route_type_label($post_type) {
    $object = get_post_type_object($post_type);

    if (!$object) {
      return ucfirst((string) $post_type);
    }

    return $object->labels->singular_name ?: $object->labels->name;
  }
}

if (!function_exists('spanishnova_route_add_item')) {
  function spanishnova_route_add_item(&$route_groups, $block, $step, $post_id, $route_blocks) {
    if (!$block || empty($route_blocks[$block])) {
      return;
    }

    if (empty($route_groups[$block])) {
      $route_groups[$block] = [
        'block' => $block,
        'label' => $route_blocks[$block]['label'],
        'description' => $route_blocks[$block]['description'],
        'items' => [],
      ];
    }

    $route_groups[$block]['items'][] = [
      'step' => (int) $step,
      'title' => get_the_title($post_id),
      'url' => get_permalink($post_id),
      'type' => spanishnova_route_type_label(get_post_type($post_id)),
    ];
  }
}

if ($is_route_term) {
  $route_query = new WP_Query([
    'post_type' => 'grammar',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'ignore_sticky_posts' => true,
    'orderby' => 'title',
    'order' => 'ASC',
    'tax_query' => [
      [
        'taxonomy' => 'route_tax',
        'field' => 'term_id',
        'terms' => $current_term->term_id,
      ],
    ],
  ]);

  if ($route_query->have_posts()) {
    while ($route_query->have_posts()) {
      $route_query->the_post();
      spanishnova_route_add_item(
        $route_groups,
        get_post_meta(get_the_ID(), 'route_block', true),
        get_post_meta(get_the_ID(), 'route_step', true),
        get_the_ID(),
        $route_blocks
      );
    }
    wp_reset_postdata();
  }

  if (empty($route_groups) && $current_term->slug === 'beginner') {
    $level_term = get_term_by('slug', 'beginner', 'level_tax');

    if ($level_term && !is_wp_error($level_term)) {
      $fallback_query = new WP_Query([
        'post_type' => 'grammar',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'ignore_sticky_posts' => true,
        'orderby' => [
          'menu_order' => 'ASC',
          'title' => 'ASC',
        ],
        'order' => 'ASC',
        'tax_query' => [
          [
            'taxonomy' => 'level_tax',
            'field' => 'term_id',
            'terms' => $level_term->term_id,
          ],
        ],
      ]);

      if ($fallback_query->have_posts()) {
        while ($fallback_query->have_posts()) {
          $fallback_query->the_post();
          $slug = get_post_field('post_name', get_the_ID());

          if (empty($fallback_route_map[$slug])) {
            continue;
          }

          spanishnova_route_add_item(
            $route_groups,
            $fallback_route_map[$slug]['block'],
            $fallback_route_map[$slug]['step'],
            get_the_ID(),
            $route_blocks
          );
        }
        wp_reset_postdata();
      }
    }
  }

  ksort($route_groups, SORT_NATURAL);

  foreach ($route_groups as &$route_group) {
    usort($route_group['items'], function ($a, $b) {
      if ($a['step'] === $b['step']) {
        return strcasecmp($a['title'], $b['title']);
      }

      return $a['step'] <=> $b['step'];
    });

    $route_total += count($route_group['items']);
  }
  unset($route_group);
}
?>

<main class="level-route-page">
  <section class="panel level-route-hero">
    <p class="level-route-eyebrow">Learning route</p>
    <h1><?php single_term_title(); ?></h1>
    <?php if (term_description()) : ?>
      <div class="level-route-description">
        <?php echo wp_kses_post(term_description()); ?>
      </div>
    <?php else : ?>
      <p class="level-route-description">Follow the route by blocks. Each block groups compact grammar lessons around one practical step.</p>
    <?php endif; ?>
  </section>

  <section class="panel beginner-grammar-path" aria-labelledby="route-path-title">
    <div class="level-section-heading">
      <div>
        <p class="level-route-eyebrow">Start here</p>
        <h2 id="route-path-title">
          <?php if ($is_route_term && $current_term->slug === 'beginner') : ?>
            Beginner Grammar Path
          <?php else : ?>
            <?php single_term_title(); ?> Path
          <?php endif; ?>
        </h2>
      </div>
      <?php if ($route_total > 0) : ?>
        <span class="level-route-count"><?php echo esc_html($route_total); ?> lessons</span>
      <?php endif; ?>
    </div>

    <?php if ($route_total > 0) : ?>
      <div class="grammar-route-blocks">
        <?php foreach ($route_groups as $route_group) : ?>
          <section class="grammar-route-block" aria-labelledby="<?php echo esc_attr('route-block-' . $route_group['block']); ?>">
            <div class="grammar-route-block-heading">
              <span class="grammar-route-block-number"><?php echo esc_html($route_group['block']); ?></span>
              <div>
                <h3 id="<?php echo esc_attr('route-block-' . $route_group['block']); ?>"><?php echo esc_html($route_group['label']); ?></h3>
                <p><?php echo esc_html($route_group['description']); ?></p>
              </div>
            </div>

            <div class="grammar-route-lessons">
              <?php foreach ($route_group['items'] as $route_item) : ?>
                <a class="grammar-lesson-chip" href="<?php echo esc_url($route_item['url']); ?>">
                  <span class="grammar-lesson-type"><?php echo esc_html($route_item['type']); ?></span>
                  <span><?php echo esc_html($route_item['title']); ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p class="empty-state">No published lessons in this route yet.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
