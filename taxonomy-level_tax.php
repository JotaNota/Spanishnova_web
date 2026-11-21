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
$level_lesson_published = 0;

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
  $posts_by_title = [];
  foreach ($published_posts as $published_post) {
    $published_type = get_post_type($published_post);
    $posts_by_slug[$published_type . ':' . $published_post->post_name] = $published_post;
    $title_key = $published_type . ':' . sanitize_title(get_the_title($published_post));
    if (!isset($posts_by_title[$title_key])) {
      $posts_by_title[$title_key] = [$published_post];
    } else {
      $posts_by_title[$title_key][] = $published_post;
    }
  }

  // The curriculum uses "Conversation"; its registered CPT is "conversations".
  $resolve_route_post = static function ($entry) use ($posts_by_slug, $posts_by_title) {
    $type_map = [
      'grammar' => 'grammar',
      'vocabulary' => 'vocabulary',
      'practice' => 'practice',
      'reading' => 'readings',
      'readings' => 'readings',
      'conversation' => 'conversations',
      'conversations' => 'conversations',
      'resources' => 'resources',
    ];
    $post_type = $type_map[strtolower(trim($entry['type'] ?? ''))] ?? '';
    if (!$post_type) {
      return null;
    }
    $slug = trim((string) ($entry['slug'] ?? ''));
    if ($slug !== '') {
      return $posts_by_slug[$post_type . ':' . $slug] ?? null;
    }
    // For curriculum entries without an explicit slug, use the WordPress
    // slug generated from the displayed lesson title (e.g. Basic verbs -> basic-verbs).
    // An exact title match remains a fallback. Never link to an unrelated post.
    $generated_slug = sanitize_title($entry['title']);
    $slug_post = $posts_by_slug[$post_type . ':' . $generated_slug] ?? null;
    if ($slug_post) {
      return $slug_post;
    }
    $matches = $posts_by_title[$post_type . ':' . $generated_slug] ?? [];
    return count($matches) === 1 ? $matches[0] : null;
  };

  foreach ($curricula[$level_slug] as $module) {
    $units = [];
    $module_published = 0;
    $module_total = 0;
    foreach ($module['units'] as $index => $unit) {
      $lessons = [];
      $unit_published = 0;
      foreach ($unit['lessons'] as $entry) {
        $post = $resolve_route_post($entry);
        $lessons[] = [
          'title' => $entry['title'],
          'type' => $entry['type'],
          'url' => $post ? get_permalink($post) : '',
        ];
        $module_total++;
        $level_lesson_total++;
        if ($post) {
          $unit_published++;
          $module_published++;
          $level_lesson_published++;
        }
      }
      $units[] = [
        'title' => $unit['title'],
        'number' => $index + 1,
        'lessons' => $lessons,
        'total' => count($lessons),
        'published' => $unit_published,
      ];
    }
    $level_modules[] = [
      'label' => $module['label'],
      'number' => $module['number'],
      'units' => $units,
      'total' => $module_total,
      'published' => $module_published,
    ];
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
    $level_lesson_published++;
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
        <span class="level-route-count"><?php echo esc_html($level_lesson_published); ?> of <?php echo esc_html($level_lesson_total); ?> available</span>
      <?php endif; ?>
    </div>

    <?php if (!empty($level_modules)) : ?>
      <div class="starter-module-list">
        <?php foreach ($level_modules as $level_module) : ?>
          <section class="starter-module">
            <header class="starter-module-heading">
              <div class="sn-module-heading-copy">
                <p class="starter-module-kicker">Module <?php echo esc_html($level_module['number'] ?? ''); ?> · <?php single_term_title(); ?></p>
                <h3><?php echo esc_html($level_module['label']); ?></h3>
                <?php if (isset($level_module['units'])) : ?>
                  <p class="sn-module-meta"><?php echo esc_html(count($level_module['units'])); ?> units · <?php echo esc_html($level_module['total']); ?> lessons</p>
                  <div class="sn-module-progress" role="progressbar" aria-label="Published lessons" aria-valuemin="0" aria-valuemax="<?php echo esc_attr($level_module['total']); ?>" aria-valuenow="<?php echo esc_attr($level_module['published']); ?>"><span style="width:<?php echo esc_attr($level_module['total'] ? round(100 * $level_module['published'] / $level_module['total'], 2) : 0); ?>%"></span></div>
                <?php endif; ?>
              </div>
              <?php if (isset($level_module['total'])) : ?>
                <span class="starter-module-progress"><?php echo esc_html($level_module['published']); ?> of <?php echo esc_html($level_module['total']); ?> available</span>
              <?php endif; ?>
            </header>
            <?php if (isset($level_module['units'])) : ?>
              <div class="sn-unit-list">
                <?php foreach ($level_module['units'] as $unit) : ?>
                  <details class="sn-unit" open>
                    <summary class="sn-unit-summary">
                      <span class="sn-unit-number"><?php echo esc_html(str_pad((string)$unit['number'], 2, '0', STR_PAD_LEFT)); ?></span>
                      <span class="sn-unit-info"><strong><?php echo esc_html($unit['title']); ?></strong><span><?php echo esc_html($unit['total']); ?> lessons · <?php echo esc_html($unit['published']); ?> available</span></span>
                      <span class="sn-unit-chevron" aria-hidden="true"></span>
                    </summary>
                    <div class="sn-unit-lessons">
                      <?php foreach ($unit['lessons'] as $lesson) : ?>
                        <?php $available = !empty($lesson['url']); ?>
                        <?php if ($available) : ?><a class="sn-unit-lesson is-available" href="<?php echo esc_url($lesson['url']); ?>">
                        <?php else : ?><div class="sn-unit-lesson is-pending"><?php endif; ?>
                          <span class="sn-lesson-icon" aria-hidden="true"><?php echo $available ? '&#9635;' : '&#9675;'; ?></span>
                          <span class="sn-lesson-info"><strong><?php echo esc_html($lesson['title']); ?></strong><small><?php echo esc_html($lesson['type']); ?></small></span>
                          <span class="sn-lesson-status"><?php echo $available ? 'Available' : 'Coming soon'; ?></span>
                          <?php if ($available) : ?><span class="sn-lesson-arrow" aria-hidden="true">&#8599;</span><?php endif; ?>
                        <?php if ($available) : ?></a><?php else : ?></div><?php endif; ?>
                      <?php endforeach; ?>
                    </div>
                  </details>
                <?php endforeach; ?>
              </div>
            <?php else : ?>
              <div class="starter-lesson-list">
                <?php foreach ($level_module['items'] as $level_lesson) : ?>
                  <a class="starter-lesson-row" href="<?php echo esc_url($level_lesson['url']); ?>">
                    <span class="starter-lesson-order"><?php echo esc_html($level_lesson['order']); ?></span>
                    <span class="starter-lesson-copy"><strong><?php echo esc_html($level_lesson['title']); ?></strong></span>
                    <span class="starter-lesson-arrow" aria-hidden="true">&#8594;</span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
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
