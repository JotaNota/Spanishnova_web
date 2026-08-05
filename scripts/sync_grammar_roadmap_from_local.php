<?php
/**
 * Preview or add local Grammar posts missing from the grammar roadmap.
 *
 * Open this file through the local site. It loads WordPress directly; it does
 * not use REST, WP-CLI, credentials, or external network requests.
 */

declare(strict_types=1);

$wp_load = dirname(__DIR__, 4) . '/wp-load.php';
$roadmap = dirname(__DIR__) . '/docs/content-system/roadmaps/grammar-roadmap.csv';

if (!is_file($wp_load)) {
    http_response_code(500);
    exit('Could not locate wp-load.php.');
}

require_once $wp_load;

if (!current_user_can('edit_posts')) {
    auth_redirect();
    exit;
}

if (!is_file($roadmap)) {
    http_response_code(500);
    exit('Could not locate grammar roadmap: ' . esc_html($roadmap));
}

$apply = isset($_GET['apply']) && $_GET['apply'] === '1';

if ($apply && !check_admin_referer('spanishnova_sync_grammar_roadmap')) {
    wp_die('Invalid confirmation token.');
}

$handle = fopen($roadmap, 'r');

if ($handle === false) {
    wp_die('Could not read the grammar roadmap.');
}

$headers = fgetcsv($handle);

if ($headers === false) {
    wp_die('The grammar roadmap is empty.');
}

$headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]);
$columns = array_flip($headers);

foreach (['pk_grammar', 'base_slug', 'topic_base', 'public_title', 'status', 'cpt', 'output_folder', 'wp_post_id', 'wp_slug', 'wp_status', 'last_wp_seen_modified_gmt'] as $column) {
    if (!array_key_exists($column, $columns)) {
        wp_die('Missing required CSV column: ' . esc_html($column));
    }
}

$rows = [];
$roadmap_post_ids = [];
$highest_pk = 0;

while (($values = fgetcsv($handle)) !== false) {
    $row = array_combine($headers, array_pad($values, count($headers), ''));

    if ($row === false) {
        fclose($handle);
        wp_die('Could not read a CSV row.');
    }

    $rows[] = $row;

    $post_id = trim((string) ($row['wp_post_id'] ?? ''));
    if ($post_id !== '') {
        $roadmap_post_ids[$post_id] = true;
    }

    $pk = trim((string) ($row['pk_grammar'] ?? ''));
    if (ctype_digit($pk)) {
        $highest_pk = max($highest_pk, (int) $pk);
    }
}

fclose($handle);

$posts = get_posts([
    'post_type' => 'grammar',
    'post_status' => 'publish',
    'numberposts' => -1,
    'orderby' => 'ID',
    'order' => 'ASC',
]);

$missing_posts = [];

foreach ($posts as $post) {
    if (isset($roadmap_post_ids[(string) $post->ID])) {
        continue;
    }

    $missing_posts[] = $post;
}

$added = 0;
$error = '';

if ($apply && $missing_posts) {
    foreach ($missing_posts as $post) {
        $title = trim((string) $post->post_title);
        $slug = trim((string) $post->post_name);
        $modified_gmt = trim((string) $post->post_modified_gmt);

        $row = array_fill_keys($headers, '');
        $row['pk_grammar'] = (string) (++$highest_pk);
        $row['base_slug'] = $slug;
        $row['topic_base'] = $title;
        $row['public_title'] = $title;
        $row['status'] = 'published';
        $row['cpt'] = 'grammar';
        $row['output_folder'] = 'docs/content-system/generated/markdown/grammar/';
        $row['wp_post_id'] = (string) $post->ID;
        $row['wp_slug'] = $slug;
        $row['wp_status'] = 'published';
        $row['last_wp_seen_modified_gmt'] = $modified_gmt;

        $rows[] = $row;
        $added++;
    }

    $temporary = $roadmap . '.tmp';
    $output = fopen($temporary, 'w');

    if ($output === false) {
        $error = 'Could not create the temporary roadmap file.';
    } else {
        fputcsv($output, $headers);

        foreach ($rows as $row) {
            $values = [];
            foreach ($headers as $header) {
                $values[] = (string) ($row[$header] ?? '');
            }
            fputcsv($output, $values);
        }

        fclose($output);

        if (!rename($temporary, $roadmap)) {
            @unlink($temporary);
            $error = 'Could not replace the grammar roadmap.';
            $added = 0;
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Grammar roadmap import</title>
    <style>
        body { font: 16px/1.5 system-ui, sans-serif; margin: 2rem; max-width: 1000px; }
        table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
        th, td { border: 1px solid #ccc; padding: .5rem; text-align: left; vertical-align: top; }
        .notice { padding: 1rem; background: #f0f6fc; border-left: 4px solid #2271b1; }
        .error { padding: 1rem; background: #fcf0f1; border-left: 4px solid #d63638; }
        .button { display: inline-block; padding: .7rem 1rem; background: #2271b1; color: #fff; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Grammar roadmap import</h1>

    <?php if ($error !== '') : ?>
        <p class="error"><?php echo esc_html($error); ?></p>
    <?php elseif ($apply) : ?>
        <p class="notice">Added: <?php echo esc_html((string) $added); ?> roadmap row(s).</p>
    <?php else : ?>
        <p class="notice">Dry run. Nothing has been changed.</p>
    <?php endif; ?>

    <p>Published Grammar posts: <?php echo esc_html((string) count($posts)); ?></p>
    <p>Posts missing from the roadmap: <?php echo esc_html((string) count($missing_posts)); ?></p>

    <?php if ($missing_posts) : ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Title</th><th>Slug</th><th>Modified (GMT)</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($missing_posts as $post) : ?>
                    <tr>
                        <td><?php echo esc_html((string) $post->ID); ?></td>
                        <td><?php echo esc_html($post->post_title); ?></td>
                        <td><?php echo esc_html($post->post_name); ?></td>
                        <td><?php echo esc_html($post->post_modified_gmt); ?></td>
                        <td><?php echo esc_html($post->post_status); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!$apply && $missing_posts) : ?>
        <p>
            <a class="button" href="<?php echo esc_url(wp_nonce_url(add_query_arg('apply', '1'), 'spanishnova_sync_grammar_roadmap')); ?>">
                Add these posts to the roadmap
            </a>
        </p>
    <?php endif; ?>
</body>
</html>
