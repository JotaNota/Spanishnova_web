<?php
/**
 * Preview or apply Grammar post-title changes from the local roadmap.
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

if ($apply && !check_admin_referer('spanishnova_sync_grammar_titles')) {
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

foreach (['wp_post_id', 'public_title'] as $column) {
    if (!array_key_exists($column, $columns)) {
        wp_die('Missing required CSV column: ' . esc_html($column));
    }
}

$changes = [];
$skipped = [];
$updated = 0;

while (($values = fgetcsv($handle)) !== false) {
    $row = array_combine($headers, array_pad($values, count($headers), ''));

    if ($row === false) {
        $skipped[] = 'Unreadable CSV row.';
        continue;
    }

    $post_id = absint(trim((string) $row['wp_post_id']));
    $new_title = trim((string) $row['public_title']);

    if (!$post_id || $new_title === '') {
        $skipped[] = 'Skipped a row with an empty wp_post_id or public_title.';
        continue;
    }

    $post = get_post($post_id);

    if (!$post) {
        $skipped[] = "ID {$post_id}: post not found.";
        continue;
    }

    if ($post->post_type !== 'grammar') {
        $skipped[] = "ID {$post_id}: post type is {$post->post_type}, not grammar.";
        continue;
    }

    if ($post->post_title === $new_title) {
        continue;
    }

    $change = [
        'id' => $post_id,
        'before' => $post->post_title,
        'after' => $new_title,
    ];

    $changes[] = $change;

    if ($apply) {
        $result = wp_update_post(
            [
                'ID' => $post_id,
                'post_title' => $new_title,
            ],
            true
        );

        if (is_wp_error($result)) {
            $skipped[] = "ID {$post_id}: " . $result->get_error_message();
            continue;
        }

        $updated++;
    }
}

fclose($handle);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Grammar title sync</title>
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
    <h1>Grammar title sync</h1>

    <?php if ($apply) : ?>
        <p class="notice">Applied: <?php echo esc_html((string) $updated); ?> title change(s).</p>
    <?php else : ?>
        <p class="notice">Dry run. Nothing has been changed.</p>
    <?php endif; ?>

    <p>Planned title changes: <?php echo esc_html((string) count($changes)); ?></p>

    <?php if ($changes) : ?>
        <table>
            <thead>
                <tr><th>ID</th><th>Current title</th><th>New title</th></tr>
            </thead>
            <tbody>
                <?php foreach ($changes as $change) : ?>
                    <tr>
                        <td><?php echo esc_html((string) $change['id']); ?></td>
                        <td><?php echo esc_html($change['before']); ?></td>
                        <td><?php echo esc_html($change['after']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ($skipped) : ?>
        <h2>Skipped</h2>
        <ul class="error">
            <?php foreach ($skipped as $message) : ?>
                <li><?php echo esc_html($message); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!$apply && $changes) : ?>
        <p>
            <a class="button" href="<?php echo esc_url(wp_nonce_url(add_query_arg('apply', '1'), 'spanishnova_sync_grammar_titles')); ?>">
                Apply these title changes
            </a>
        </p>
    <?php endif; ?>
</body>
</html>
