<?php
if (!defined('ABSPATH')) {
    exit;
}

function luxureat_static_routes() {
    $routes = require get_template_directory() . '/routes.php';
    return is_array($routes) ? $routes : array();
}

function luxureat_static_aliases() {
    return array(
        'index.html' => 'zh',
        'zh/index.html' => 'zh',
        'en/index.html' => 'en',
        'caviar' => 'zh/caviar',
        'caviar.html' => 'zh/caviar',
        'rituals' => 'zh/rituals',
        'rituals.html' => 'zh/rituals',
        'journal' => 'zh/journal',
        'journal.html' => 'zh/journal',
        'news' => 'zh/news',
        'news.html' => 'zh/news',
        'gifting' => 'zh/gifting',
        'gifting.html' => 'zh/gifting',
        'certification' => 'zh/certification',
        'certification.html' => 'zh/certification',
        'contact' => 'zh/contact',
        'contact.html' => 'zh/contact',
        'bag' => 'zh/bag',
        'bag.html' => 'zh/bag',
        'en/caviar' => 'en/products',
        'en/caviar.html' => 'en/products',
        'en/private' => 'en/gifting',
        'en/private.html' => 'en/gifting',
        'private-selection' => 'en/gifting',
        'private-selection.html' => 'en/gifting',
        'product-imperial-beluga' => 'zh/caviar',
        'product-imperial-beluga.html' => 'zh/caviar',
    );
}

function luxureat_static_normalize_path($path) {
    $path = is_string($path) ? $path : '';
    $path = trim($path);
    $path = trim($path, '/');
    $path = preg_replace('#/+#', '/', $path);
    return $path ? $path : '';
}

function luxureat_static_pretty_paths() {
    return array(
        'zh' => '/',
        'zh/caviar' => '/caviar/',
        'zh/rituals' => '/rituals/',
        'zh/journal' => '/journal/',
        'zh/news' => '/news/',
        'zh/gifting' => '/gifting/',
        'zh/certification' => '/certification/',
        'zh/contact' => '/contact/',
        'zh/bag' => '/bag/',
        'en' => '/en/',
        'en/products' => '/en/products/',
        'en/rituals' => '/en/rituals/',
        'en/journal' => '/en/journal/',
        'en/news' => '/en/news/',
        'en/gifting' => '/en/gifting/',
        'en/certification' => '/en/certification/',
        'en/contact' => '/en/contact/',
        'en/bag' => '/en/bag/',
    );
}

function luxureat_static_url($path = 'zh', $suffix = '') {
    $path = luxureat_static_normalize_path($path);
    $suffix = is_string($suffix) ? $suffix : '';
    $pretty_paths = luxureat_static_pretty_paths();

    $url = isset($pretty_paths[$path])
        ? home_url($pretty_paths[$path])
        : home_url('/' . $path . '/');

    return $url . $suffix;
}

function luxureat_static_current_path() {
    $query_path = get_query_var('luxureat_path');
    if (is_string($query_path) && $query_path !== '') {
        return luxureat_static_normalize_path($query_path);
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $request_path = parse_url($request_uri, PHP_URL_PATH);
    $home_path = parse_url(home_url('/'), PHP_URL_PATH);

    $request_path = is_string($request_path) ? $request_path : '/';
    $home_path = is_string($home_path) ? $home_path : '/';

    if ($home_path !== '/' && strpos($request_path, $home_path) === 0) {
        $request_path = substr($request_path, strlen($home_path));
    }

    return luxureat_static_normalize_path($request_path);
}

function luxureat_static_assets() {
    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style(
        'luxureat-integration',
        $theme_uri . '/integration.css',
        array(),
        filemtime($theme_dir . '/integration.css')
    );


    wp_enqueue_script(
        'luxureat-product-data',
        $theme_uri . '/assets/data/products.js',
        array(),
        filemtime($theme_dir . '/assets/data/products.js'),
        true
    );

    wp_enqueue_script('luxureat-event-data', $theme_uri . '/assets/data/events.js', array(), filemtime($theme_dir . '/assets/data/events.js'), true);
    wp_enqueue_script('luxureat-journal-data', $theme_uri . '/assets/data/journal.js', array(), filemtime($theme_dir . '/assets/data/journal.js'), true);
    wp_enqueue_script('luxureat-brand-data', $theme_uri . '/assets/data/brand.js', array(), filemtime($theme_dir . '/assets/data/brand.js'), true);
    wp_enqueue_script('luxureat-core', $theme_uri . '/assets/js/core.js', array(), filemtime($theme_dir . '/assets/js/core.js'), true);
    wp_enqueue_script('luxureat-products', $theme_uri . '/assets/js/products.js', array('luxureat-product-data'), filemtime($theme_dir . '/assets/js/products.js'), true);
    wp_enqueue_script('luxureat-events', $theme_uri . '/assets/js/events.js', array('luxureat-event-data'), filemtime($theme_dir . '/assets/js/events.js'), true);
    wp_enqueue_script('luxureat-journal', $theme_uri . '/assets/js/journal.js', array('luxureat-journal-data', 'luxureat-event-data'), filemtime($theme_dir . '/assets/js/journal.js'), true);
    wp_enqueue_script('luxureat-brand', $theme_uri . '/assets/js/brand.js', array('luxureat-brand-data'), filemtime($theme_dir . '/assets/js/brand.js'), true);
}
add_action('wp_enqueue_scripts', 'luxureat_static_assets');

function luxureat_static_register_routes() {
    foreach (array_keys(luxureat_static_routes()) as $route) {
        add_rewrite_rule('^' . preg_quote($route, '/') . '/?$', 'index.php?luxureat_path=' . $route, 'top');
    }

    foreach (array_keys(luxureat_static_aliases()) as $alias) {
        add_rewrite_rule('^' . preg_quote($alias, '/') . '/?$', 'index.php?luxureat_path=' . $alias, 'top');
    }

    add_rewrite_rule('^$', 'index.php?luxureat_path=__home', 'top');
}
add_action('init', 'luxureat_static_register_routes');

function luxureat_static_query_vars($vars) {
    $vars[] = 'luxureat_path';
    return $vars;
}
add_filter('query_vars', 'luxureat_static_query_vars');

function luxureat_static_template_include($template) {
    $path = luxureat_static_current_path();
    $routes = luxureat_static_routes();
    $aliases = luxureat_static_aliases();

    if ($path === '' || $path === '__home' || isset($routes[$path]) || isset($aliases[$path])) {
        return get_template_directory() . '/index.php';
    }

    return $template;
}
add_filter('template_include', 'luxureat_static_template_include');

function luxureat_static_flush_rewrites() {
    luxureat_static_register_routes();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'luxureat_static_flush_rewrites');
add_action('switch_theme', 'flush_rewrite_rules');
