<?php get_header(); ?>

<?php
$current_term = get_queried_object();
$is_level_term = $current_term instanceof WP_Term && $current_term->taxonomy === 'level_tax';

$level_slugs = [
  'starter' => 'Starter',
  'beginner' => 'Beginner',
  'intermediate' => 'Intermediate',
  'upper-intermediate' => 'Upper-Intermediate',
  'advanced' => 'Advanced',
];
$level_links = [];
$level_modules = [];
$level_lesson_total = 0;

foreach ($level_slugs as $level_slug => $level_label) {
  $level_term = get_term_by('slug', $level_slug, 'level_tax');

  if (!$level_term || is_wp_error($level_term)) {
    continue;
  }

  $level_url = get_term_link($level_term);

  if (is_wp_error($level_url)) {
    continue;
  }

  $level_links[] = [
    'label' => $level_label,
    'slug' => $level_slug,
    'url' => $level_url,
  ];
}

$level_slug = $is_level_term ? $current_term->slug : '';
$curricula = [];
if (in_array($level_slug, ['starter', 'beginner'], true)) {
  require_once get_template_directory() . '/inc/level-route-curriculum.php';
  $curricula = spanishnova_level_curriculum();
}
if (isset($curricula[$level_slug])) {
  $published_posts = get_posts([
    'post_type' => ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'],
    'post_status' => 'publish',
    'numberposts' => -1,
  ]);
  $posts_by_slug = [];
  foreach ($published_posts as $p) {
    $posts_by_slug[get_post_type($p) . ':' . $p->post_name] = $p;
  }
  foreach ($curricula[$level_slug] as $i => $module) {
    $items = [];
    foreach ($module['items'] as $j => $entry) {
      $key = strtolower($entry['type']) . ':' . $entry['slug'];
      $p = $entry['slug'] !== '' ? ($posts_by_slug[$key] ?? null) : null;
      $items[] = [
        'order' => $j + 1,
        'title' => $entry['title'],
        'url' => $p ? get_permalink($p) : '',
        'type' => $entry['type'],
        'associated' => $entry['associated'],
        'excerpt' => '',
      ];
      $level_lesson_total++;
    }
    $level_modules[] = ['label' => $module['label'], 'number' => $i + 1, 'items' => $items];
  }
} else {
$level_query = new WP_Query([
  'post_type' => 'grammar',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'ignore_sticky_posts' => true,
  'orderby' => 'title',
  'order' => 'ASC',
  'tax_query' => [
    [
      'taxonomy' => 'level_tax',
      'field' => 'term_id',
      'terms' => $is_level_term ? $current_term->term_id : 0,
    ],
  ],
  'meta_query' => [
    [
      'key' => 'level_roadmap_source',
      'value' => 'levels-roadmap-current',
    ],
  ],
]);

if ($level_query->have_posts()) {
  while ($level_query->have_posts()) {
    $level_query->the_post();
    $module = trim((string) get_post_meta(get_the_ID(), 'level_module', true));
    $order_value = get_post_meta(get_the_ID(), 'level_order', true);
    $has_order = $order_value !== '' && is_numeric($order_value);
    $order = $has_order ? (int) $order_value : null;
    $lesson = [
      'order' => $order,
      'title' => get_the_title(),
      'url' => get_permalink(),
      'excerpt' => trim((string) get_post_field('post_excerpt', get_the_ID())),
    ];

    if ($module === '' || !$has_order) {
      continue;
    }

    if (empty($level_modules[$module])) {
      $level_modules[$module] = [
        'label' => $module,
        'first_order' => $order,
        'items' => [],
      ];
    }

    if ($order < $level_modules[$module]['first_order']) {
      $level_modules[$module]['first_order'] = $order;
    }

    $level_modules[$module]['items'][] = $lesson;
    $level_lesson_total++;
  }
  wp_reset_postdata();
}

uasort($level_modules, function ($a, $b) {
  if ($a['first_order'] === $b['first_order']) {
    return strcasecmp($a['label'], $b['label']);
  }

  return $a['first_order'] <=> $b['first_order'];
});

foreach ($level_modules as &$level_module) {
  usort($level_module['items'], function ($a, $b) {
    if ($a['order'] === $b['order']) {
      return strcasecmp($a['title'], $b['title']);
    }

    return $a['order'] <=> $b['order'];
  });
}
unset($level_module);
}


