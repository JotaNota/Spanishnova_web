<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_enqueue_assets() {
    wp_enqueue_style('spanishnova-main', get_template_directory_uri() . '/assets/css/main.css', [], '0.1.0');
    wp_enqueue_style('spanishnova-header', get_template_directory_uri() . '/assets/css/header.css', ['spanishnova-main'], '0.1.0');
    wp_enqueue_style('spanishnova-footer', get_template_directory_uri() . '/assets/css/footer.css', ['spanishnova-main'], '0.1.0');

    if (is_front_page() || is_home()) {
        wp_enqueue_style('spanishnova-home', get_template_directory_uri() . '/assets/css/home.css', ['spanishnova-main'], '0.1.0');
    }

    if (is_singular(['post', 'grammar', 'vocabulary', 'readings', 'conversations'])) {
        wp_enqueue_style('spanishnova-singles', get_template_directory_uri() . '/assets/css/singles.css', ['spanishnova-main'], '0.1.0');
    }

    if (is_archive() || is_tax() || is_search()) {
        wp_enqueue_style('spanishnova-archives', get_template_directory_uri() . '/assets/css/archives.css', ['spanishnova-main'], '0.1.0');
    }

    if (is_tax('level_tax')) {
        wp_enqueue_style('spanishnova-level-route', get_template_directory_uri() . '/assets/css/level-route.css', ['spanishnova-archives'], '0.1.0');
    }

    wp_enqueue_script('spanishnova-main', get_template_directory_uri() . '/assets/js/main.js', [], '0.1.0', true);
}
add_action('wp_enqueue_scripts', 'spanishnova_enqueue_assets');
