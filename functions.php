<?php
if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/cpt.php';
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