?>

<main class="level-route-page starter-level-page">
  <section class="panel starter-level-hero">
    <p class="level-route-eyebrow">Learning level</p>
    <h1><?php single_term_title(); ?></h1>
    <?php if (term_description()) : ?>
      <div class="level-route-description">
        <?php echo wp_kses_post(term_description()); ?>
      </div>
    <?php else : ?>
      <p class="level-route-description">Follow the learning route module by module.</p>
    <?php endif; ?>

    <?php if (!empty($level_links)) : ?>
      <nav class="starter-level-nav" aria-label="Learning levels">
        <?php foreach ($level_links as $level_link) : ?>
          <?php $is_active_level = $is_level_term && $level_link['slug'] === $current_term->slug; ?>
          <a
            class="starter-level-link<?php echo $is_active_level ? ' is-active' : ''; ?>"
            href="<?php echo esc_url($level_link['url']); ?>"
            <?php echo $is_active_level ? 'aria-current="page"' : ''; ?>
          >
            <?php echo esc_html($level_link['label']); ?>
          </a>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>
  </section>

  <section class="starter-modules" aria-labelledby="level-modules-title">
    <div class="starter-section-heading">
      <div>
        <p class="level-route-eyebrow"><?php single_term_title(); ?> curriculum</p>
        <h2 id="level-modules-title">Modules and lessons</h2>
      </div>
      <?php if ($level_lesson_total > 0) : ?>
        <span class="level-route-count"><?php echo esc_html($level_lesson_total); ?> lessons</span>
      <?php endif; ?>
    </div>

    <?php if (!empty($level_modules)) : ?>
      <div class="starter-module-list">
        <?php foreach ($level_modules as $level_module) : ?>
          <section class="starter-module">
            <header class="starter-module-heading">
              <p class="starter-module-kicker">Module <?php echo esc_html($level_module['number'] ?? ''); ?></p>
              <h3><?php echo esc_html($level_module['label']); ?></h3>
            </header>

            <div class="starter-lesson-list">
              <?php foreach ($level_module['items'] as $level_lesson) : ?>
                <div class="starter-lesson-entry">
                  <?php if (!empty($level_lesson['url'])) : ?>
                    <a class="starter-lesson-row" href="<?php echo esc_url($level_lesson['url']); ?>">
                  <?php else : ?>
                    <div class="starter-lesson-row">
                  <?php endif; ?>
                    <span class="starter-lesson-order"><?php echo esc_html($level_lesson['order']); ?></span>
                    <span class="starter-lesson-copy">
                      <strong><?php echo esc_html($level_lesson['title']); ?></strong>
                      <?php if (!empty($level_lesson['type'])) : ?>
                        <span><?php echo esc_html($level_lesson['type']); ?></span>
                      <?php elseif ($level_lesson['excerpt'] !== '') : ?>
                        <span><?php echo esc_html($level_lesson['excerpt']); ?></span>
                      <?php endif; ?>
                    </span>
                    <?php if (!empty($level_lesson['url'])) : ?><span class="starter-lesson-arrow" aria-hidden="true">&rarr;</span><?php endif; ?>
                  <?php if (!empty($level_lesson['url'])) : ?></a><?php else : ?></div><?php endif; ?>
                  <?php if (!empty($level_lesson['associated'])) : ?>
                    <ul class="starter-lesson-associated" style="margin: 0 0 12px 50px; padding: 0 0 0 20px;">
                      <?php foreach ($level_lesson['associated'] as $activity) : ?>
                        <li><?php echo esc_html($activity['title']); ?> <span>[<?php echo esc_html($activity['type']); ?>]</span></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($level_lesson_total === 0) : ?>
      <div class="panel starter-empty-state">
        <h3>No published roadmap lessons yet</h3>
        <p>There are no published grammar lessons with complete roadmap metadata for this level.</p>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
