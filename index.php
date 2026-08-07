<?php
if (!defined('ABSPATH')) {
    exit;
}

$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
$raw_query = parse_url($request_uri, PHP_URL_QUERY);
$decoded_query = is_string($raw_query) ? rawurldecode($raw_query) : '';
$luxureat_spam_query =
    ($decoded_query !== '' && preg_match('/^[A-Za-z]=[0-9]{7,}$/', $decoded_query)) ||
    ($decoded_query !== '' && preg_match('#^[^=&]+/[^=&]+\.html(?:&.*)?$#i', $decoded_query));

if ($luxureat_spam_query) {
    status_header(404);
    nocache_headers();
    header('X-Robots-Tag: noindex, nofollow', true);
    ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php esc_html_e('Page not found', 'luxureat-static'); ?></title>
</head>
<body style="margin:0;background:#101010;color:#e5e2e1;font-family:Spectral;display:grid;min-height:100vh;place-items:center;text-align:center;padding:24px;">
    <main>
        <p style="color:#9df5ec;letter-spacing:.2em;text-transform:uppercase;font-size:12px;">LuxurEat</p>
        <h1 style="font-family:'Nyght Serif';font-weight:400;"><?php esc_html_e('Page not found', 'luxureat-static'); ?></h1>
        <p><a style="color:#e9c349;" href="<?php echo esc_url(luxureat_static_url('zh')); ?>"><?php esc_html_e('Return to home', 'luxureat-static'); ?></a></p>
    </main>
</body>
</html><?php
    exit;
}

$routes = require get_template_directory() . '/routes.php';
$path = luxureat_static_current_path();
$aliases = luxureat_static_aliases();

if ($path === '' || $path === '__home') {
    $path = 'zh';
}

if (isset($aliases[$path])) {
    $target_path = $aliases[$path];
    $pretty_paths = luxureat_static_pretty_paths();
    $canonical_request_path = isset($pretty_paths[$target_path]) ? trim($pretty_paths[$target_path], '/') : '';

    if ($canonical_request_path === $path) {
        $path = $target_path;
    } else {
        wp_safe_redirect(luxureat_static_url($target_path), 301);
        exit;
    }
}

if (!isset($routes[$path])) {
    status_header(404);
    nocache_headers();
    ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php esc_html_e('Page not found', 'luxureat-static'); ?></title>
    <?php wp_head(); ?>
</head>
<body style="margin:0;background:#101010;color:#e5e2e1;font-family:Spectral;display:grid;min-height:100vh;place-items:center;text-align:center;padding:24px;">
    <main>
        <p style="color:#9df5ec;letter-spacing:.2em;text-transform:uppercase;font-size:12px;">LuxurEat</p>
        <h1 style="font-family:'Nyght Serif';font-weight:400;"><?php esc_html_e('Page not found', 'luxureat-static'); ?></h1>
        <p><a style="color:#e9c349;" href="<?php echo esc_url(luxureat_static_url('zh')); ?>"><?php esc_html_e('Return to home', 'luxureat-static'); ?></a></p>
    </main>
    <?php wp_footer(); ?>
</body>
</html><?php
    exit;
}

status_header(200);
include get_template_directory() . '/' . $routes[$path];
