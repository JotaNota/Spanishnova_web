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
        wp_enqueue_style('spanishnova-singles', get_template_directory_uri() . '/assets/css/singles.css', ['spanishnova-main'], filemtime(get_template_directory() . '/assets/css/singles.css'));
    }

    if (is_singular('grammar')) {
        wp_enqueue_style('spanishnova-grammar-lesson', get_template_directory_uri() . '/assets/css/grammar-lesson.css', ['spanishnova-singles'], filemtime(get_template_directory() . '/assets/css/grammar-lesson.css'));
        wp_enqueue_script('spanishnova-grammar-lesson', get_template_directory_uri() . '/assets/js/grammar-lesson.js', [], filemtime(get_template_directory() . '/assets/js/grammar-lesson.js'), true);
    }

    if (is_singular('vocabulary')) {
        $vocabulary_css = get_template_directory() . '/assets/css/vocabulary-lesson.css';
        $vocabulary_js = get_template_directory() . '/assets/js/vocabulary-lesson.js';
        wp_enqueue_style('spanishnova-vocabulary-lesson', get_template_directory_uri() . '/assets/css/vocabulary-lesson.css', ['spanishnova-singles'], filemtime($vocabulary_css));
        wp_enqueue_script('spanishnova-vocabulary-lesson', get_template_directory_uri() . '/assets/js/vocabulary-lesson.js', [], filemtime($vocabulary_js), true);
    }

    // Load practice lesson components only for posts using their scoped wrapper.
    if (is_singular('practice')) {
        $practice_post = get_queried_object();
        if ($practice_post instanceof WP_Post && strpos($practice_post->post_content, 'sn-practice-lesson') !== false) {
            $practice_css = get_template_directory() . '/assets/css/practice.css';
            if (is_file($practice_css)) {
                wp_enqueue_style(
                    'spanishnova-practice-lesson',
                    get_template_directory_uri() . '/assets/css/practice.css',
                    ['spanishnova-main'],
                    filemtime($practice_css)
                );
            }
        }
    }

    if (is_archive() || is_tax() || is_search()) {
        wp_enqueue_style('spanishnova-archives', get_template_directory_uri() . '/assets/css/archives.css', ['spanishnova-main'], '0.1.0');
    }

    if (is_tax('level_tax')) {
        wp_enqueue_style('spanishnova-level-route', get_template_directory_uri() . '/assets/css/level-route.css', ['spanishnova-archives'], filemtime(get_template_directory() . '/assets/css/level-route.css'));
    }

    if (is_page(['classes', 'donations', 'donate', 'contact'])) {
        $info_css = get_template_directory() . '/assets/css/info-pages.css';
        $info_js = get_template_directory() . '/assets/js/info-pages.js';
        wp_enqueue_style(
            'spanishnova-info-pages',
            get_template_directory_uri() . '/assets/css/info-pages.css',
            ['spanishnova-main'],
            file_exists($info_css) ? filemtime($info_css) : '0.1.0'
        );
        wp_enqueue_script(
            'spanishnova-info-pages',
            get_template_directory_uri() . '/assets/js/info-pages.js',
            [],
            file_exists($info_js) ? filemtime($info_js) : '0.1.0',
            true
        );
    }

    wp_enqueue_script('spanishnova-main', get_template_directory_uri() . '/assets/js/main.js', [], '0.1.0', true);
}
add_action('wp_enqueue_scripts', 'spanishnova_enqueue_assets');
