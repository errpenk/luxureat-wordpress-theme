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
        if ($handle === 'core') {
            wp_localize_script('luxureat-core', 'LuxureatAccount', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('luxureat_account'),
                'loggedIn' => is_user_logged_in(),
                'lostPasswordUrl' => wp_lostpassword_url(home_url('/')),
                'logoutUrl' => wp_logout_url(home_url('/')),
            ));
        }
        if ($handle === 'products') {
            wp_localize_script('luxureat-products', 'LuxureatCheckout', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('luxureat_checkout'),
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'luxureat_static_assets');

function luxureat_static_mailpoet_subscribe($email) {
    if (!class_exists('\MailPoet\API\API')) {
        return new WP_Error('mailpoet_unavailable');
    }

    try {
        $api = \MailPoet\API\API::MP('v1');
        $lists = array_values(array_filter($api->getLists(), function ($list) {
            return isset($list['type']) && $list['type'] === 'default' && empty($list['deleted_at']);
        }));
        if (!$lists) {
            return new WP_Error('mailpoet_list_missing');
        }

        $preferred = array_values(array_filter($lists, function ($list) {
            return stripos($list['name'], 'LuxurEat') !== false;
        }));
        $list_id = (int) ($preferred ? $preferred[0]['id'] : $lists[0]['id']);
        $options = array('send_confirmation_email' => true, 'schedule_welcome_email' => true);
        try {
            $subscriber = $api->getSubscriber($email);
            $api->subscribeToLists($subscriber['id'], array($list_id), $options);
        } catch (\MailPoet\API\MP\v1\APIException $error) {
            if ((int) $error->getCode() !== 4) {
                throw $error;
            }
            $api->addSubscriber(array('email' => $email), array($list_id), $options);
        }
        return true;
    } catch (\Throwable $error) {
        return new WP_Error('mailpoet_failed');
    }
}

function luxureat_static_account_ajax() {
    $is_zh = isset($_POST['lang']) && sanitize_key(wp_unslash($_POST['lang'])) === 'zh';
    $message = function ($zh, $en) use ($is_zh) { return $is_zh ? $zh : $en; };
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_account')) {
        wp_send_json_error(array('message' => $message('请刷新页面后重试。', 'Please refresh the page and try again.')), 403);
    }
    if (is_user_logged_in()) {
        wp_send_json_success();
    }

    $mode = isset($_POST['mode']) ? sanitize_key(wp_unslash($_POST['mode'])) : 'login';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
    if (!is_email($email)) {
        wp_send_json_error(array('message' => $message('请输入有效邮箱。', 'Enter a valid email address.')), 400);
    }

    if ($mode === 'forgot') {
        $user = get_user_by('email', $email);
        if ($user) {
            $sent = retrieve_password($user->user_login);
            if (is_wp_error($sent)) {
                wp_send_json_error(array('message' => $message('暂时无法发送重置邮件，请稍后再试。', 'The reset email could not be sent. Please try again later.')), 500);
            }
        }
        wp_send_json_success(array('message' => $message('如果该邮箱已注册，密码重置链接已发送，请检查收件箱和垃圾邮件。', 'If the email is registered, a reset link has been sent. Please check your inbox and spam folder.')));
    }

    if (strlen($password) < 8) {
        wp_send_json_error(array('message' => $message('请输入有效邮箱和至少 8 位密码。', 'Enter a valid email and a password of at least 8 characters.')), 400);
    }

    if ($mode === 'register') {
        if (!function_exists('wc_create_new_customer') || get_option('woocommerce_enable_myaccount_registration') !== 'yes') {
            wp_send_json_error(array('message' => $message('暂未开放账号注册。', 'Account registration is not available yet.')), 403);
        }
        $user_id = wc_create_new_customer($email, '', $password);
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => wp_strip_all_tags($user_id->get_error_message())), 400);
        }
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true, is_ssl());
        if (!empty($_POST['newsletter'])) {
            luxureat_static_mailpoet_subscribe($email);
        }
        wp_send_json_success();
    }

    $user = get_user_by('email', $email);
    $credentials = array(
        'user_login' => $user ? $user->user_login : $email,
        'user_password' => $password,
        'remember' => !empty($_POST['remember']),
    );
    $signed_in = wp_signon($credentials, is_ssl());
    if (is_wp_error($signed_in)) {
        wp_send_json_error(array('message' => $message('邮箱或密码不正确。', 'Incorrect email or password.')), 401);
    }
    wp_send_json_success();
}
add_action('wp_ajax_nopriv_luxureat_account', 'luxureat_static_account_ajax');
add_action('wp_ajax_luxureat_account', 'luxureat_static_account_ajax');

