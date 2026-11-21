<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_enqueue_assets() {
    wp_enqueue_style('spanishnova-main', get_template_directory_uri() . '/assets/css/main.css', [], '0.1.0');
    wp_enqueue_style('spanishnova-header', get_template_directory_uri() . '/assets/css/header.css', ['spanishnova-main'], '0.1.0');
    wp_enqueue_style('spanishnova-footer', get_template_directory_uri() . '/assets/css/footer.css', ['spanishnova-main'], filemtime(get_template_directory() . '/assets/css/footer.css'));

    if (is_front_page() || is_home()) {
        wp_enqueue_style('spanishnova-home', get_template_directory_uri() . '/assets/css/home.css', ['spanishnova-main'], '0.1.0');
    }

    if (is_singular(['post', 'grammar', 'vocabulary', 'readings', 'conversations'])) {
        wp_enqueue_style('spanishnova-singles', get_template_directory_uri() . '/assets/css/singles.css', ['spanishnova-main'], '0.1.0');
    }

    // Grammar > Verbos model. Scoped to .sn-grammar-verb-lesson in post content.
    if (is_singular('grammar')) {
        wp_enqueue_style('spanishnova-grammar-verbs', get_template_directory_uri() . '/assets/css/grammar-verbs.css', ['spanishnova-singles'], filemtime(get_template_directory() . '/assets/css/grammar-verbs.css'));
        wp_enqueue_script('spanishnova-grammar-verbs', get_template_directory_uri() . '/assets/js/grammar-verbs.js', [], filemtime(get_template_directory() . '/assets/js/grammar-verbs.js'), true);
    }

    // Donations page styles, scoped to the existing section IDs in its content.
    if (is_page('donations')) {
        wp_enqueue_style('spanishnova-donations', get_template_directory_uri() . '/assets/css/donations.css', ['spanishnova-main'], filemtime(get_template_directory() . '/assets/css/donations.css'));
    }

    if (is_archive() || is_tax() || is_search()) {
        wp_enqueue_style('spanishnova-archives', get_template_directory_uri() . '/assets/css/archives.css', ['spanishnova-main'], filemtime(get_template_directory() . '/assets/css/archives.css'));
    }

    if (is_tax('level_tax')) {
        wp_enqueue_style('spanishnova-level-route', get_template_directory_uri() . '/assets/css/level-route.css', ['spanishnova-archives'], '0.1.0');
    }

    wp_enqueue_script('spanishnova-main', get_template_directory_uri() . '/assets/js/main.js', [], filemtime(get_template_directory() . '/assets/js/main.js'), true);
}
add_action('wp_enqueue_scripts', 'spanishnova_enqueue_assets');
