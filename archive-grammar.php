<?php get_header(); ?>

<?php
$search = isset($_GET['sn_search']) ? sanitize_text_field(wp_unslash($_GET['sn_search'])) : '';
$category_slug = isset($_GET['sn_category']) ? sanitize_title(wp_unslash($_GET['sn_category'])) : '';
$subtopic_slug = isset($_GET['sn_subtopic']) ? sanitize_title(wp_unslash($_GET['sn_subtopic'])) : '';
$level_slug = isset($_GET['sn_level']) ? sanitize_title(wp_unslash($_GET['sn_level'])) : '';
$paged = isset($_GET['sn_page']) ? max(1, absint($_GET['sn_page'])) : 1;

// Only offer grammar terms that have at least one published Grammar post
// (including posts assigned to descendants).
function spanishnova_grammar_term_has_lessons($term_id) {
  $matches = new WP_Query([
    'post_type' => 'grammar',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'fields' => 'ids',
    'no_found_rows' => true,
    'tax_query' => [[
      'taxonomy' => 'grammar_tax',
      'field' => 'term_id',
      'terms' => (int) $term_id,
      'include_children' => true,
    ]],
  ]);
  return $matches->have_posts();
}

$all_categories = get_terms([
  'taxonomy' => 'grammar_tax',
  'parent' => 0,
  'hide_empty' => false,
]);
if (is_wp_error($all_categories)) {
  $all_categories = [];
}
$categories = [];
$category_tree = [];
foreach ($all_categories as $category) {
  if (!spanishnova_grammar_term_has_lessons($category->term_id)) continue;
  $categories[] = $category;
  $descendants = get_terms([
    'taxonomy' => 'grammar_tax',
    'child_of' => $category->term_id,
    'hide_empty' => false,
  ]);
  if (is_wp_error($descendants)) $descendants = [];
  $visible_descendants = array_values(array_filter($descendants, function ($term) {
    return spanishnova_grammar_term_has_lessons($term->term_id);
  }));
  $category_tree[$category->slug] = [
    'label' => $category->name,
    'children' => array_map(function ($term) {
      return ['slug' => $term->slug, 'label' => $term->name];
    }, $visible_descendants),
  ];
}
$levels = get_terms([
  'taxonomy' => 'level_tax',
  'hide_empty' => false,
]);
if (is_wp_error($levels)) {
  $levels = [];
}

$selected_category = $category_slug ? get_term_by('slug', $category_slug, 'grammar_tax') : null;
if ($selected_category && (int) $selected_category->parent !== 0) {
  $selected_category = null;
}
if (!$selected_category) {
  $category_slug = '';
}
$selected_subtopic = $subtopic_slug ? get_term_by('slug', $subtopic_slug, 'grammar_tax') : null;
if ($selected_subtopic && $selected_category) {
  $ancestor_ids = get_ancestors($selected_subtopic->term_id, 'grammar_tax', 'taxonomy');
  if (!in_array((int) $selected_category->term_id, array_map('intval', $ancestor_ids), true)) {
    $selected_subtopic = null;
  }
} else {
  $selected_subtopic = null;
}
if (!$selected_subtopic) {
  $subtopic_slug = '';
}
$subtopics = [];
if ($selected_category && isset($category_tree[$selected_category->slug])) {
  $subtopics = $category_tree[$selected_category->slug]['children'];
}
$valid_level = $level_slug ? get_term_by('slug', $level_slug, 'level_tax') : null;
if (!$valid_level) {
  $level_slug = '';
}

$query_args = [
  'post_type' => 'grammar',
  'post_status' => 'publish',
  'posts_per_page' => 12,
  'paged' => $paged,
  'ignore_sticky_posts' => true,
  'orderby' => 'title',
  'order' => 'ASC',
];
if ($search !== '') {
  $query_args['s'] = $search;
}
$tax_query = ['relation' => 'AND'];
if ($selected_category) {
  $tax_query[] = [
    'taxonomy' => 'grammar_tax',
    'field' => 'term_id',
    'terms' => $selected_subtopic ? $selected_subtopic->term_id : $selected_category->term_id,
    'include_children' => true,
  ];
}
if ($valid_level) {
  $tax_query[] = [
    'taxonomy' => 'level_tax',
    'field' => 'term_id',
    'terms' => $valid_level->term_id,
  ];
}
if (count($tax_query) > 1) {
  $query_args['tax_query'] = $tax_query;
}
$grammar_query = new WP_Query($query_args);
$base_url = get_post_type_archive_link('grammar') ?: home_url('/grammar/');
$active_args = [];
if ($search !== '') $active_args['sn_search'] = $search;
if ($category_slug !== '') $active_args['sn_category'] = $category_slug;
if ($subtopic_slug !== '') $active_args['sn_subtopic'] = $subtopic_slug;
if ($level_slug !== '') $active_args['sn_level'] = $level_slug;
?>

