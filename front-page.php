<?php
/**
 * SpanishNova homepage. Featured special: tag one published item with home-special.
 * Without a special, the banner cycles through recent posts (up to five).
 */
get_header();

$sn_home_types = ['grammar','vocabulary','readings','conversations','practice','resources'];
$sn_special_query = new WP_Query([
  'post_type' => $sn_home_types,
  'post_status' => 'publish',
  'posts_per_page' => 1,
  'ignore_sticky_posts' => true,
  'tag' => 'home-special',
  'orderby' => 'modified',
  'order' => 'DESC',
]);
$sn_has_special = $sn_special_query->have_posts();
$sn_featured_posts = [];
if ($sn_has_special) {
  while ($sn_special_query->have_posts()) {
    $sn_special_query->the_post();
    $sn_featured_posts[] = get_post();
  }
}
wp_reset_postdata();

if (!$sn_featured_posts) {
  // Prefer posts with WordPress featured images for the carousel.
  $sn_featured_query = new WP_Query([
    'post_type' => $sn_home_types,
    'post_status' => 'publish',
    'posts_per_page' => 5,
    'ignore_sticky_posts' => true,
    'meta_query' => [['key' => '_thumbnail_id', 'compare' => 'EXISTS']],
    'orderby' => 'date',
    'order' => 'DESC',
  ]);
  if ($sn_featured_query->have_posts()) {
    while ($sn_featured_query->have_posts()) {
      $sn_featured_query->the_post();
      $sn_featured_posts[] = get_post();
    }
  }
  wp_reset_postdata();
}
if (!$sn_featured_posts) {
  $sn_fallback_query = new WP_Query([
    'post_type' => $sn_home_types,
    'post_status' => 'publish',
    'posts_per_page' => 5,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
  ]);
  if ($sn_fallback_query->have_posts()) {
    while ($sn_fallback_query->have_posts()) {
      $sn_fallback_query->the_post();
      $sn_featured_posts[] = get_post();
    }
  }
  wp_reset_postdata();
}

