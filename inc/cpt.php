<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_register_cpts() {
    $post_types = [
        'grammar' => ['Grammar', 'Grammar'],
        'vocabulary' => ['Vocabulary', 'Vocabulary'],
        'readings' => ['Readings', 'Reading'],
        'conversations' => ['Conversations', 'Conversation'],
        'practice' => ['Practice', 'Practice'],
        'resources' => ['Resources', 'Resource'],
    ];

    foreach ($post_types as $slug => $labels) {
        register_post_type($slug, [
            'labels' => [
                'name' => __($labels[0], 'spanishnova'),
                'singular_name' => __($labels[1], 'spanishnova'),
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'menu_position' => 20,
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
            'rewrite' => ['slug' => $slug],
        ]);
    }
}
add_action('init', 'spanishnova_register_cpts');
