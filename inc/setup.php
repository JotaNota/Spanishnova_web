<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus([
        'primary' => __('Primary Navigation', 'spanishnova'),
        'utility' => __('Utility Navigation', 'spanishnova'),
    ]);
}
add_action('after_setup_theme', 'spanishnova_setup');
