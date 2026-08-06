<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_add_grammar_lesson_details_metabox() {
    add_meta_box(
        'spanishnova_grammar_lesson_details',
        'Lesson details',
        'spanishnova_render_grammar_lesson_details_metabox',
        'grammar',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes_grammar', 'spanishnova_add_grammar_lesson_details_metabox');

function spanishnova_render_grammar_lesson_details_metabox($post) {
    wp_nonce_field('spanishnova_save_grammar_lesson_details', 'spanishnova_grammar_lesson_details_nonce');

    $duration = get_post_meta($post->ID, '_sn_lesson_duration', true);
    $pdf_url = get_post_meta($post->ID, '_sn_lesson_pdf_url', true);
    $audio_url = get_post_meta($post->ID, '_sn_lesson_audio_url', true);
    $slides_url = get_post_meta($post->ID, '_sn_lesson_slides_url', true);
    $flashcard_word = get_post_meta($post->ID, '_sn_flashcard_word', true);
    $flashcard_meaning = get_post_meta($post->ID, '_sn_flashcard_meaning', true);
    $next_lesson_id = absint(get_post_meta($post->ID, '_sn_next_lesson_id', true));

    $lessons = get_posts(array(
        'post_type' => 'grammar',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post__not_in' => array($post->ID),
        'orderby' => 'title',
        'order' => 'ASC',
    ));
    ?>
    <p>Complete only the fields that this lesson uses. Empty fields do not appear on the lesson page.</p>

    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><label for="sn_lesson_duration">Duration</label></th>
            <td><input class="regular-text" id="sn_lesson_duration" name="sn_lesson_duration" type="text" value="<?php echo esc_attr($duration); ?>" placeholder="10 min"></td>
        </tr>
        <tr>
            <th scope="row"><label for="sn_lesson_pdf_url">PDF URL</label></th>
            <td><input class="large-text" id="sn_lesson_pdf_url" name="sn_lesson_pdf_url" type="url" value="<?php echo esc_url($pdf_url); ?>" placeholder="https://"></td>
        </tr>
        <tr>
            <th scope="row"><label for="sn_lesson_audio_url">Audio URL</label></th>
            <td><input class="large-text" id="sn_lesson_audio_url" name="sn_lesson_audio_url" type="url" value="<?php echo esc_url($audio_url); ?>" placeholder="https://"></td>
        </tr>
        <tr>
            <th scope="row"><label for="sn_lesson_slides_url">Slides URL</label></th>
            <td><input class="large-text" id="sn_lesson_slides_url" name="sn_lesson_slides_url" type="url" value="<?php echo esc_url($slides_url); ?>" placeholder="https://"></td>
        </tr>
        <tr>
            <th scope="row"><label for="sn_flashcard_word">Flashcard word</label></th>
            <td><input class="regular-text" id="sn_flashcard_word" name="sn_flashcard_word" type="text" value="<?php echo esc_attr($flashcard_word); ?>" placeholder="hablaré"></td>
        </tr>
        <tr>
            <th scope="row"><label for="sn_flashcard_meaning">Flashcard meaning</label></th>
            <td><input class="regular-text" id="sn_flashcard_meaning" name="sn_flashcard_meaning" type="text" value="<?php echo esc_attr($flashcard_meaning); ?>" placeholder="I will speak"></td>
        </tr>
        <tr>
            <th scope="row"><label for="sn_next_lesson_id">Next lesson</label></th>
            <td>
                <select id="sn_next_lesson_id" name="sn_next_lesson_id">
                    <option value="">Select a lesson</option>
                    <?php foreach ($lessons as $lesson) : ?>
                        <option value="<?php echo esc_attr($lesson->ID); ?>" <?php selected($next_lesson_id, $lesson->ID); ?>><?php echo esc_html($lesson->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

function spanishnova_save_grammar_lesson_details($post_id) {
    if (
        !isset($_POST['spanishnova_grammar_lesson_details_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['spanishnova_grammar_lesson_details_nonce'])), 'spanishnova_save_grammar_lesson_details')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $text_fields = array(
        '_sn_lesson_duration' => 'sn_lesson_duration',
        '_sn_flashcard_word' => 'sn_flashcard_word',
        '_sn_flashcard_meaning' => 'sn_flashcard_meaning',
    );

    foreach ($text_fields as $meta_key => $field_name) {
        $value = isset($_POST[$field_name]) ? sanitize_text_field(wp_unslash($_POST[$field_name])) : '';

        if ($value === '') {
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $value);
        }
    }

    $url_fields = array(
        '_sn_lesson_pdf_url' => 'sn_lesson_pdf_url',
        '_sn_lesson_audio_url' => 'sn_lesson_audio_url',
        '_sn_lesson_slides_url' => 'sn_lesson_slides_url',
    );

    foreach ($url_fields as $meta_key => $field_name) {
        $value = isset($_POST[$field_name]) ? esc_url_raw(wp_unslash($_POST[$field_name])) : '';

        if ($value === '') {
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $value);
        }
    }

    $next_lesson_id = isset($_POST['sn_next_lesson_id']) ? absint($_POST['sn_next_lesson_id']) : 0;

    if ($next_lesson_id && get_post_type($next_lesson_id) === 'grammar') {
        update_post_meta($post_id, '_sn_next_lesson_id', $next_lesson_id);
    } else {
        delete_post_meta($post_id, '_sn_next_lesson_id');
    }
}
add_action('save_post_grammar', 'spanishnova_save_grammar_lesson_details');