<main>
  <section class="panel grammar-archive-panel grammar-explore-panel">
    <h1>Grammar</h1>
    <p class="grammar-catalog-intro">Find grammar lessons by keyword, topic or level.</p>

    <form id="grammar-catalog-filters" class="grammar-catalog-filters" action="<?php echo esc_url($base_url); ?>" method="get" role="search">
      <div class="grammar-filter-field grammar-search-field">
        <label for="grammar-search">Search lessons</label>
        <input id="grammar-search" type="search" name="sn_search" value="<?php echo esc_attr($search); ?>" placeholder="Search grammar lessons..." autocomplete="off">
      </div>
      <div class="grammar-filter-field">
        <label for="grammar-category">Topic</label>
        <select id="grammar-category" name="sn_category">
          <option value="">All topics</option>
          <?php foreach ($categories as $category) : ?>
            <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($category_slug, $category->slug); ?>><?php echo esc_html($category->name); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="grammar-filter-field">
        <label for="grammar-level">Level</label>
        <select id="grammar-level" name="sn_level">
          <option value="">All levels</option>
          <?php foreach ($levels as $level) : ?>
            <option value="<?php echo esc_attr($level->slug); ?>" <?php selected($level_slug, $level->slug); ?>><?php echo esc_html($level->name); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="grammar-filter-field grammar-subtopic-field" <?php echo $selected_category ? '' : 'hidden'; ?>>
        <label for="grammar-subtopic">Subtopic</label>
        <select id="grammar-subtopic" name="sn_subtopic" data-current="<?php echo esc_attr($subtopic_slug); ?>">
          <option value="">All <?php echo $selected_category ? esc_html($selected_category->name) : 'subtopics'; ?></option>
          <?php foreach ($subtopics as $subtopic) : ?>
            <option value="<?php echo esc_attr($subtopic['slug']); ?>" <?php selected($subtopic_slug, $subtopic['slug']); ?>><?php echo esc_html($subtopic['label']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="grammar-filter-actions">
        <button type="button" id="grammar-reset" class="grammar-reset-button">Reset</button>
        <button type="submit" class="grammar-fallback-submit">Apply filters</button>
      </div>
    </form>

    <div id="grammar-catalog-results" aria-live="polite" aria-busy="false">
      <div class="grammar-results-heading">
        <h2>Grammar lessons</h2>
        <p><?php echo esc_html($grammar_query->found_posts); ?> results</p>
      </div>

      <?php if ($grammar_query->have_posts()) : ?>
        <div class="activity-list grammar-term-lessons">
          <?php while ($grammar_query->have_posts()) : $grammar_query->the_post(); ?>
            <?php
            $level_terms = get_the_terms(get_the_ID(), 'level_tax');
            $level_label = ($level_terms && !is_wp_error($level_terms))
              ? implode(', ', wp_list_pluck($level_terms, 'name'))
              : '';
            $grammar_terms = get_the_terms(get_the_ID(), 'grammar_tax');
            $grammar_topic = '';
            if ($grammar_terms && !is_wp_error($grammar_terms)) {
              usort($grammar_terms, function ($a, $b) {
                return (int) $b->parent <=> (int) $a->parent;
              });
              $grammar_topic = $grammar_terms[0]->name;
            }
            ?>
            <a class="activity-row sn-catalog-card sn-catalog-grammar" href="<?php the_permalink(); ?>">
              <div class="sn-grammar-card-content">
                <div class="sn-catalog-card-meta">
                  <span>Grammar</span>
                  <?php if ($level_label !== '') : ?>
                    <span aria-hidden="true">·</span>
                    <span><?php echo esc_html($level_label); ?></span>
                  <?php endif; ?>
                </div>
                <h3><?php the_title(); ?></h3>
                <?php if ($grammar_topic) : ?>
                  <p><?php echo esc_html($grammar_topic); ?></p>
                <?php endif; ?>
              </div>
              <span class="arrow" aria-hidden="true">→</span>
            </a>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="empty-state">No grammar lessons match these filters.</p>
      <?php endif; ?>

      <?php if ($grammar_query->max_num_pages > 1) : ?>
        <nav class="grammar-catalog-pagination" aria-label="Grammar pagination">
          <?php for ($page_number = 1; $page_number <= $grammar_query->max_num_pages; $page_number++) :
            $page_url = add_query_arg(array_merge($active_args, ['sn_page' => $page_number]), $base_url);
          ?>
            <a href="<?php echo esc_url($page_url); ?>" <?php echo $paged === $page_number ? 'aria-current="page"' : ''; ?>><?php echo esc_html($page_number); ?></a>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>
    </div>
  </section>
</main>

<script>
(function () {
  var form = document.getElementById('grammar-catalog-filters');
  var results = document.getElementById('grammar-catalog-results');
  if (!form || !results || !window.fetch || !window.DOMParser) return;
  form.classList.add('js-ready');

  var search = document.getElementById('grammar-search');
  var category = document.getElementById('grammar-category');
  var subtopic = document.getElementById('grammar-subtopic');
  var subtopicField = form.querySelector('.grammar-subtopic-field');
  var level = document.getElementById('grammar-level');
  var reset = document.getElementById('grammar-reset');
  var timer;
  var requestId = 0;
  var categoryTree = <?php echo wp_json_encode($category_tree); ?>;

  function updateSubtopics(value) {
    var entry = categoryTree[value];
    var current = subtopic.value;
    subtopic.replaceChildren();
    var all = document.createElement('option');
    all.value = '';
    all.textContent = entry ? 'All ' + entry.label : 'All subtopics';
    subtopic.appendChild(all);
    if (entry && entry.children.length) {
      entry.children.forEach(function (child) {
        var option = document.createElement('option');
        option.value = child.slug;
        option.textContent = child.label;
        subtopic.appendChild(option);
      });
      subtopic.value = current;
      if (subtopic.selectedIndex === -1) subtopic.value = '';
      subtopicField.hidden = false;
    } else {
      subtopic.value = '';
      subtopicField.hidden = true;
    }
  }

  function buildUrl(page) {
    var url = new URL(form.action, window.location.href);
    var params = url.searchParams;
    if (search.value.trim()) params.set('sn_search', search.value.trim());
    if (category.value) params.set('sn_category', category.value);
    if (category.value && subtopic.value) params.set('sn_subtopic', subtopic.value);
    if (level.value) params.set('sn_level', level.value);
    if (page > 1) params.set('sn_page', String(page));
    return url;
  }

  function load(url) {
    var id = ++requestId;
    results.setAttribute('aria-busy', 'true');
    fetch(url.toString(), {credentials:'same-origin'})
      .then(function (response) {
        if (!response.ok) throw new Error('Catalog request failed');
        return response.text();
      })
      .then(function (html) {
        if (id !== requestId) return;
        var documentCopy = new DOMParser().parseFromString(html, 'text/html');
        var next = documentCopy.getElementById('grammar-catalog-results');
        if (!next) throw new Error('Catalog results missing');
        results.innerHTML = next.innerHTML;
        window.history.replaceState(null, '', url.toString());
        results.setAttribute('aria-busy', 'false');
      })
      .catch(function () {
        if (id !== requestId) return;
        results.setAttribute('aria-busy', 'false');
        results.innerHTML = '<p class="empty-state">Could not load lessons. Please try again.</p>';
      });
  }

  function refresh() { load(buildUrl(1)); }
  search.addEventListener('input', function () {
    clearTimeout(timer);
    timer = setTimeout(refresh, 250);
  });
  category.addEventListener('change', function () {
    subtopic.value = '';
    updateSubtopics(category.value);
    refresh();
  });
  subtopic.addEventListener('change', refresh);
  level.addEventListener('change', refresh);
  reset.addEventListener('click', function () {
    clearTimeout(timer);
    form.reset();
    search.value = '';
    category.value = '';
    subtopic.value = '';
    level.value = '';
    updateSubtopics('');
    refresh();
  });
  form.addEventListener('submit', function (event) {
    event.preventDefault();
    clearTimeout(timer);
    refresh();
  });
  results.addEventListener('click', function (event) {
    var link = event.target.closest('.grammar-catalog-pagination a');
    if (!link) return;
    event.preventDefault();
    clearTimeout(timer);
    var url = new URL(link.href, window.location.href);
    var page = Number(url.searchParams.get('sn_page') || 1);
    load(buildUrl(page));
  });
})();
</script>
<?php get_footer(); ?>