function luxureat_static_checkout_ajax() {
    $is_zh = isset($_POST['lang']) && sanitize_key(wp_unslash($_POST['lang'])) === 'zh';
    $message = function ($zh, $en) use ($is_zh) { return $is_zh ? $zh : $en; };
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_checkout')) {
        wp_send_json_error(array('message' => $message('请刷新页面后重试。', 'Please refresh the page and try again.')), 403);
    }
    if (!function_exists('WC') || !function_exists('wc_get_product_id_by_sku')) {
        wp_send_json_error(array('message' => $message('结算服务暂时不可用。', 'Checkout is temporarily unavailable.')), 503);
    }
    if (null === WC()->cart && function_exists('wc_load_cart')) {
        wc_load_cart();
    }
    if (null === WC()->cart) {
        wp_send_json_error(array('message' => $message('无法建立购物车。', 'Could not start the cart.')), 503);
    }

    $items = isset($_POST['items']) ? json_decode(wp_unslash($_POST['items']), true) : null;
    if (!is_array($items) || !$items || count($items) > 20) {
        wp_send_json_error(array('message' => $message('购物袋数据无效。', 'The bag data is invalid.')), 400);
    }

    $desired = array();
    foreach ($items as $item) {
        $sku = isset($item['sku']) ? sanitize_text_field($item['sku']) : '';
        $quantity = isset($item['quantity']) ? absint($item['quantity']) : 0;
        $product_id = $sku ? wc_get_product_id_by_sku($sku) : 0;
        $product = $product_id ? wc_get_product($product_id) : false;
        if (!$product || !$product->is_purchasable() || !$product->is_in_stock() || $quantity < 1 || $quantity > 99 || ($product->is_sold_individually() && $quantity > 1) || !$product->has_enough_stock($quantity)) {
            wp_send_json_error(array('message' => $message('商品已下架或数量无效。', 'A product is unavailable or its quantity is invalid.')), 400);
        }
        $desired[$sku] = array('id' => $product_id, 'quantity' => isset($desired[$sku]) ? $desired[$sku]['quantity'] + $quantity : $quantity);
        if ($desired[$sku]['quantity'] > 99 || !$product->has_enough_stock($desired[$sku]['quantity'])) {
            wp_send_json_error(array('message' => $message('商品数量超出库存限制。', 'The requested quantity exceeds available stock.')), 400);
        }
    }

    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        $sku = isset($cart_item['data']) ? $cart_item['data']->get_sku() : '';
        if (!isset($desired[$sku])) {
            WC()->cart->remove_cart_item($key);
            continue;
        }
        if ((int) $cart_item['quantity'] !== $desired[$sku]['quantity']) {
            WC()->cart->set_quantity($key, $desired[$sku]['quantity'], false);
        }
        unset($desired[$sku]);
    }
    foreach ($desired as $item) {
        if (!WC()->cart->add_to_cart($item['id'], $item['quantity'])) {
            wp_send_json_error(array('message' => $message('商品无法加入购物车。', 'A product could not be added to the cart.')), 400);
        }
    }
    WC()->cart->calculate_totals();
    WC()->cart->set_session();
    wp_send_json_success(array('checkoutUrl' => wc_get_checkout_url()));
}
add_action('wp_ajax_nopriv_luxureat_checkout', 'luxureat_static_checkout_ajax');
add_action('wp_ajax_luxureat_checkout', 'luxureat_static_checkout_ajax');

function luxureat_static_account_language() {
    $language = isset($_GET['lang']) ? sanitize_key(wp_unslash($_GET['lang'])) : 'zh';
    return $language === 'en' ? 'en' : 'zh';
}

function luxureat_static_account_menu($items) {
    if (!is_user_logged_in() || current_user_can('manage_options')) {
        return $items;
    }

    $is_zh = luxureat_static_account_language() === 'zh';
    $labels = array(
        'orders' => $is_zh ? '订单' : 'Orders',
        'edit-address' => $is_zh ? '地址' : 'Addresses',
        'edit-account' => $is_zh ? '账户资料' : 'Account details',
        'customer-logout' => $is_zh ? '退出登录' : 'Log out',
    );

    return array_intersect_key($labels, $items);
}
add_filter('woocommerce_account_menu_items', 'luxureat_static_account_menu', 999);

function luxureat_static_account_endpoint_url($url) {
    return add_query_arg('lang', luxureat_static_account_language(), $url);
}
add_filter('woocommerce_get_endpoint_url', 'luxureat_static_account_endpoint_url');

function luxureat_static_account_dashboard() {
    $user = wp_get_current_user();
    $is_zh = luxureat_static_account_language() === 'zh';
    ?>
    <section class="lux-account-dashboard">
        <p class="lux-account-eyebrow"><?php echo esc_html($is_zh ? '欢迎回来' : 'Welcome back'); ?></p>
        <h2><?php echo esc_html($user->display_name ?: $user->user_login); ?></h2>
        <p><?php echo esc_html($is_zh ? '在这里查看订单、管理收货与账单地址，或更新账户资料。' : 'View your orders, manage shipping and billing addresses, or update your account details.'); ?></p>
    </section>
    <?php
}

function luxureat_static_replace_account_dashboard() {
    remove_action('woocommerce_account_dashboard', 'woocommerce_account_dashboard');
    add_action('woocommerce_account_dashboard', 'luxureat_static_account_dashboard');
}
add_action('wp_loaded', 'luxureat_static_replace_account_dashboard');

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
