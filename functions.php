<?php
if (!defined('ABSPATH')) { exit; }

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/lesson-meta.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/taxonomy-seeds.php';
require_once get_template_directory() . '/inc/topic-catalog.php';


if (!function_exists('spanishnova_get_card_excerpt')) {
    function spanishnova_get_card_excerpt($post_id = null) {
        $post_id = $post_id ?: get_the_ID();
        $content = get_post_field('post_content', $post_id);

        if ($content && preg_match('/<p[^>]*class=["\'][^"\']*\bsn-intro\b[^"\']*["\'][^>]*>(.*?)<\/p>/is', $content, $matches)) {
            return wp_strip_all_tags($matches[1]);
        }

        $excerpt = get_the_excerpt($post_id);

        if (!$excerpt) {
            $excerpt = wp_trim_words(wp_strip_all_tags($content), 18);
        }

        return $excerpt;
    }
}



/**
 * Filter topical CPT archives from header menu without entering taxonomy
 * archives that combine different post types.
 */
function spanishnova_filter_topic_archive($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive()) return;

    $post_type = $query->get('post_type');
    if (!in_array($post_type, ['vocabulary', 'readings', 'conversations'], true)) return;
    if (!isset($_GET['sn_topic']) || !is_string($_GET['sn_topic'])) return;

    $slug = sanitize_title(wp_unslash($_GET['sn_topic']));
    if (!$slug || !term_exists($slug, 'topic_tax')) return;

    $filters = (array) $query->get('tax_query');
    $filters[] = [
        'taxonomy' => 'topic_tax',
        'field' => 'slug',
        'terms' => $slug,
        'include_children' => true,
    ];
    $query->set('tax_query', $filters);
}
add_action('pre_get_posts', 'spanishnova_filter_topic_archive');


/**
 * Handle visitor messages from the floating contact form.
 */
function spanishnova_handle_contact() {
    if (!check_ajax_referer('spanishnova_contact', 'nonce', false)) {
        wp_send_json_error(['message' => 'Please refresh the page and try again.'], 403);
    }

    if (!empty($_POST['website'])) {
        wp_send_json_error(['message' => 'Message could not be sent.'], 400);
    }

    $name = isset($_POST['name']) && is_string($_POST['name'])
        ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) && is_string($_POST['email'])
        ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $message = isset($_POST['message']) && is_string($_POST['message'])
        ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    if (!$name || strlen($name) > 120 || !is_email($email) ||
        strlen($email) > 254 || !$message || strlen($message) > 3000) {
        wp_send_json_error(['message' => 'Please check the required fields.'], 400);
    }

    // Limit submissions from the same IP without storing the address.
    $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    $key = 'sn_contact_' . md5($ip . wp_salt('nonce'));
    if (get_transient($key)) {
        wp_send_json_error(['message' => 'Please wait a minute before sending another message.'], 429);
    }

    $body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
    $headers = ['Reply-To: ' . $email];
    $sent = wp_mail('info@spanishnova.com', 'SpanishNova contact form', $body, $headers);

    if (!$sent) {
        wp_send_json_error(['message' => 'Message could not be sent. Please try again later.'], 500);
    }

    set_transient($key, 1, MINUTE_IN_SECONDS);
    wp_send_json_success(['message' => 'Message sent. Thank you!']);
}
add_action('wp_ajax_spanishnova_contact', 'spanishnova_handle_contact');
add_action('wp_ajax_nopriv_spanishnova_contact', 'spanishnova_handle_contact');
