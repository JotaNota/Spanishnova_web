<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_seed_taxonomy_terms() {
    spanishnova_seed_flat_taxonomy_terms('level_tax', [
        ['name' => 'Beginner', 'slug' => 'beginner'],
        ['name' => 'Intermediate', 'slug' => 'intermediate'],
        ['name' => 'Advanced', 'slug' => 'advanced'],
    ]);

    spanishnova_seed_hierarchical_taxonomy_terms('grammar_tax', [
        [
            'name' => 'Tenses',
            'slug' => 'tenses',
            'children' => [
                ['name' => 'Present', 'slug' => 'present'],
                [
                    'name' => 'Past',
                    'slug' => 'past',
                    'children' => [
                        ['name' => 'Preterite', 'slug' => 'preterite'],
                        ['name' => 'Imperfect', 'slug' => 'imperfect'],
                    ],
                ],
                ['name' => 'Future', 'slug' => 'future'],
                ['name' => 'Conditional', 'slug' => 'conditional'],
                ['name' => 'Progressive', 'slug' => 'progressive'],
                ['name' => 'Perfect Tenses', 'slug' => 'perfect-tenses'],
            ],
        ],
        [
            'name' => 'Verbs',
            'slug' => 'verbs',
            'children' => [
                ['name' => 'Ser', 'slug' => 'ser'],
                ['name' => 'Estar', 'slug' => 'estar'],
                ['name' => 'Tener', 'slug' => 'tener'],
                ['name' => 'Ir', 'slug' => 'ir'],
                ['name' => 'Haber', 'slug' => 'haber'],
                ['name' => 'Gustar-Type Verbs', 'slug' => 'gustar-type-verbs'],
                ['name' => 'Reflexive Verbs', 'slug' => 'reflexive-verbs'],
                ['name' => 'Irregular Verbs', 'slug' => 'irregular-verbs'],
                ['name' => 'Meaning-Changing Verbs', 'slug' => 'meaning-changing-verbs'],
            ],
        ],
        [
            'name' => 'Moods',
            'slug' => 'moods',
            'children' => [
                ['name' => 'Indicative', 'slug' => 'indicative'],
                ['name' => 'Subjunctive', 'slug' => 'subjunctive'],
                ['name' => 'Imperative', 'slug' => 'imperative'],
            ],
        ],
        [
            'name' => 'Parts of Speech',
            'slug' => 'parts-of-speech',
            'children' => [
                ['name' => 'Articles', 'slug' => 'articles'],
                ['name' => 'Nouns', 'slug' => 'nouns'],
                ['name' => 'Adjectives', 'slug' => 'adjectives'],
                ['name' => 'Pronouns', 'slug' => 'pronouns'],
                ['name' => 'Adverbs', 'slug' => 'adverbs'],
                ['name' => 'Prepositions', 'slug' => 'prepositions'],
                ['name' => 'Conjunctions', 'slug' => 'conjunctions'],
            ],
        ],
        [
            'name' => 'Sentence Structure',
            'slug' => 'sentence-structure',
            'children' => [
                ['name' => 'Questions', 'slug' => 'questions'],
                ['name' => 'Negation', 'slug' => 'negation'],
                ['name' => 'Comparisons', 'slug' => 'comparisons'],
                ['name' => 'Word Order', 'slug' => 'word-order'],
                ['name' => 'Direct and Indirect Speech', 'slug' => 'direct-and-indirect-speech'],
            ],
        ],
    ]);

    spanishnova_seed_hierarchical_taxonomy_terms('topic_tax', [
        [
            'name' => 'Daily Life',
            'slug' => 'daily-life',
            'children' => [
                ['name' => 'Home', 'slug' => 'home'],
                ['name' => 'Routine', 'slug' => 'routine'],
                ['name' => 'Relationships', 'slug' => 'relationships'],
                ['name' => 'Health', 'slug' => 'health'],
                ['name' => 'Hobbies', 'slug' => 'hobbies'],
                ['name' => 'Leisure', 'slug' => 'leisure'],
            ],
        ],
        ['name' => 'Travel', 'slug' => 'travel'],
        ['name' => 'Food', 'slug' => 'food'],
        ['name' => 'Work', 'slug' => 'work'],
        ['name' => 'Culture', 'slug' => 'culture'],
        ['name' => 'Entertainment', 'slug' => 'entertainment'],
        ['name' => 'Education', 'slug' => 'education'],
        ['name' => 'Society', 'slug' => 'society'],
    ]);
}
add_action('init', 'spanishnova_seed_taxonomy_terms', 20);

function spanishnova_seed_flat_taxonomy_terms($taxonomy, array $terms) {
    if (!taxonomy_exists($taxonomy)) {
        return;
    }

    foreach ($terms as $term) {
        spanishnova_ensure_taxonomy_term($taxonomy, $term);
    }
}

function spanishnova_seed_hierarchical_taxonomy_terms($taxonomy, array $terms, $parent_id = 0) {
    if (!taxonomy_exists($taxonomy)) {
        return;
    }

    foreach ($terms as $term) {
        $term_id = spanishnova_ensure_taxonomy_term($taxonomy, $term, $parent_id);

        if ($term_id && !empty($term['children'])) {
            spanishnova_seed_hierarchical_taxonomy_terms($taxonomy, $term['children'], $term_id);
        }
    }
}

function spanishnova_ensure_taxonomy_term($taxonomy, array $term, $parent_id = 0) {
    if (empty($term['name']) || empty($term['slug']) || !taxonomy_exists($taxonomy)) {
        return 0;
    }

    $existing = term_exists($term['slug'], $taxonomy);

    if (!$existing) {
        $existing = term_exists($term['name'], $taxonomy, (int) $parent_id);
    }

    if (is_array($existing) && !empty($existing['term_id'])) {
        return (int) $existing['term_id'];
    }

    if ((int) $existing > 0) {
        return (int) $existing;
    }

    $inserted = wp_insert_term($term['name'], $taxonomy, [
        'slug' => $term['slug'],
        'parent' => (int) $parent_id,
    ]);

    if (is_wp_error($inserted) || empty($inserted['term_id'])) {
        return 0;
    }

    return (int) $inserted['term_id'];
}
