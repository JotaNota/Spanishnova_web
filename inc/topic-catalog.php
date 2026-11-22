<?php
if (!defined('ABSPATH')) { exit; }

/**
 * Approved editorial topics. Legacy terms remain in the database until
 * content assignments are audited and migrated.
 */
function spanishnova_topic_slugs() {
    return [
        'daily-life', 'travel', 'food', 'work', 'education',
        'culture', 'entertainment', 'society', 'science-nature',
    ];
}

/**
 * Keep former topic terms out of public selectors, menus and topic chips.
 * Does not change taxonomy assignments, edit screens, or stored terms.
 */
function spanishnova_filter_public_topic_terms($terms, $taxonomies, $args, $term_query) {
    if (is_admin() || is_wp_error($terms) || !is_array($terms)) return $terms;
    if (!in_array('topic_tax', (array) $taxonomies, true)) return $terms;
    if (count((array) $taxonomies) !== 1) return $terms;
    if (!$terms || !($terms[0] instanceof WP_Term)) return $terms;

    $allowed = spanishnova_topic_slugs();
    return array_values(array_filter($terms, function ($term) use ($allowed) {
        return $term instanceof WP_Term && in_array($term->slug, $allowed, true);
    }));
}
add_filter('get_terms', 'spanishnova_filter_public_topic_terms', 20, 4);
