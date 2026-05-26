<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_enqueue_assets() {
    wp_enqueue_style('spanishnova-main', get_template_directory_uri() . '/assets/css/main.css', [], '0.1.0');
    wp_enqueue_script('spanishnova-main', get_template_directory_uri() . '/assets/js/main.js', [], '0.1.0', true);
}
add_action('wp_enqueue_scripts', 'spanishnova_enqueue_assets');
