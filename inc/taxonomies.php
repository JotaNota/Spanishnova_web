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

    register_taxonomy('route_tax', $post_types, [
        'labels' => [
            'name' => __('Routes', 'spanishnova'),
            'singular_name' => __('Route', 'spanishnova'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'route'],
    ]);

    foreach ($post_types as $post_type) {
        register_taxonomy_for_object_type('post_tag', $post_type);
    }
}
add_action('init', 'spanishnova_register_taxonomies');

function spanishnova_register_route_meta() {
    $post_types = ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'];

    foreach ($post_types as $post_type) {
        register_post_meta($post_type, 'route_block', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);

        register_post_meta($post_type, 'route_step', [
            'type' => 'integer',
            'single' => true,
            'show_in_rest' => true,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }
}
add_action('init', 'spanishnova_register_route_meta');
