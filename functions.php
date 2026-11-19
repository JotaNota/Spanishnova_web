<?php
if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/lesson-meta.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/taxonomy-seeds.php';


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
 * Filter topical CPT archives from header menu without entering taxonomy
 * archives that combine different post types.
 */
function spanishnova_filter_topic_archive($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive()) return;

    $post_type = $query->get('post_type');
    if (!in_array($post_type, ['vocabulary', 'readings', 'conversations'], true)) return;
    if (!isset($_GET['sn_topic']) || !is_string($_GET['sn_topic'])) return;

    $slug = sanitize_title(wp_unslash($_GET['sn_topic']));
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
