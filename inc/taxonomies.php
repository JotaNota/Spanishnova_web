<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_register_taxonomies() {
    $post_types = ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'];

    register_taxonomy('level', $post_types, [
        'labels' => [
            'name' => __('Levels', 'spanishnova'),
            'singular_name' => __('Level', 'spanishnova'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'level'],
    ]);

    register_taxonomy('topic', $post_types, [
        'labels' => [
            'name' => __('Topics', 'spanishnova'),
            'singular_name' => __('Topic', 'spanishnova'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'topic'],
    ]);
}
add_action('init', 'spanishnova_register_taxonomies');