$sn_levels = [
  ['A1','Starter','starter'],
  ['A2','Beginner','beginner'],
  ['B1','Intermediate','intermediate'],
  ['B2','Upper-intermediate','upper-intermediate'],
  ['C1','Advanced','advanced'],
];
$sn_skills = [
  ['Grammar','Structure','Verbs, tenses and sentence structures','Preterite, subjunctive, ser vs estar','grammar'],
  ['Vocabulary','Words','Words for everyday situations','Travel, food, the weather','vocabulary'],
  ['Readings','Reading','Texts and reading comprehension','Short stories, news, dialogues','readings'],
  ['Conversations','Speaking','Learn to communicate in context','At a restaurant, meeting people','conversations'],
  ['Practice','Exercises',"Exercises to apply what you've learned",'Quizzes, matching, fill in the blanks','practice'],
  ['Resources','Teachers','Worksheets and teaching materials','Printable PDFs, lesson plans','resources'],
];
?>
<main class="sn-home">
  <section class="sn-home-feature" aria-label="Featured Spanish lessons" data-sn-carousel>
    <?php if ($sn_featured_posts) : ?>
      <?php foreach ($sn_featured_posts as $index => $sn_post) : ?>
        <?php
          $sn_id = $sn_post->ID;
          $sn_text = trim((string) get_the_excerpt($sn_id));
          if (!$sn_text) {
            $sn_text = 'Explore this SpanishNova lesson and practice your Spanish.';
          }
          $sn_text = wp_trim_words(wp_strip_all_tags($sn_text), 19, '…');
          $sn_image = get_the_post_thumbnail($sn_id, 'large', [
            'loading' => $index === 0 ? 'eager' : 'lazy',
            'alt' => '',
          ]);
        ?>
        <article class="sn-home-feature-slide" data-sn-slide <?php echo $index ? 'hidden' : ''; ?>>
          <div class="sn-home-feature-copy">
            <p class="sn-home-kicker"><?php echo $sn_has_special ? 'Featured special' : 'Featured lesson'; ?></p>
            <?php if ($index === 0) : ?>
              <h1><?php echo esc_html(get_the_title($sn_id)); ?></h1>
            <?php else : ?>
              <h2><?php echo esc_html(get_the_title($sn_id)); ?></h2>
            <?php endif; ?>
            <p class="sn-home-feature-desc"><?php echo esc_html($sn_text); ?></p>
            <a class="sn-home-feature-link" href="<?php echo esc_url(get_permalink($sn_id)); ?>">Explore <?php echo $sn_has_special ? 'special' : 'lesson'; ?> <span aria-hidden="true">→</span></a>
          </div>
          <div class="sn-home-feature-media<?php echo $sn_image ? '' : ' sn-home-feature-media--empty'; ?>">
            <?php echo $sn_image ? wp_kses_post($sn_image) : ''; ?>
          </div>
        </article>
      <?php endforeach; ?>
      <?php if (!$sn_has_special && count($sn_featured_posts) > 1) : ?>
        <div class="sn-home-feature-controls" aria-label="Featured lesson carousel controls">
          <button type="button" data-sn-prev aria-label="Previous featured lesson">‹</button>
          <span class="sn-home-feature-count" data-sn-count aria-live="off">1 / <?php echo esc_html(count($sn_featured_posts)); ?></span>
          <button type="button" data-sn-next aria-label="Next featured lesson">›</button>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <div class="sn-home-feature-slide">
        <div class="sn-home-feature-copy">
          <p class="sn-home-kicker">SpanishNova</p>
          <h1>Explore Spanish at your own pace.</h1>
          <p class="sn-home-feature-desc">Browse grammar, vocabulary, readings and practice materials.</p>
          <a class="sn-home-feature-link" href="<?php echo esc_url(home_url('/grammar/')); ?>">Explore grammar →</a>
        </div>
        <div class="sn-home-feature-media sn-home-feature-media--empty"></div>
      </div>
    <?php endif; ?>
  </section>

  <section class="sn-home-section" aria-labelledby="sn-level-heading">
    <h2 id="sn-level-heading">Learn by level</h2>
    <p class="sn-home-section-intro">Browse available lessons by level.</p>
    <div class="sn-home-levels">
      <?php foreach ($sn_levels as $sn_level) : ?>
        <?php
          [$sn_code,$sn_name,$sn_slug] = $sn_level;
          $sn_term = get_term_by('slug', $sn_slug, 'level_tax');
          $sn_url = $sn_term && !is_wp_error($sn_term) ? get_term_link($sn_term) : null;
          $sn_level_available = false;
          if ($sn_url && !is_wp_error($sn_url)) {
            // Level routes currently display roadmap-tagged grammar posts only.
            $sn_check = new WP_Query([
              'post_type' => 'grammar',
              'post_status' => 'publish',
              'posts_per_page' => 1,
              'fields' => 'ids',
              'tax_query' => [['taxonomy' => 'level_tax','field' => 'term_id','terms' => $sn_term->term_id]],
              'meta_query' => [['key'=>'level_roadmap_source','value'=>'levels-roadmap-current']],
            ]);
            $sn_level_available = $sn_check->have_posts();
            wp_reset_postdata();
          }
        ?>
        <?php if ($sn_level_available) : ?>
          <a class="sn-home-level" href="<?php echo esc_url($sn_url); ?>">
            <span class="sn-home-level-code"><?php echo esc_html($sn_code); ?></span>
            <span class="sn-home-level-name"><?php echo esc_html($sn_name); ?></span>
            <span class="sn-home-level-state">Browse lessons →</span>
          </a>
        <?php else : ?>
          <div class="sn-home-level sn-home-level--unavailable" aria-label="<?php echo esc_attr($sn_code . ' ' . $sn_name . ', coming soon'); ?>">
            <span class="sn-home-level-code"><?php echo esc_html($sn_code); ?></span>
            <span class="sn-home-level-name"><?php echo esc_html($sn_name); ?></span>
            <span class="sn-home-level-state">Coming soon</span>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sn-home-section" aria-labelledby="sn-skills-heading">
    <h2 id="sn-skills-heading">Or go straight to a skill</h2>
    <p class="sn-home-section-intro">Looking for something specific? Choose a type of content.</p>
    <div class="sn-home-skill-grid">
      <?php foreach ($sn_skills as $sn_skill) : ?>
        <?php [$sn_title,$sn_tag,$sn_desc,$sn_example,$sn_type] = $sn_skill; ?>
        <a class="sn-home-skill" href="<?php echo esc_url(get_post_type_archive_link($sn_type) ?: home_url('/' . $sn_type . '/')); ?>">
          <span class="sn-home-skill-tag"><?php echo esc_html($sn_tag); ?></span>
          <h3><?php echo esc_html($sn_title); ?></h3>
          <p><?php echo esc_html($sn_desc); ?></p>
          <span class="sn-home-skill-example">e.g. <?php echo esc_html($sn_example); ?></span>
          <span class="sn-home-skill-go">Explore →</span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sn-home-class-cta" aria-label="Spanish classes">
    <div>
      <p class="sn-home-kicker">Spanish classes</p>
      <h2>Ready to speak Spanish?</h2>
      <p>Practice with a Spanish teacher.</p>
    </div>
    <a href="<?php echo esc_url(home_url('/classes/')); ?>">Explore classes →</a>
  </section>
</main>
<script>
(function () {
  var carousel = document.querySelector('[data-sn-carousel]');
  if (!carousel) return;
  var slides = Array.prototype.slice.call(carousel.querySelectorAll('[data-sn-slide]'));
  var previous = carousel.querySelector('[data-sn-prev]');
  var next = carousel.querySelector('[data-sn-next]');
  var counter = carousel.querySelector('[data-sn-count]');
  if (!previous || !next || slides.length < 2) return;
  var active = 0;
  var timer;
  function show(index) {
    active = (index + slides.length) % slides.length;
    slides.forEach(function (slide, i) { slide.hidden = i !== active; });
    counter.textContent = (active + 1) + ' / ' + slides.length;
  }
  function pause() { if (timer) { clearInterval(timer); timer = null; } }
  function resume() {
    pause();
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      timer = setInterval(function () { show(active + 1); }, 7000);
    }
  }
  previous.addEventListener('click', function () { show(active - 1); resume(); });
  next.addEventListener('click', function () { show(active + 1); resume(); });
  carousel.addEventListener('mouseenter', pause);
  carousel.addEventListener('mouseleave', resume);
  carousel.addEventListener('focusin', pause);
  carousel.addEventListener('focusout', function (event) {
    if (!carousel.contains(event.relatedTarget)) resume();
  });
  document.addEventListener('visibilitychange', function () {
    document.hidden ? pause() : resume();
  });
  resume();
})();
</script>
<?php get_footer(); ?>
