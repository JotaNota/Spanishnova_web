<?php
/**
 * Run via WP-CLI from the WordPress installation:
 *   wp eval-file wp-content/themes/spanishnova/scripts/topic-taxonomy-migration.php audit
 *   wp eval-file wp-content/themes/spanishnova/scripts/topic-taxonomy-migration.php migrate home daily-life
 *   wp eval-file wp-content/themes/spanishnova/scripts/topic-taxonomy-migration.php migrate home daily-life --execute
 *
 * This script never deletes terms. Migration requires explicit --execute.
 * Backup the WordPress database before applying any migration.
 */
if (!defined('WP_CLI') || !WP_CLI) {
    exit("This script requires WP-CLI.\n");
}

$allowed = ['daily-life', 'travel', 'food', 'work', 'education', 'culture', 'entertainment', 'society', 'science-nature'];
$arguments = isset($args) && is_array($args) ? $args : [];
$action = $arguments[0] ?? 'audit';
$terms = get_terms(['taxonomy' => 'topic_tax', 'hide_empty' => false]);
if (is_wp_error($terms)) WP_CLI::error($terms->get_error_message());

// Theme's public get_terms filter is not active in WP-CLI, but explicitly
// work from the complete term list regardless.
if ($action === 'audit') {
    $rows = [];
    foreach ($terms as $term) {
        $ids = get_objects_in_term($term->term_id, 'topic_tax');
        if (is_wp_error($ids)) WP_CLI::error($ids->get_error_message());
        $rows[] = [
            'slug' => $term->slug,
            'name' => $term->name,
            'approved' => in_array($term->slug, $allowed, true) ? 'yes' : 'no',
            'parent' => (int) $term->parent,
            'assigned_posts' => count(array_unique(array_map('intval', $ids))),
        ];
    }
    WP_CLI\Utils\format_items('table', $rows, ['slug', 'name', 'approved', 'parent', 'assigned_posts']);
    WP_CLI::log('Audit only. No data changed.');
    return;
}

if ($action !== 'migrate') {
    WP_CLI::error('Use audit or migrate <legacy-slug> <approved-slug> [--execute].');
}

$from_slug = sanitize_title($arguments[1] ?? '');
$to_slug = sanitize_title($arguments[2] ?? '');
if (!$from_slug || !$to_slug || $from_slug === $to_slug) {
    WP_CLI::error('Supply distinct source and destination slugs.');
}
if (in_array($from_slug, $allowed, true) || !in_array($to_slug, $allowed, true)) {
    WP_CLI::error('Source must be a legacy slug; destination must be an approved topic.');
}
$from = get_term_by('slug', $from_slug, 'topic_tax');
$to = get_term_by('slug', $to_slug, 'topic_tax');
if (!$from || !$to) WP_CLI::error('Both source and destination terms must exist.');

$ids = get_objects_in_term($from->term_id, 'topic_tax');
if (is_wp_error($ids)) WP_CLI::error($ids->get_error_message());
$ids = array_unique(array_map('intval', $ids));
WP_CLI::log(sprintf('Proposed reassignment: %s -> %s, %d content items.', $from->name, $to->name, count($ids)));
foreach ($ids as $id) {
    WP_CLI::log(sprintf('%d | %s | %s', $id, get_post_type($id), get_the_title($id)));
}
if (!in_array('--execute', $arguments, true)) {
    WP_CLI::log('DRY RUN: no assignments changed. Add --execute only after reviewing the list and backing up the database.');
    return;
}
if (!WP_CLI::confirm('Apply this reassignment? Legacy term will remain in WordPress.', [], false)) {
    WP_CLI::error('Canceled.');
}
foreach ($ids as $id) {
    // Append the new topic and keep all other assigned topics.
    $added = wp_set_object_terms($id, [(int) $to->term_id], 'topic_tax', true);
    if (is_wp_error($added)) {
        WP_CLI::warning('Could not add destination to ' . $id . ': ' . $added->get_error_message());
        continue;
    }
    wp_remove_object_terms($id, [(int) $from->term_id], 'topic_tax');
}
WP_CLI::success('Reassignment completed. Source term remains in the database for separate review.');
