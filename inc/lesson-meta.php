<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Lesson details shared by SpanishNova learning content types.
 * Keys are theme-owned; existing fields from external plugins are not migrated.
 */
function spanishnova_lesson_details_fields() {
    return [
        'duration' => ['label' => 'Duration', 'placeholder' => '10 min', 'type' => 'text'],
        'video_url' => ['label' => 'Video URL', 'placeholder' => 'https://youtube.com/', 'type' => 'url'],
        'pdf_url' => ['label' => 'PDF URL', 'placeholder' => 'https://', 'type' => 'url'],
        'audio_url' => ['label' => 'Audio URL', 'placeholder' => 'https://', 'type' => 'url'],
        'slides_url' => ['label' => 'Slides URL', 'placeholder' => 'https://', 'type' => 'url'],
    ];
}

function spanishnova_add_lesson_details_metabox($post_type) {
    $types = ['grammar', 'vocabulary', 'readings', 'conversations', 'practice', 'resources'];
    if (!in_array($post_type, $types, true)) return;

    // Avoid a second "Lesson details" box when it was registered elsewhere.
    global $wp_meta_boxes;
    foreach (['normal', 'side', 'advanced'] as $context) {
        foreach (($wp_meta_boxes[$post_type][$context] ?? []) as $boxes) {
            foreach ((array) $boxes as $box) {
                if (is_array($box) && isset($box['title']) &&
                    strtolower(wp_strip_all_tags($box['title'])) === 'lesson details') {
                    return;
                }
            }
        }
    }

    add_meta_box('spanishnova-lesson-details', 'Lesson details',
        'spanishnova_render_lesson_details_metabox', $post_type, 'normal', 'default');
}
add_action('add_meta_boxes', 'spanishnova_add_lesson_details_metabox', 99);

function spanishnova_render_lesson_details_metabox($post) {
    wp_nonce_field('spanishnova_save_lesson_details', 'spanishnova_lesson_details_nonce');
    echo '<p>Complete only the fields that this lesson uses. Empty fields do not appear on the lesson page.</p>';
    foreach (spanishnova_lesson_details_fields() as $field => $config) {
        $key = '_spanishnova_' . $field;
        $value = get_post_meta($post->ID, $key, true);
        echo '<p style="display:grid;grid-template-columns:180px minmax(0,1fr);gap:12px;align-items:center">';
        echo '<label for="' . esc_attr($key) . '">' . esc_html($config['label']) . '</label>';
        echo '<input class="widefat" id="' . esc_attr($key) . '" name="' . esc_attr($key) .
             '" type="' . esc_attr($config['type']) . '" value="' . esc_attr($value) .
             '" placeholder="' . esc_attr($config['placeholder']) . '"></p>';
    }
}

function spanishnova_save_lesson_details($post_id, $post, $update) {
    if (!isset($_POST['spanishnova_lesson_details_nonce'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['spanishnova_lesson_details_nonce'])), 'spanishnova_save_lesson_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    foreach (spanishnova_lesson_details_fields() as $field => $config) {
        $key = '_spanishnova_' . $field;
        if (!isset($_POST[$key]) || !is_string($_POST[$key])) continue;
        $raw = wp_unslash($_POST[$key]);
        $value = $config['type'] === 'url' ? esc_url_raw($raw) : sanitize_text_field($raw);
        if ($value === '') delete_post_meta($post_id, $key);
        else update_post_meta($post_id, $key, $value);
    }
}
add_action('save_post', 'spanishnova_save_lesson_details', 10, 3);
