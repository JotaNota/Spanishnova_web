<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_register_taxonomies() {
    $post_types = ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'];
    $grammar_post_types = ['grammar', 'readings', 'conversations', 'practice'];
    $topic_post_types = ['vocabulary', 'readings', 'conversations', 'practice', 'resources'];

    register_taxonomy('level_tax', $post_types, [
        'labels' => [
            'name' => __('Levels', 'spanishnova'),
            'singular_name' => __('Level', 'spanishnova'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'level'],
    ]);

    register_taxonomy('grammar_tax', $grammar_post_types, [
        'labels' => [
            'name' => __('Grammar', 'spanishnova'),
            'singular_name' => __('Grammar term', 'spanishnova'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'grammar-topic'],
    ]);

    register_taxonomy('topic_tax', $topic_post_types, [
        'labels' => [
            'name' => __('Topics', 'spanishnova'),
            'singular_name' => __('Topic', 'spanishnova'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'topic'],
    ]);

    foreach ($post_types as $post_type) {
        register_taxonomy_for_object_type('post_tag', $post_type);
    }
}
add_action('init', 'spanishnova_register_taxonomies');
