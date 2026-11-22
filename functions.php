<?php
if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/lesson-meta.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/taxonomy-seeds.php';
require_once get_template_directory() . '/inc/topic-catalog.php';
require_once get_template_directory() . '/inc/grammar-meta.php';
require_once get_template_directory() . '/inc/grammar-editor-template.php';

if (!function_exists('spanishnova_get_card_excerpt')) {
    function spanishnova_get_card_excerpt($post_id = null) {
        $post_id = $post_id ?: get_the_ID();
        $content = get_post_field('post_content', $post_id);

        if ($content && preg_match('/<p[^>]*class=["\'][^"\']*\bsn-intro\b[^"\']*["\'][^>]*>(.*?)<\/p>/is', $content, $matches)) {
            return wp_strip_all_tags($matches[1]);
        }

        $excerpt = get_the_excerpt($post_id);

        if (!$excerpt) {
            $excerpt = wp_trim_words(wp_strip_all_tags($content), 18);
        }

        return $excerpt;
    }
}

/**
 * Redirect legacy Explore type filters to canonical CPT archives.
 * Keep topic filters and leave /explore/ as the cross-type search.
 */
add_action('template_redirect', function () {
    if (!is_page('explore') || !isset($_GET['type']) || is_admin()) {
        return;
    }
    $type = sanitize_key(wp_unslash($_GET['type']));
    if (!in_array($type, ['vocabulary', 'readings', 'conversations'], true)) {
        return;
    }
    $url = get_post_type_archive_link($type);
    if (!$url) {
        return;
    }
    if (isset($_GET['topic'])) {
        $topic = sanitize_title(wp_unslash($_GET['topic']));
        if ($topic) {
            $url = add_query_arg('topic', $topic, $url);
        }
    }
    wp_safe_redirect($url, 301);
    exit;
});

/** Filter selected topic within the vocabulary, readings and conversations archives. */
function spanishnova_filter_topic_archive($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive()) return;
    $post_type = $query->get('post_type');
    if (!in_array($post_type, ['vocabulary', 'readings', 'conversations'], true)) return;
    $raw_topic = $_GET['sn_topic'] ?? ($_GET['topic'] ?? null);
    if (!is_string($raw_topic)) return;
    $slug = sanitize_title(wp_unslash($raw_topic));
    if (!$slug || !term_exists($slug, 'topic_tax')) return;
    $filters = (array) $query->get('tax_query');
    $filters[] = [
        'taxonomy' => 'topic_tax',
        'field' => 'slug',
        'terms' => $slug,
        'include_children' => true,
    ];
    $query->set('tax_query', $filters);
}
add_action('pre_get_posts', 'spanishnova_filter_topic_archive');
