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
    $path = luxureat_static_current_path();
    $path = $path === '' ? 'zh' : $path;
    $aliases = luxureat_static_aliases();
    $path = isset($aliases[$path]) ? $aliases[$path] : $path;

    wp_enqueue_style(
        'luxureat-integration',
        $theme_uri . '/integration.css',
        array(),
        filemtime($theme_dir . '/integration.css')
    );

    $catalog = array(
        'product-data' => array('src' => 'assets/data/products.js', 'dependencies' => array()),
        'event-data' => array('src' => 'assets/data/events.js', 'dependencies' => array()),
        'journal-data' => array('src' => 'assets/data/journal.js', 'dependencies' => array()),
        'brand-data' => array('src' => 'assets/data/brand.js', 'dependencies' => array()),
        'core' => array('src' => 'assets/js/core.js', 'dependencies' => array()),
        'products' => array('src' => 'assets/js/products.js', 'dependencies' => array('product-data')),
        'events' => array('src' => 'assets/js/events.js', 'dependencies' => array('event-data')),
        'journal' => array('src' => 'assets/js/journal.js', 'dependencies' => array('journal-data')),
        'brand' => array('src' => 'assets/js/brand.js', 'dependencies' => array('brand-data')),
    );
    $assets_by_path = array(
        'zh' => array('product-data', 'event-data', 'events', 'journal-data', 'journal', 'products', 'core'),
        'zh/journal' => array('journal-data', 'journal', 'core'),
        'zh/caviar' => array('product-data', 'products', 'core'),
        'zh/rituals' => array('journal-data', 'journal', 'core'),
        'zh/news' => array('event-data', 'journal-data', 'journal', 'core'),
        'zh/certification' => array('core'),
        'zh/gifting' => array('brand-data', 'brand', 'core'),
        'zh/contact' => array('brand-data', 'brand', 'core'),
        'zh/bag' => array('product-data', 'products', 'core'),
        'en' => array('product-data', 'event-data', 'events', 'journal-data', 'journal', 'products', 'core'),
        'en/journal' => array('journal-data', 'journal', 'core'),
        'en/products' => array('product-data', 'products', 'core'),
        'en/rituals' => array('journal-data', 'journal', 'core'),
        'en/news' => array('event-data', 'journal-data', 'journal', 'core'),
        'en/certification' => array('core'),
        'en/gifting' => array('brand-data', 'brand', 'core'),
        'en/contact' => array('brand-data', 'brand', 'core'),
        'en/bag' => array('product-data', 'products', 'core'),
    );

    foreach (isset($assets_by_path[$path]) ? $assets_by_path[$path] : array('core') as $handle) {
        if (!isset($catalog[$handle])) {
            continue;
        }
        $script = $catalog[$handle];
        $source = $theme_dir . '/' . $script['src'];
        if (!is_file($source)) {
            continue;
        }
        $dependencies = array_map(function ($dependency) {
            return 'luxureat-' . $dependency;
        }, $script['dependencies']);
        wp_enqueue_script(
            'luxureat-' . $handle,
            $theme_uri . '/' . $script['src'],
            $dependencies,
            filemtime($source),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'luxureat_static_assets');

function luxureat_static_defer_scripts($tag, $handle) {
    if (strpos($handle, 'luxureat-') !== 0 || strpos($tag, ' defer') !== false) {
        return $tag;
    }

    return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'luxureat_static_defer_scripts', 10, 2);

function luxureat_static_cache_headers($headers) {
    if (!is_admin() && !is_user_logged_in()) {
        $headers['Cache-Control'] = 'public, max-age=300, stale-while-revalidate=86400';
    }

    return $headers;
}
add_filter('wp_headers', 'luxureat_static_cache_headers');

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
