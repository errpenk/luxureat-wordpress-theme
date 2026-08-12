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
        'about-us' => 'zh/about-us',
        'about-us.html' => 'zh/about-us',
        'journal' => 'zh/about-us',
        'journal.html' => 'zh/about-us',
        'product' => 'zh/product',
        'product.html' => 'zh/product',
        'caviar' => 'zh/product',
        'caviar.html' => 'zh/product',
        'recipe' => 'zh/recipe',
        'recipe.html' => 'zh/recipe',
        'rituals' => 'zh/recipe',
        'rituals.html' => 'zh/recipe',
        'brand' => 'zh/brand',
        'brand.html' => 'zh/brand',
        'news' => 'zh/brand',
        'news.html' => 'zh/brand',
        'blog' => 'zh/blog',
        'blog.html' => 'zh/blog',
        'new' => 'zh/new',
        'new.html' => 'zh/new',
        'cooperation' => 'zh/cooperation',
        'cooperation.html' => 'zh/cooperation',
        'gifting' => 'zh/cooperation',
        'gifting.html' => 'zh/cooperation',
        'certification' => 'zh/certification',
        'certification.html' => 'zh/certification',
        'contact' => 'zh/contact',
        'contact.html' => 'zh/contact',
        'bag' => 'zh/bag',
        'bag.html' => 'zh/bag',
        'en/journal' => 'en/about-us',
        'en/journal.html' => 'en/about-us',
        'en/products' => 'en/product',
        'en/products.html' => 'en/product',
        'en/caviar' => 'en/product',
        'en/caviar.html' => 'en/product',
        'en/rituals' => 'en/recipe',
        'en/rituals.html' => 'en/recipe',
        'en/news' => 'en/brand',
        'en/news.html' => 'en/brand',
        'en/new.html' => 'en/new',
        'en/gifting' => 'en/cooperation',
        'en/gifting.html' => 'en/cooperation',
        'en/private' => 'en/cooperation',
        'en/private.html' => 'en/cooperation',
        'private-selection' => 'en/cooperation',
        'private-selection.html' => 'en/cooperation',
        'product-imperial-beluga' => 'zh/product',
        'product-imperial-beluga.html' => 'zh/product',
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
        'zh/product' => '/product/',
        'zh/recipe' => '/recipe/',
        'zh/about-us' => '/about-us/',
        'zh/brand' => '/brand/',
        'zh/blog' => '/blog/',
        'zh/new' => '/new/',
        'zh/cooperation' => '/cooperation/',
        'zh/certification' => '/certification/',
        'zh/contact' => '/contact/',
        'zh/bag' => '/bag/',
        'en' => '/en/',
        'en/product' => '/en/product/',
        'en/recipe' => '/en/recipe/',
        'en/about-us' => '/en/about-us/',
        'en/brand' => '/en/brand/',
        'en/blog' => '/en/blog/',
        'en/new' => '/en/new/',
        'en/cooperation' => '/en/cooperation/',
        'en/certification' => '/en/certification/',
        'en/contact' => '/en/contact/',
        'en/bag' => '/en/bag/',
    );
}

function luxureat_static_url($path = 'zh', $suffix = '') {
    $path = luxureat_static_normalize_path($path);
    $suffix = is_string($suffix) ? $suffix : '';
    $pretty_paths = luxureat_static_pretty_paths();
    $home = untrailingslashit((string) get_option('home'));

    $route = isset($pretty_paths[$path])
        ? $pretty_paths[$path]
        : '/' . $path . '/';
    $url = $home . $route;

    return $url . $suffix;
}

function luxureat_static_current_path() {
    $query_path = get_query_var('luxureat_path');
    if (is_string($query_path) && $query_path !== '') {
        return luxureat_static_normalize_path($query_path);
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $request_path = parse_url($request_uri, PHP_URL_PATH);
    $home_path = parse_url((string) get_option('home'), PHP_URL_PATH);

    $request_path = is_string($request_path) ? $request_path : '/';
    $home_path = is_string($home_path) ? $home_path : '/';

    if ($home_path !== '/' && strpos($request_path, $home_path) === 0) {
        $request_path = substr($request_path, strlen($home_path));
    }

    return luxureat_static_normalize_path($request_path);
}

function luxureat_static_is_allowed_public_query($key, $route) {
    if (strpos($key, 'utm_') === 0 || strpos($key, 'attribute_') === 0) {
        return true;
    }

    if (in_array($key, array('gclid', 'dclid', 'fbclid', 'msclkid', '_gl', 'wc-ajax', 'add-to-cart', 'quantity', 'variation_id', '_wpnonce'), true)) {
        return true;
    }

    if (in_array($route, array('zh', 'en'), true) && in_array($key, array('account', 'luxureat_verify', 'user', 'token'), true)) {
        return true;
    }

    return in_array($route, array('zh/product', 'en/product'), true) && $key === 'category';
}

function luxureat_static_reject_noncanonical_requests() {
    if (
        is_admin()
        || is_user_logged_in()
        || (function_exists('wp_doing_ajax') && wp_doing_ajax())
        || (function_exists('wp_doing_cron') && wp_doing_cron())
    ) {
        return;
    }

    $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';
    if (!in_array($method, array('GET', 'HEAD'), true)) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $request_path = parse_url($request_uri, PHP_URL_PATH);
    $request_path = luxureat_static_normalize_path(is_string($request_path) ? $request_path : '');
    $gone_paths = array('product-category/uncategorized');
    $gone = in_array($request_path, $gone_paths, true);

    $route = $request_path === '' ? 'zh' : $request_path;
    $aliases = luxureat_static_aliases();
    if (isset($aliases[$route])) {
        $route = $aliases[$route];
    }
    $public_route = isset(luxureat_static_routes()[$route]);

    $raw_query = parse_url($request_uri, PHP_URL_QUERY);
    if (!$gone && $public_route && is_string($raw_query) && $raw_query !== '') {
        $decoded_query = rawurldecode($raw_query);
        $gone = preg_match('/^[A-Za-z]=[0-9]{7,}$/', $decoded_query) === 1
            || preg_match('#^[^=&]+/[^=&]+\.html(?:&.*)?$#i', $decoded_query) === 1;

        if (!$gone) {
            $query = array();
            wp_parse_str($raw_query, $query);
            foreach (array_keys($query) as $key) {
                if (!luxureat_static_is_allowed_public_query((string) $key, $route)) {
                    $gone = true;
                    break;
                }
            }
        }
    }

    if (!$gone) {
        return;
    }

    status_header(410);
    nocache_headers();
    header('X-Robots-Tag: noindex, nofollow', true);
    header('Content-Type: text/html; charset=UTF-8');
    if ($method !== 'HEAD') {
        echo '<!doctype html><html><head><meta charset="utf-8"><meta name="robots" content="noindex,nofollow"><title>Gone</title></head><body><h1>410 Gone</h1></body></html>';
    }
    exit;
}
add_action('template_redirect', 'luxureat_static_reject_noncanonical_requests', -200);

function luxureat_static_is_utility_page() {
    $path = luxureat_static_current_path();
    $aliases = luxureat_static_aliases();
    $route = isset($aliases[$path]) ? $aliases[$path] : $path;

    return in_array($route, array('zh/bag', 'en/bag'), true)
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_account_page') && is_account_page());
}

function luxureat_static_utility_noindex_header() {
    if (luxureat_static_is_utility_page()) {
        header('X-Robots-Tag: noindex, follow', true);
    }
}
add_action('template_redirect', 'luxureat_static_utility_noindex_header', -50);

function luxureat_static_utility_robots($robots) {
    if (luxureat_static_is_utility_page()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }
    return $robots;
}
add_filter('wp_robots', 'luxureat_static_utility_robots', 999);

function luxureat_static_search_metadata_endpoint() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
    $request_path = parse_url($request_uri, PHP_URL_PATH);
    $files = array(
        '/google053137c136af2773.html' => array('google053137c136af2773.html', 'text/html; charset=UTF-8'),
        '/sitemap.xml' => array('sitemap.xml', 'application/xml; charset=UTF-8'),
    );

    if (!isset($files[$request_path])) {
        return;
    }

    $file = get_template_directory() . '/' . $files[$request_path][0];
    if (!is_file($file) || !is_readable($file)) {
        return;
    }

    status_header(200);
    nocache_headers();
    header('Content-Type: ' . $files[$request_path][1]);
    readfile($file);
    exit;
}
add_action('template_redirect', 'luxureat_static_search_metadata_endpoint', -100);

function luxureat_static_robots_txt($output, $public) {
    $file = get_template_directory() . '/robots.txt';
    if (!is_file($file) || !is_readable($file)) {
        return $output;
    }

    $contents = file_get_contents($file);
    return is_string($contents) ? $contents : $output;
}
add_filter('robots_txt', 'luxureat_static_robots_txt', 999, 2);

function luxureat_baidu_site_verification() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
    if (parse_url($request_uri, PHP_URL_PATH) !== '/baidu_verify_codeva-unoYAk5W8p.html') {
        return;
    }

    status_header(200);
    nocache_headers();
    header('Content-Type: text/html; charset=UTF-8');
    echo '6c9c028426f2f70621969ba37ffb0ae3';
    exit;
}
add_action('template_redirect', 'luxureat_baidu_site_verification', -100);

function luxureat_static_seo_catalog() {
    return array(
        'zh' => array('title' => 'LuxurEat（露意膳） | 意大利高端美食与品牌合作', 'description' => 'LuxurEat（露意膳）专注意大利高端美食、地道风味与中国市场合作，提供产品、食谱、品牌内容与专业服务。', 'lang' => 'zh', 'alternate' => 'en', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/about-us' => array('title' => '关于我们 | LuxurEat（露意膳）', 'description' => '了解 LuxurEat（露意膳）的品牌传承、产地故事、品质承诺与时令观察。', 'lang' => 'zh', 'alternate' => 'en/about-us', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/product' => array('title' => '系列产品 | LuxurEat（露意膳）', 'description' => '浏览 LuxurEat（露意膳）精选意大利高端美食与松露、鱼子酱等系列产品。', 'lang' => 'zh', 'alternate' => 'en/product', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/new' => array('title' => '热门新品 | LuxurEat（露意膳）', 'description' => '发现 LuxurEat（露意膳）的意大利橄榄油、披萨与意式手工冰淇淋等热门新品。', 'lang' => 'zh', 'alternate' => 'en/new', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/recipe' => array('title' => '食谱艺术 | LuxurEat（露意膳）', 'description' => '从意大利地域风味到家庭餐桌，探索 LuxurEat（露意膳）的中英文食谱与烹饪灵感。', 'lang' => 'zh', 'alternate' => 'en/recipe', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/brand' => array('title' => '品牌新闻 | LuxurEat（露意膳）', 'description' => '查看 LuxurEat（露意膳）最新品牌活动、行业展会、合作动态与现场故事。', 'lang' => 'zh', 'alternate' => 'en/brand', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/blog' => array('title' => '知识博客 | LuxurEat（露意膳）', 'description' => '系统探索松露学院、意大利美食词典、生产者与产地故事，以及鱼子酱、橄榄油等意大利食材知识。', 'lang' => 'zh', 'alternate' => 'en/blog', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/certification' => array('title' => '品质认证 | LuxurEat（露意膳）', 'description' => '了解 LuxurEat（露意膳）的产地证明、质量体系、认证文件、责任贸易与合作项目。', 'lang' => 'zh', 'alternate' => 'en/certification', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/cooperation' => array('title' => '商务合作 | LuxurEat（露意膳）', 'description' => '探索 LuxurEat（露意膳）的国际市场定制、品牌合作、渠道方案与中国市场服务。', 'lang' => 'zh', 'alternate' => 'en/cooperation', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/contact' => array('title' => '联系我们 | LuxurEat（露意膳）', 'description' => '联系 LuxurEat（露意膳），咨询品牌、产品、渠道与商务合作。', 'lang' => 'zh', 'alternate' => 'en/contact', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'zh/bag' => array('title' => '购物袋 | LuxurEat（露意膳）', 'description' => '查看并管理您在 LuxurEat（露意膳）购物袋中的已选产品。', 'lang' => 'zh', 'alternate' => 'en/bag', 'indexable' => false, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en' => array('title' => 'LuxurEat | Premium Italian Food & Brand Partnerships', 'description' => 'Discover premium Italian food, authentic regional flavours, recipes, brand stories and professional market partnerships from LuxurEat.', 'lang' => 'en', 'alternate' => 'zh', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/about-us' => array('title' => 'About Us | LuxurEat', 'description' => 'Discover LuxurEat\'s brand heritage, stories of place, quality promise and seasonal observations.', 'lang' => 'en', 'alternate' => 'zh/about-us', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/product' => array('title' => 'Products | LuxurEat', 'description' => 'Browse LuxurEat\'s selected premium Italian foods, truffle products, caviar and culinary collections.', 'lang' => 'en', 'alternate' => 'zh/product', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/new' => array('title' => 'New Arrivals | LuxurEat', 'description' => 'Discover new Italian olive oil, pizza and artisan Gelato selections from LuxurEat.', 'lang' => 'en', 'alternate' => 'zh/new', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/recipe' => array('title' => 'Recipe Art | LuxurEat', 'description' => 'Explore bilingual Italian recipes, regional flavours and practical inspiration for the family table from LuxurEat.', 'lang' => 'en', 'alternate' => 'zh/recipe', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/brand' => array('title' => 'Brand News | LuxurEat', 'description' => 'Read the latest LuxurEat events, trade exhibitions, partnerships and stories from the field.', 'lang' => 'en', 'alternate' => 'zh/brand', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/blog' => array('title' => 'Knowledge Blog | LuxurEat', 'description' => 'Explore the Truffle Academy, Italian Food Dictionary, producers and stories of place, caviar, olive oil and Italian craft.', 'lang' => 'en', 'alternate' => 'zh/blog', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/certification' => array('title' => 'Quality & Certification | LuxurEat', 'description' => 'Explore LuxurEat\'s origin records, quality systems, certification documents, responsible trade and partnership projects.', 'lang' => 'en', 'alternate' => 'zh/certification', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/cooperation' => array('title' => 'Cooperation | LuxurEat', 'description' => 'Explore LuxurEat private label, brand partnerships, channel solutions and services for the China market.', 'lang' => 'en', 'alternate' => 'zh/cooperation', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/contact' => array('title' => 'Contact | LuxurEat', 'description' => 'Contact LuxurEat for brand, product, distribution and business partnership enquiries.', 'lang' => 'en', 'alternate' => 'zh/contact', 'indexable' => true, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'en/bag' => array('title' => 'Shopping Bag | LuxurEat', 'description' => 'Review and manage the products selected in your LuxurEat shopping bag.', 'lang' => 'en', 'alternate' => 'zh/bag', 'indexable' => false, 'type' => 'WebPage', 'image' => 'media/brand/home-hero-truffle-poster.webp'),
        'blog/baerii-caviar' => array('title' => '贝氏鲟鱼子酱 | LuxurEat', 'description' => '贝氏鲟鱼子酱取自西伯利亚鲟（Acipenser baerii），以灰棕色小颗粒和带有轻柔碘感的细腻风味著称。', 'lang' => 'zh', 'alternate' => 'en/blog/baerii-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/baerii-caviar-cover-page-bg.png'),
        'en/blog/baerii-caviar' => array('title' => 'Baerii Caviar | LuxurEat', 'description' => 'Baerii caviar, obtained from the Acipenser baerii sturgeon, is appreciated for its small grey-brown eggs and delicate flavor with light iodine notes.', 'lang' => 'en', 'alternate' => 'blog/baerii-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/baerii-caviar-cover-page-bg.png'),
        'blog/beluga-caviar' => array('title' => '贝鲁迦鱼子酱 | LuxurEat', 'description' => '贝鲁迦鱼子酱（Beluga caviar）被视为最奢华、最稀有的鱼子酱之一，取自欧鳇（Beluga sturgeon，Huso huso），以硕大的灰色颗粒和细腻、奶油般的口感闻名。', 'lang' => 'zh', 'alternate' => 'en/blog/beluga-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/beluga-caviar-cover-new-page-bg.png'),
        'en/blog/beluga-caviar' => array('title' => 'Beluga Caviar | LuxurEat', 'description' => 'Beluga caviar, considered the most luxurious and rare, comes from the Huso huso sturgeon and is known for its large grey eggs and delicate, buttery taste.', 'lang' => 'en', 'alternate' => 'blog/beluga-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/beluga-caviar-cover-new-page-bg.png'),
        'blog/kaluga-amur-caviar' => array('title' => '卡露伽与阿穆尔鱼子酱 | LuxurEat', 'description' => '卡露伽与阿穆尔鱼子酱被视为珍贵贝鲁迦鱼子酱（Beluga caviar）的优质替代选择，主要优势在于价格更亲民。卡露伽鲟仅栖息于中俄之间的黑龙江及其支流。', 'lang' => 'zh', 'alternate' => 'en/blog/kaluga-amur-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/kaluga-amur-caviar-cover-page-bg.png'),
        'en/blog/kaluga-amur-caviar' => array('title' => 'Kaluga and Amur Caviar | LuxurEat', 'description' => 'Kaluga and Amur caviar is considered a fine alternative to the prestigious Beluga, distinguished mainly by its lower cost. The Kaluga sturgeon inhabits only the Amur River and its tributaries, located between Russia and China.', 'lang' => 'en', 'alternate' => 'blog/kaluga-amur-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/kaluga-amur-caviar-cover-page-bg.png'),
        'blog/oscetra-caviar' => array('title' => '奥西特拉鱼子酱 | LuxurEat', 'description' => '奥西特拉鱼子酱的颗粒色泽从灰色延伸至金色，质地丝滑，风味优雅，并带有榛子与黄油气息。', 'lang' => 'zh', 'alternate' => 'en/blog/oscetra-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/oscetra-caviar-cover.png'),
        'en/blog/oscetra-caviar' => array('title' => 'Oscetra Caviar | LuxurEat', 'description' => 'Oscetra caviar is distinguished by its eggs ranging in color from grey to golden, with a silky texture and an elegant flavor featuring notes of hazelnut and butter.', 'lang' => 'en', 'alternate' => 'blog/oscetra-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/oscetra-caviar-cover.png'),
        'blog/sevruga-caviar' => array('title' => '闪光鲟鱼子酱 | LuxurEat', 'description' => '闪光鲟鱼子酱（Sevruga caviar）取自闪光鲟（stellate sturgeon，Acipenser stellatus），其鱼卵是主要鱼子酱品种中最小的，直径约2毫米，色泽均匀、质地紧实、风味浓郁。', 'lang' => 'zh', 'alternate' => 'en/blog/sevruga-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/sevruga-caviar-cover-page-bg.png'),
        'en/blog/sevruga-caviar' => array('title' => 'Sevruga Caviar (Acipenser stellatus) | LuxurEat', 'description' => 'Sevruga sturgeon roe are the smallest among all caviar varieties, with a diameter of about 2 mm. They feature a uniform color, compact texture, and intense flavor.', 'lang' => 'en', 'alternate' => 'blog/sevruga-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/sevruga-caviar-cover-page-bg.png'),
        'blog/white-sturgeon-caviar' => array('title' => '白鲟鱼子酱 | LuxurEat', 'description' => '白鲟原生于北美西海岸，其鱼子酱以较大颗粒、奶油般质地和细腻风味受到珍视。', 'lang' => 'zh', 'alternate' => 'en/blog/white-sturgeon-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/white-sturgeon-caviar-cover-page-bg.png'),
        'en/blog/white-sturgeon-caviar' => array('title' => 'White Sturgeon Caviar | LuxurEat', 'description' => 'White sturgeon caviar, native to the west coast of North America, is prized for its large eggs, creamy texture, and delicate flavor.', 'lang' => 'en', 'alternate' => 'blog/white-sturgeon-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/white-sturgeon-caviar-cover-page-bg.png'),
        'blog/history-of-caviar' => array('title' => '鱼子酱的历史 | LuxurEat', 'description' => '鱼子酱的历史可追溯至古代波斯与俄罗斯。“caviar”一词源自波斯语“khavyar”，意为“鱼卵”。', 'lang' => 'zh', 'alternate' => 'en/blog/history-of-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/history-of-caviar-cover.webp'),
        'en/blog/history-of-caviar' => array('title' => 'History of Caviar | LuxurEat', 'description' => 'The history of caviar traces back to ancient Persia and Russia. The word “caviar” comes from the Persian “khavyar”, meaning “egg.”', 'lang' => 'en', 'alternate' => 'blog/history-of-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/history-of-caviar-cover.webp'),
        'blog/main-types-of-caviar' => array('title' => '鱼子酱的主要种类 | LuxurEat', 'description' => '主要鱼子酱品种包括欧鳇、奥西特拉、贝氏鲟、卡露伽、闪光鲟与白鲟，它们各自拥有不同的产地与感官特点。', 'lang' => 'zh', 'alternate' => 'en/blog/main-types-of-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/main-types-of-caviar-cover.webp'),
        'en/blog/main-types-of-caviar' => array('title' => 'Main Types of Caviar | LuxurEat', 'description' => 'The main types of caviar include Beluga, Oscetra, Baerii, Kaluga, Sevruga, and White Sturgeon, each with distinct characteristics and origins.', 'lang' => 'en', 'alternate' => 'blog/main-types-of-caviar', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/main-types-of-caviar-cover.webp'),
        'blog/caviar-processing' => array('title' => '鱼子酱加工工艺 | LuxurEat', 'description' => '鱼子酱加工全程以手工完成，从鱼卵甄选、Malossol轻盐法，到装罐与熟成都需要精确控制。', 'lang' => 'zh', 'alternate' => 'en/blog/caviar-processing', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/caviar-processing-cover.webp'),
        'en/blog/caviar-processing' => array('title' => 'Caviar Processing | LuxurEat', 'description' => 'Caviar processing is carried out by hand — from the selection of roe to the Malossol salting method, followed by packaging and maturation.', 'lang' => 'en', 'alternate' => 'blog/caviar-processing', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/caviar-processing-cover.webp'),
        'blog/olive-oil-basics' => array('title' => '什么是特级初榨橄榄油？ | LuxurEat', 'description' => '从法定等级、机械加工、感官评价到日常使用，建立判断特级初榨橄榄油的完整基础。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-oil-basics', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-01.webp'),
        'blog/olive-regions' => array('title' => '从产区到风味：意大利橄榄油的区域差异 | LuxurEat', 'description' => '从气候、地形与本地品种出发，理解意大利橄榄油为何不存在一种统一的“标准味道”。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-regions', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-02.webp'),
        'blog/olive-tasting' => array('title' => '如何品鉴、保存和使用橄榄油 | LuxurEat', 'description' => '用可重复的闻香、入口与记录方法品鉴橄榄油，并把保存和烹饪纳入同一套判断。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-tasting', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-03.webp'),
        'blog/gelato-vs-ice-cream' => array('title' => '意式手工冰淇淋与普通冰淇淋：从名字到口感的区别 | LuxurEat', 'description' => '从配方、空气、冻结、服务温度与门店文化，理解意式手工冰淇淋和普通冰淇淋的常见差异。', 'lang' => 'zh', 'alternate' => 'en/blog/gelato-vs-ice-cream', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-01.webp'),
        'blog/gelato-history' => array('title' => '意式手工冰淇淋：从城市传统到现代工艺 | LuxurEat', 'description' => '从意大利冷冻甜品传统、城市门店到现代设备，理解意式手工冰淇淋如何成为日常文化与专业工艺。', 'lang' => 'zh', 'alternate' => 'en/blog/gelato-history', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-02.webp'),
        'blog/ugolini-gelato-mix' => array('title' => '新品：Luxureat（露意膳）意式手工冰淇淋粉末基底即将上线 | LuxurEat', 'description' => 'Luxureat（露意膳）全新意式手工冰淇淋粉末基底系列即将推出，面向家庭、店铺及食品业客户，提供更灵活、便捷的意式冰淇淋制作选择。具体口味、规格及上市信息，请以最终产品资料为准。', 'lang' => 'zh', 'alternate' => 'en/blog/ugolini-gelato-mix', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-03.webp'),
        'en/blog/olive-oil-basics' => array('title' => 'What is extra virgin olive oil? | LuxurEat', 'description' => 'From legal grade, mechanical processing, sensory evaluation to daily use, establish a complete basis for judging extra virgin olive oil.', 'lang' => 'en', 'alternate' => 'blog/olive-oil-basics', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-01.webp'),
        'en/blog/olive-regions' => array('title' => 'From Region to Flavour: Italy\'s Olive-Oil Landscapes | LuxurEat', 'description' => 'Starting from the climate, topography and local varieties, understand why there is no unified "standard taste" for Italian olive oil.', 'lang' => 'en', 'alternate' => 'blog/olive-regions', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-02.webp'),
        'en/blog/olive-tasting' => array('title' => 'How to Taste, Store and Use Olive Oil | LuxurEat', 'description' => 'Taste olive oil with repeatable methods of smelling, tasting and recording, and integrating preservation and cooking into the same set of judgments.', 'lang' => 'en', 'alternate' => 'blog/olive-tasting', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-03.webp'),
        'en/blog/gelato-vs-ice-cream' => array('title' => 'Italian Gelato vs Ice Cream: From Definition to Texture | LuxurEat', 'description' => 'Understand the common differences between Italian gelato and regular ice cream from recipe, air, freezing, serving temperature and store culture.', 'lang' => 'en', 'alternate' => 'blog/gelato-vs-ice-cream', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-01.webp'),
        'en/blog/gelato-history' => array('title' => 'Italian Gelato: From Urban Tradition to Modern Craft | LuxurEat', 'description' => 'From Italian frozen dessert traditions to urban stores to modern equipment, understand how Italian gelato has become an everyday culture and a professional craft.', 'lang' => 'en', 'alternate' => 'blog/gelato-history', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-02.webp'),
        'en/blog/ugolini-gelato-mix' => array('title' => 'Coming Soon: LuxurEat (露意膳) Italian Gelato Base Mix | LuxurEat', 'description' => 'LuxurEat (露意膳)\'s new Italian gelato powder base series will be launched soon, providing more flexible and convenient gelato making options for households, shops and food industry customers. For specific flavours, specifications and listing information, please refer to the final product information.', 'lang' => 'en', 'alternate' => 'blog/ugolini-gelato-mix', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-03.webp'),
        'blog/italian-food-culture' => array('title' => '意大利美食，不止一种味道 | LuxurEat', 'description' => '从山地、平原、海岸与岛屿出发，理解意大利饮食为何始终与地域、季节、家庭和地方生产相连。', 'lang' => 'zh', 'alternate' => 'en/blog/italian-food-culture', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-01.webp'),
        'en/blog/italian-food-culture' => array('title' => 'Italian food is more than one flavour | LuxurEat', 'description' => 'A starting point for understanding Italy through climate, landscape, history and the family table.', 'lang' => 'en', 'alternate' => 'blog/italian-food-culture', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-01.webp'),
        'blog/italy-regions' => array('title' => '意大利二十个大区：一张餐桌地图 | LuxurEat', 'description' => '从阿尔卑斯山到地中海岛屿，用二十个大区建立餐桌坐标，而不是用简单的南北二分概括意大利。', 'lang' => 'zh', 'alternate' => 'en/blog/italy-regions', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-02.webp'),
        'en/blog/italy-regions' => array('title' => 'Italy\'s twenty regions: a map of the table | LuxurEat', 'description' => 'From Piedmont to Sicily, twenty regions carry distinct climates, ingredients, traditions and table languages.', 'lang' => 'en', 'alternate' => 'blog/italy-regions', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-02.webp'),
        'blog/regional-traditions' => array('title' => '地方风味：传统如何留在日常餐桌 | LuxurEat', 'description' => '从季节、保存、节庆和家庭实践出发，理解地方传统为何会变化，却仍能保持清晰的文化连续性。', 'lang' => 'zh', 'alternate' => 'en/blog/regional-traditions', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-03.webp'),
        'en/blog/regional-traditions' => array('title' => 'Regional flavour: how tradition stays on the table | LuxurEat', 'description' => 'Italian food is not only a list of dishes; festivals, seasons and family habits shape how flavour is made and shared.', 'lang' => 'en', 'alternate' => 'blog/regional-traditions', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-03.webp'),
        'blog/ingredients-territory' => array('title' => '从产地到餐桌：读懂一份意大利食材 | LuxurEat', 'description' => '用产地、品种、生产、标签、保存和使用六组信息，建立一套可核对的意大利食材阅读方法。', 'lang' => 'zh', 'alternate' => 'en/blog/ingredients-territory', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-04.webp'),
        'en/blog/ingredients-territory' => array('title' => 'From territory to table: reading an Italian ingredient | LuxurEat', 'description' => 'A useful ingredient profile connects origin, variety, processing, storage, flavour and use.', 'lang' => 'en', 'alternate' => 'blog/ingredients-territory', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-04.webp'),
        'blog/table-etiquette' => array('title' => '意大利餐桌礼仪：慢下来，分享风味 | LuxurEat', 'description' => '餐桌礼仪不是僵硬规则，而是通过节奏、份量、分享和交谈，让食物与共同用餐得到应有的注意。', 'lang' => 'zh', 'alternate' => 'en/blog/table-etiquette', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-05.webp'),
        'en/blog/table-etiquette' => array('title' => 'Italian table culture: slow down and share flavour | LuxurEat', 'description' => 'Table etiquette is not about distance; it gives food, time and conversation the attention they deserve.', 'lang' => 'en', 'alternate' => 'blog/table-etiquette', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-05.webp'),
        'blog/cooking-techniques' => array('title' => '意大利料理的基础技法 | LuxurEat', 'description' => '从火候、乳化、烘烤和静置理解基础技法：少依赖堆叠调味，多观察原料在温度与时间中的变化。', 'lang' => 'zh', 'alternate' => 'en/blog/cooking-techniques', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-06.webp'),
        'en/blog/cooking-techniques' => array('title' => 'Fundamental techniques of Italian cooking | LuxurEat', 'description' => 'Temperature, emulsion, reduction and resting determine whether an ingredient keeps a clear flavour.', 'lang' => 'en', 'alternate' => 'blog/cooking-techniques', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-06.webp'),
        'blog/pasta-academy' => array('title' => '意大利面：形状、酱汁与火候 | LuxurEat', 'description' => '从原料与形状出发，理解煮面水、酱汁浓度和锅中收尾如何共同决定一盘意大利面的完整度。', 'lang' => 'zh', 'alternate' => 'en/blog/pasta-academy', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-07.webp'),
        'en/blog/pasta-academy' => array('title' => 'Pasta: shape, sauce and timing | LuxurEat', 'description' => 'Pasta is not one product; shape, dough, sauce and cooking method together define the final bite.', 'lang' => 'en', 'alternate' => 'blog/pasta-academy', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-07.webp'),
        'blog/pairings-class' => array('title' => '风味搭配：从质地到味觉的平衡 | LuxurEat', 'description' => '真正出色的搭配，不在于堆叠昂贵食材，而在于让香气、质地、酸度、盐度与温度彼此协调，相互成就。', 'lang' => 'zh', 'alternate' => 'en/blog/pairings-class', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-08.webp'),
        'en/blog/pairings-class' => array('title' => 'Pairing: start with texture, then intensity | LuxurEat', 'description' => 'Good pairing is not stacking expensive ingredients; it balances aroma, texture, acidity, salt, sweetness and temperature.', 'lang' => 'en', 'alternate' => 'blog/pairings-class', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/culture-cover-08.webp'),
        'blog/olive-nutrition' => array('title' => '橄榄油的营养价值：从份量到饮食搭配 | LuxurEat', 'description' => '认识橄榄油中的脂肪类型与能量密度，把营养数字放回份量、替代关系和整体饮食。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-nutrition', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-04.webp'),
        'en/blog/olive-nutrition' => array('title' => 'Olive-Oil Nutrition: From Portions to a Balanced Diet | LuxurEat', 'description' => 'Understand the fat types and energy density in olive oil, and put the nutritional numbers back into portion sizes, substitution relationships, and the overall diet.', 'lang' => 'en', 'alternate' => 'blog/olive-nutrition', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-04.webp'),
        'blog/olive-cultivars' => array('title' => '品种如何改变橄榄油的香气 | LuxurEat', 'description' => '从品种差异理解青草、番茄、果仁与辛香的来源，同时避免把品种名称当成固定风味公式。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-cultivars', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-05.webp'),
        'en/blog/olive-cultivars' => array('title' => 'How Cultivars Shape Olive-Oil Aroma | LuxurEat', 'description' => 'Understand the origins of grass, tomatoes, nuts and spices from variety differences, and avoid using variety names as fixed flavour formulas.', 'lang' => 'en', 'alternate' => 'blog/olive-cultivars', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-05.webp'),
        'blog/olive-how-to-choose' => array('title' => '买橄榄油时，先看这些信息 | LuxurEat', 'description' => '把等级、产地、日期、包装与用途放进同一张检查清单，减少只看价格和营销词的误判。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-how-to-choose', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-06.webp'),
        'en/blog/olive-how-to-choose' => array('title' => 'Choosing Olive Oil: What to Check First | LuxurEat', 'description' => 'Put grade, origin, date, packaging and use into the same checklist to reduce misjudgments based only on price and marketing words.', 'lang' => 'en', 'alternate' => 'blog/olive-how-to-choose', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-06.webp'),
        'blog/olive-storage' => array('title' => '让橄榄油保持风味：光、热与时间 | LuxurEat', 'description' => '理解光、热、氧与时间如何削弱橄榄油风味，并为家庭和餐饮场景建立可执行的保存方法。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-storage', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-07.webp'),
        'en/blog/olive-storage' => array('title' => 'Keeping Olive Oil Fresh: Light, Heat and Time | LuxurEat', 'description' => 'Understand how light, heat, oxygen, and time weaken olive oil flavour and establish actionable preservation methods for home and restaurant settings.', 'lang' => 'en', 'alternate' => 'blog/olive-storage', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-07.webp'),
        'blog/olive-fasting' => array('title' => '空腹喝橄榄油：营养价值、正确方法与常见误区 | LuxurEat', 'description' => '从能量、耐受性与饮食结构解释空腹喝橄榄油，区分个人习惯、营养事实和未经证实的功效。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-fasting', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-08.webp'),
        'en/blog/olive-fasting' => array('title' => 'Olive Oil on an Empty Stomach: Nutrition, Tolerance and Myths | LuxurEat', 'description' => 'Explain drinking olive oil on an empty stomach from the perspective of energy, tolerance and dietary structure, distinguishing personal habits, nutritional facts and unproven effects.', 'lang' => 'en', 'alternate' => 'blog/olive-fasting', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-08.webp'),
        'blog/olive-myths' => array('title' => '关于橄榄油，先放下这几种误区 | LuxurEat', 'description' => '澄清酸度、颜色、加热、苦辣和产地等常见误区，用可验证信息替代简单口号。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-myths', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-09.webp'),
        'en/blog/olive-myths' => array('title' => 'Olive Oil: Six Myths to Leave Behind | LuxurEat', 'description' => 'Clarify common myths about acidity, colour, heating, bitterness and origin, and replace simple slogans with verifiable information.', 'lang' => 'en', 'alternate' => 'blog/olive-myths', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-09.webp'),
        'blog/olive-recipes-guide' => array('title' => '橄榄油进入日常：从面包到意面 | LuxurEat', 'description' => '从面包、沙拉、豆类到意面和烤制，理解橄榄油在调味、传热、乳化与完成菜中的不同角色。', 'lang' => 'zh', 'alternate' => 'en/blog/olive-recipes-guide', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/choose-use-store-evo.webp'),
        'en/blog/olive-recipes-guide' => array('title' => 'Olive Oil in Everyday Cooking: From Bread to Pasta | LuxurEat', 'description' => 'From breads, salads, and beans to pasta and grilling, understand the different roles olive oil plays in seasoning, transferring heat, emulsifying, and finishing dishes.', 'lang' => 'en', 'alternate' => 'blog/olive-recipes-guide', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/choose-use-store-evo.webp'),
        'blog/pizza-fundamentals' => array('title' => '一张意式披萨，从面团开始 | LuxurEat', 'description' => '从面粉、含水、发酵、整形到炉温，理解一张意式披萨如何获得轻盈饼边、柔韧中心与清晰配料。', 'lang' => 'zh', 'alternate' => 'en/blog/pizza-fundamentals', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/pizza-cover-01.webp'),
        'en/blog/pizza-fundamentals' => array('title' => 'Italian Pizza Begins with the Dough | LuxurEat', 'description' => 'From flour, water content, fermentation, shaping to oven temperature, understand how an Italian pizza obtains light edges, a flexible center and clear toppings.', 'lang' => 'en', 'alternate' => 'blog/pizza-fundamentals', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/pizza-cover-01.webp'),
        'blog/gelato-flavours' => array('title' => '经典口味：意式手工冰淇淋如何表达原料 | LuxurEat', 'description' => '从牛奶、香草、巧克力、咖啡、榛子、开心果和水果出发，理解不同原料如何改变甜度、质地与香气。', 'lang' => 'zh', 'alternate' => 'en/blog/gelato-flavours', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-04.webp'),
        'en/blog/gelato-flavours' => array('title' => 'Classic Flavours: How Italian Gelato Expresses Its Ingredients | LuxurEat', 'description' => 'Start with milk, vanilla, chocolate, coffee, hazelnuts, pistachios and fruits to understand how different ingredients change sweetness, texture and aroma.', 'lang' => 'en', 'alternate' => 'blog/gelato-flavours', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/gelato-cover-04.webp'),
        'blog/nutrition-labels' => array('title' => '读懂一张食品配料表 | LuxurEat', 'description' => '按食品名称、配料、营养、致敏物质、日期、储存与责任主体的顺序，读懂一张预包装食品标签。', 'lang' => 'zh', 'alternate' => 'en/blog/nutrition-labels', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-01.webp'),
        'en/blog/nutrition-labels' => array('title' => 'How to Read a Food Ingredient List | LuxurEat', 'description' => 'Read a prepackaged food label in the order of food name, ingredients, nutrition, allergens, date, storage and responsible party.', 'lang' => 'en', 'alternate' => 'blog/nutrition-labels', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-01.webp'),
        'blog/allergens-guide' => array('title' => '过敏原信息：按产品逐项确认 | LuxurEat', 'description' => '区分配方含有、交叉接触与个人风险，按产品和批次核对致敏物质，而不是用全系列口号代替标签。', 'lang' => 'zh', 'alternate' => 'en/blog/allergens-guide', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-02.webp'),
        'en/blog/allergens-guide' => array('title' => 'Allergen Information: Verify Product by Product | LuxurEat', 'description' => 'Distinguish between formula content, cross-contact and individual risks, and check allergens by product and batch, rather than replacing labels with a full range of slogans.', 'lang' => 'en', 'alternate' => 'blog/allergens-guide', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-02.webp'),
        'blog/clean-label' => array('title' => '无添加标签：先问清楚没有添加什么 | LuxurEat', 'description' => '“无添加”只有在对象、范围和证据明确时才有意义，它不自动等于更天然、更安全或营养更好。', 'lang' => 'zh', 'alternate' => 'en/blog/clean-label', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-03.webp'),
        'en/blog/clean-label' => array('title' => 'No-Additives Claims: First Ask What Is Not Added | LuxurEat', 'description' => '“No-additives” only makes sense when the object, scope and evidence are clear; it does not automatically equate to more natural, safer or nutritionally better.', 'lang' => 'en', 'alternate' => 'blog/clean-label', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-03.webp'),
        'blog/nutrition-myths' => array('title' => '营养信息：把事实、个人感受和宣传分开 | LuxurEat', 'description' => '用份量、证据等级、适用人群和整体饮食审视营养信息，避免把个人体验或营销语言当成普遍事实。', 'lang' => 'zh', 'alternate' => 'en/blog/nutrition-myths', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-04.webp'),
        'en/blog/nutrition-myths' => array('title' => 'Nutrition Information: Separate Facts, Experience and Marketing | LuxurEat', 'description' => 'Review nutritional information in terms of portion size, level of evidence, intended populations and the overall diet, and avoid taking personal experience or marketing language as universal truth.', 'lang' => 'en', 'alternate' => 'blog/nutrition-myths', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/nutrition-cover-04.webp'),
        'en/blog/italian-gelato-vs-ice-cream' => array('title' => 'Italian Gelato vs Ice Cream: What Makes the Italian Tradition Different? | LuxurEat', 'description' => 'Gelato and ice cream belong to the same broad family of frozen desserts, but traditional Italian gelato is shaped by a different balance of air, fat, serving temperature and flavour. The result is not simply a different recipe, but a different way of experiencing a frozen dessert.', 'lang' => 'en', 'alternate' => 'blog/italian-gelato-vs-ice-cream', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/italian-gelato-vs-ice-cream.webp'),
        'en/blog/story-of-italian-gelato' => array('title' => 'The Story of Italian Gelato: From Frozen Desserts to the Gelateria | LuxurEat', 'description' => 'The history of gelato is not the story of a single invention. It is a gradual evolution in which older frozen preparations, Italian court culture, technical experimentation and artisan practice eventually came together in the modern gelateria.', 'lang' => 'en', 'alternate' => 'blog/story-of-italian-gelato', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/story-of-italian-gelato.webp'),
        'en/blog/truffle-meets-dessert' => array('title' => 'Truffle Beyond Savoury Cuisine: When Truffle Meets Dessert | LuxurEat', 'description' => 'Truffle is usually associated with pasta, risotto, eggs and cheese. Yet truffle is above all an aromatic ingredient, and aroma can interact with sweetness, dairy, chocolate and other dessert elements in surprisingly coherent ways.', 'lang' => 'en', 'alternate' => 'blog/truffle-meets-dessert', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-meets-dessert.webp'),
        'en/blog/evo-vs-common-cooking-oil' => array('title' => 'Italian Extra Virgin Olive Oil vs Common Cooking Oil: What Makes EVO Different? | LuxurEat', 'description' => '“Olive oil” is not one single product category. Extra Virgin Olive Oil (EVO) is defined by how it is obtained and by chemical and sensory criteria, while refined oils and olive-pomace oils are produced through different processes and have different characteristics.', 'lang' => 'en', 'alternate' => 'blog/evo-vs-common-cooking-oil', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/evo-vs-common-cooking-oil.webp'),
        'en/blog/story-of-italian-evo' => array('title' => 'The Story of Italian Extra Virgin Olive Oil: From Mediterranean Staple to Regional Craft | LuxurEat', 'description' => 'Olive oil predates modern Italy by centuries, but the Italian peninsula developed an unusually rich regional culture around olive growing, milling, cooking and tasting. Today’s EVO is the modern expression of that long agricultural and culinary history.', 'lang' => 'en', 'alternate' => 'blog/story-of-italian-evo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/story-of-italian-evo.webp'),
        'en/blog/evo-chocolate-dessert' => array('title' => 'Extra Virgin Olive Oil Beyond the Salad: When EVO Meets Chocolate and Dessert | LuxurEat', 'description' => 'Extra Virgin Olive Oil is usually introduced through savoury dishes, but its fruitiness, bitterness and aroma can also create sophisticated contrasts with chocolate, cake and gelato. The key is choosing the right oil and using it with restraint.', 'lang' => 'en', 'alternate' => 'blog/evo-chocolate-dessert', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/evo-chocolate-dessert.webp'),
        'en/blog/choose-use-store-evo' => array('title' => 'How to Choose, Use and Store Italian Extra Virgin Olive Oil at Home | LuxurEat', 'description' => 'Choosing a good EVO does not require professional tasting training. A few practical habits - reading the category correctly, checking origin information, protecting the oil from light and heat, and matching intensity to the dish - make a much bigger difference than judging the bottle by colour alone.', 'lang' => 'en', 'alternate' => 'blog/choose-use-store-evo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-10.webp'),
        'en/blog/neapolitan-roman-pizza-styles' => array('title' => 'Pizza Napoletana and Roman Pizza Styles: Why “Italian Pizza” Is Not One Thing | LuxurEat', 'description' => 'Outside Italy, pizza is often treated as one universal dish. Inside Italy, dough texture, baking method, shape and eating occasion can change dramatically from one city and style to another. Naples and Rome offer two of the clearest examples.', 'lang' => 'en', 'alternate' => 'blog/neapolitan-roman-pizza-styles', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/neapolitan-roman-pizza-styles.webp'),
        'en/blog/story-of-italian-pizza' => array('title' => 'The Story of Italian Pizza: From Naples to a Global Icon | LuxurEat', 'description' => 'Pizza became one of the world’s most recognisable foods, but its modern identity is rooted in Naples and in the everyday food culture of the eighteenth and nineteenth centuries. Its global success came later - through migration, adaptation and the international appeal of a simple, flexible format.', 'lang' => 'en', 'alternate' => 'blog/story-of-italian-pizza', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/story-of-italian-pizza.webp'),
        'en/blog/modern-pinsa-romana' => array('title' => 'Beyond Classic Pizza: Discovering Modern Pinsa Romana | LuxurEat', 'description' => 'Pinsa Romana is best understood as a modern Roman baking innovation rather than an ancient recipe revived unchanged from antiquity. Its identity comes from a distinctive dough concept, an elongated shape and a texture that sits somewhere between familiar pizza expectations and contemporary bakery technique.', 'lang' => 'en', 'alternate' => 'blog/modern-pinsa-romana', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/modern-pinsa-romana.webp'),
        'en/blog/truffle-gelato-at-home' => array('title' => 'How to Prepare Italian-Inspired Truffle Gelato at Home | LuxurEat', 'description' => 'Truffle Gelato is an unusual dessert, but the preparation experience does not need to be complicated. The LuxurEat (露意膳) concept uses a powdered mix to move the technically difficult part - formulation - away from the consumer and make the final dessert easier to prepare close to the moment it will be served.', 'lang' => 'en', 'alternate' => 'blog/truffle-gelato-at-home', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-gelato-at-home.webp'),
        'en/blog/pizza-pinsa-at-home' => array('title' => 'How to Make Italian-Style Pizza and Pinsa at Home | LuxurEat', 'description' => 'A domestic oven cannot reproduce every condition of a professional pizzeria, but home cooks can get much closer to Italian-style results by matching the dough, heat and toppings to the style they want to make.', 'lang' => 'en', 'alternate' => 'blog/pizza-pinsa-at-home', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/pizza-pinsa-at-home.webp'),
        'en/blog/china-italian-gelato' => array('title' => 'Why China Is Discovering Italian Gelato | LuxurEat', 'description' => 'China already has a sophisticated frozen-dessert market. The opportunity for Italian gelato is therefore not to introduce the idea of ice cream from zero, but to explain why gelato can represent a different combination of craft, flavour, texture and Italian food culture.', 'lang' => 'en', 'alternate' => 'blog/china-italian-gelato', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/china-italian-gelato.webp'),
        'en/blog/china-italian-evo' => array('title' => 'Why China Is Discovering Italian Extra Virgin Olive Oil | LuxurEat', 'description' => 'Olive oil is no longer presented in China only as an imported health-oriented product. As consumers become more familiar with international cooking, EVO has more room to be understood as a flavour ingredient with regional identity, culinary applications and measurable production standards.', 'lang' => 'en', 'alternate' => 'blog/china-italian-evo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/china-italian-evo.webp'),
        'en/blog/china-pizza-pinsa' => array('title' => 'Why China Is Discovering Italian Pizza - and Why Pinsa Creates a New Entry Point | LuxurEat', 'description' => 'Pizza is already widely known in China, which changes the challenge completely. The opportunity is not to introduce pizza itself, but to move the conversation from generic fast food toward regional styles, dough technique, ingredient quality and newer Italian formats such as Pinsa.', 'lang' => 'en', 'alternate' => 'blog/china-pizza-pinsa', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/china-pizza-pinsa.webp'),
        'blog/italian-gelato-vs-ice-cream' => array('title' => '意式手工冰淇淋与普通冰淇淋：意大利传统究竟有何不同？ | LuxurEat', 'description' => '意式手工冰淇淋和普通冰淇淋都属于冷冻甜品，但传统意式手工冰淇淋在空气含量、脂肪比例、食用温度和风味表达之间形成了不同的平衡。因此，差别并不只是“配方不同”，而是带来了一种不同的冷冻甜品体验。', 'lang' => 'zh', 'alternate' => 'en/blog/italian-gelato-vs-ice-cream', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/italian-gelato-vs-ice-cream.webp'),
        'blog/story-of-italian-gelato' => array('title' => '意式手工冰淇淋的故事：从早期冷冻甜品到手工冰淇淋专卖店 | LuxurEat', 'description' => '意式手工冰淇淋的历史并不是某一次“发明”的故事，而是一段逐渐演变的过程：早期冷冻甜品、意大利宫廷文化、技术实验与手工艺传统，最终共同塑造了今天我们熟悉的手工冰淇淋专卖店。', 'lang' => 'zh', 'alternate' => 'en/blog/story-of-italian-gelato', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/story-of-italian-gelato.webp'),
        'blog/truffle-meets-dessert' => array('title' => '松露不只属于咸味料理：当松露遇见甜点 | LuxurEat', 'description' => '提到松露，人们通常想到意面、烩饭、鸡蛋和奶酪。但松露首先是一种以香气见长的食材，而香气完全可以与甜味、乳制品、巧克力以及其他甜点元素形成出人意料却协调的组合。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-meets-dessert', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-meets-dessert.webp'),
        'blog/evo-vs-common-cooking-oil' => array('title' => '意大利特级初榨橄榄油与普通食用油：特级初榨橄榄油到底有什么不同？ | LuxurEat', 'description' => '“橄榄油”并不是一个单一产品类别。特级初榨橄榄油（特级初榨橄榄油）由其取得方式以及化学和感官标准共同定义；精炼橄榄油和橄榄果渣油则采用不同工艺生产，因此也具有不同特征。', 'lang' => 'zh', 'alternate' => 'en/blog/evo-vs-common-cooking-oil', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/evo-vs-common-cooking-oil.webp'),
        'blog/story-of-italian-evo' => array('title' => '意大利特级初榨橄榄油的故事：从地中海日常食材到地域手工传统 | LuxurEat', 'description' => '橄榄油的历史远早于现代意大利国家的形成，但意大利半岛围绕橄榄种植、压榨、烹饪与品鉴发展出了极其丰富的地域文化。今天的特级初榨橄榄油，正是这段漫长农业与饮食历史的现代延续。', 'lang' => 'zh', 'alternate' => 'en/blog/story-of-italian-evo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/story-of-italian-evo.webp'),
        'blog/evo-chocolate-dessert' => array('title' => '特级初榨橄榄油不只拌沙拉：当特级初榨橄榄油遇见巧克力和甜点 | LuxurEat', 'description' => '特级初榨橄榄油通常通过咸味料理被认识，但它的果香、苦味和芳香同样可以与巧克力、蛋糕和意式手工冰淇淋形成细腻的对比。关键在于选择合适的油，并控制使用量。', 'lang' => 'zh', 'alternate' => 'en/blog/evo-chocolate-dessert', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/evo-chocolate-dessert.webp'),
        'blog/choose-use-store-evo' => array('title' => '在家如何选择、使用和保存意大利特级初榨橄榄油 | LuxurEat', 'description' => '挑选一瓶好的特级初榨橄榄油并不需要专业品油师训练。正确读懂产品类别、查看产地信息、避光避热保存，并根据菜肴选择合适的风味强度，这些实用习惯远比单纯看油的颜色更重要。', 'lang' => 'zh', 'alternate' => 'en/blog/choose-use-store-evo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/olive-cover-10.webp'),
        'blog/neapolitan-roman-pizza-styles' => array('title' => '那不勒斯披萨与罗马披萨：为什么“意大利披萨”并不是一种东西 | LuxurEat', 'description' => '在意大利以外，披萨常被当成一种统一菜式。但在意大利，不同城市和不同风格之间，面团质地、烘烤方式、形状甚至食用场景都可能发生巨大变化。那不勒斯和罗马就是最清楚的两个例子。', 'lang' => 'zh', 'alternate' => 'en/blog/neapolitan-roman-pizza-styles', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/neapolitan-roman-pizza-styles.webp'),
        'blog/story-of-italian-pizza' => array('title' => '意大利披萨的故事：从那不勒斯走向世界 | LuxurEat', 'description' => '披萨如今是全球最容易识别的食物之一，但现代披萨的身份根植于那不勒斯，以及 18、19 世纪的城市日常饮食文化。它后来才通过移民、地方化改造和简洁灵活的形式，成为全球性食物。', 'lang' => 'zh', 'alternate' => 'en/blog/story-of-italian-pizza', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/story-of-italian-pizza.webp'),
        'blog/modern-pinsa-romana' => array('title' => '经典披萨之外：认识现代罗马品萨 | LuxurEat', 'description' => '更准确的理解方式，是把罗马品萨看成一种现代罗马烘焙创新，而不是一份从古罗马时代原封不动流传下来的古老配方。它的身份来自独特的面团思路、细长形状，以及介于熟悉披萨印象与现代烘焙技术之间的质地。', 'lang' => 'zh', 'alternate' => 'en/blog/modern-pinsa-romana', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/modern-pinsa-romana.webp'),
        'blog/truffle-gelato-at-home' => array('title' => '在家如何制作意大利风格松露意式手工冰淇淋 | LuxurEat', 'description' => '松露意式手工冰淇淋是一种不常见的甜品，但制作体验本身并不需要复杂。LuxurEat（露意膳） 的思路是用粉末基底把配方平衡这一技术难度从消费者端移走，让最终甜品能够更方便地在接近食用时间时完成。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-gelato-at-home', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-gelato-at-home.webp'),
        'blog/pizza-pinsa-at-home' => array('title' => '在家如何制作意大利风格披萨与品萨 | LuxurEat', 'description' => '家用烤箱无法复制专业披萨店的全部条件，但只要让面团、热量和配料与目标风格匹配，家庭用户仍然可以明显接近意大利风格的成品。', 'lang' => 'zh', 'alternate' => 'en/blog/pizza-pinsa-at-home', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/pizza-pinsa-at-home.webp'),
        'blog/china-italian-gelato' => array('title' => '为什么中国正在重新认识意式手工冰淇淋 | LuxurEat', 'description' => '中国已经拥有成熟的冷冻甜品市场。因此，意式手工冰淇淋的机会并不是从零向消费者解释“什么是冰淇淋”，而是让人理解意式手工冰淇淋为什么能代表一种不同的工艺、风味、质地与意大利饮食文化组合。', 'lang' => 'zh', 'alternate' => 'en/blog/china-italian-gelato', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/china-italian-gelato.webp'),
        'blog/china-italian-evo' => array('title' => '为什么中国正在重新认识意大利特级初榨橄榄油 | LuxurEat', 'description' => '在中国，橄榄油正在逐步摆脱“进口健康油”这一单一形象。随着消费者越来越熟悉国际烹饪，特级初榨橄榄油有更多机会被理解为一种具有地域身份、烹饪用途和明确生产标准的风味食材。', 'lang' => 'zh', 'alternate' => 'en/blog/china-italian-evo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/china-italian-evo.webp'),
        'blog/china-pizza-pinsa' => array('title' => '为什么中国正在进一步理解意大利披萨——以及品萨如何创造新的入口 | LuxurEat', 'description' => '披萨在中国已经拥有很高认知度，因此挑战完全不同。机会不在于再次介绍“什么是披萨”，而在于把讨论从泛化的快餐概念，带向地域风格、面团技术、食材品质，以及品萨这类更新的意大利形式。', 'lang' => 'zh', 'alternate' => 'en/blog/china-pizza-pinsa', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/china-pizza-pinsa.webp'),
        'blog/truffle-what-is-truffle' => array('title' => '什么是松露？为什么它会生长在地下？ | LuxurEat', 'description' => '提到松露，很多人首先想到的是“昂贵”“稀有”或“高级餐厅”。但如果真正想理解这种食材，最好的起点并不是价格，而是先弄清楚一件事：松露究竟是什么？松露是一类在地下形成子实体的真菌。与我们平时在地面上看到的蘑菇不同，成熟松露隐藏在土壤之中，因此仅靠肉眼很难发现。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-what-is-truffle', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-what-is-truffle.webp'),
        'blog/truffle-truffle-types' => array('title' => '白松露、黑松露、夏季松露：它们到底有什么区别？ | LuxurEat', 'description' => '第一次接触松露时，最容易产生的误解之一就是：“松露不都是一样的吗，只是颜色不同？”实际上，白松露、珍贵黑松露、夏季黑松露等属于不同种类，它们的成熟时期、香气特点、市场定位和料理方法都存在差异。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-truffle-types', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-truffle-types.webp'),
        'blog/truffle-italian-truffle-map' => array('title' => '意大利松露地图：为什么著名产地不只有阿尔巴？ | LuxurEat', 'description' => '说到意大利松露，很多人首先想到的是阿尔巴。这并不奇怪。皮埃蒙特的白松露文化拥有极高的国际知名度，阿尔巴也长期成为意大利白松露最具象征性的名字之一。但如果把“意大利松露”等同于“阿尔巴松露”，就会错过意大利松露文化真正有趣的部分：它从来不是单一产区的故事。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-italian-truffle-map', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-italian-truffle-map.webp'),
        'blog/truffle-truffle-aroma-pairing' => array('title' => '为什么松露只需要一点？理解松露的香气与风味搭配 | LuxurEat', 'description' => '很多人在第一次购买松露产品时都会有一种很自然的想法：既然松露这么珍贵，是不是放得越多越好？答案并不是这样。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-truffle-aroma-pairing', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-truffle-aroma-pairing.jpg'),
        'blog/truffle-buying-truffle-products' => array('title' => '新鲜松露、松露酱、松露调味油：购买松露产品时到底应该看什么？ | LuxurEat', 'description' => '松露产品的世界远比“新鲜松露”复杂。走进一家意大利食品店，可以看到新鲜松露、松露酱、奶油类松露酱、松露调味油、松露盐、松露蜂蜜以及各种含松露的加工食品。它们都可以与松露有关，但它们显然不是同一种产品。', 'lang' => 'zh', 'alternate' => 'en/blog/truffle-buying-truffle-products', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-buying-truffle-products.webp'),
        'en/blog/truffle-what-is-truffle' => array('title' => 'What Is a Truffle, and Why Does It Grow Underground? | LuxurEat', 'description' => 'When people first hear the word truffle, they often think of rarity, high prices, or fine dining. But the best way to understand this ingredient is not to begin with its price.', 'lang' => 'en', 'alternate' => 'blog/truffle-what-is-truffle', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-what-is-truffle.webp'),
        'en/blog/truffle-truffle-types' => array('title' => 'White Truffle, Black Truffle and Summer Truffle: What Is the Difference? | LuxurEat', 'description' => 'One of the most common misunderstandings among people discovering truffles for the first time is simple: Aren’t all truffles basically the same, apart from the colour?', 'lang' => 'en', 'alternate' => 'blog/truffle-truffle-types', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-truffle-types.webp'),
        'en/blog/truffle-italian-truffle-map' => array('title' => 'A Map of Italian Truffles: Why Alba Is Not the Only Important Territory | LuxurEat', 'description' => 'Ask someone to name an Italian truffle destination and one answer appears more frequently than almost any other: Alba. The association is understandable.', 'lang' => 'en', 'alternate' => 'blog/truffle-italian-truffle-map', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-italian-truffle-map.webp'),
        'en/blog/truffle-truffle-aroma-pairing' => array('title' => 'Why Does Truffle Often Need Only a Little? Understanding Aroma and Pairing | LuxurEat', 'description' => 'When people buy truffle or truffle products for the first time, a very natural question often appears: If truffle is so precious, shouldn\'t we use more of it?', 'lang' => 'en', 'alternate' => 'blog/truffle-truffle-aroma-pairing', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-truffle-aroma-pairing.jpg'),
        'en/blog/truffle-buying-truffle-products' => array('title' => 'Fresh Truffle, Truffle Sauce and Truffle-Flavoured Oil: What Should You Look for When Buying? | LuxurEat', 'description' => 'The world of truffle products is much broader than fresh truffle. An Italian food shop may offer fresh truffle, truffle sauces, creamy truffle spreads, truffle-flavoured oils, truffle salt, truffle honey, cheeses, condiments, and many other processed products.', 'lang' => 'en', 'alternate' => 'blog/truffle-buying-truffle-products', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/truffle-buying-truffle-products.webp'),
        'blog/dictionary-italian-menu' => array('title' => '看懂意大利菜单：从前菜到甜点，常见菜单词是什么意思？ | LuxurEat', 'description' => '第一次打开意大利餐厅菜单时，即使认识“披萨”和“意面”，很多人仍然会被菜单结构弄得有些困惑。为什么“第一道菜”里没有前菜？为什么意面属于“第一道”，肉和鱼却属于“第二道”？', 'lang' => 'zh', 'alternate' => 'en/blog/dictionary-italian-menu', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-italian-menu.webp'),
        'blog/dictionary-pasta-risotto' => array('title' => '意面与烩饭词典：这些意大利词到底是在说形状、酱汁还是做法？ | LuxurEat', 'description' => '意大利意面的名字非常多。对于第一次接触的人来说，它们很容易全部变成一个模糊概念：反正都是意面。', 'lang' => 'zh', 'alternate' => 'en/blog/dictionary-pasta-risotto', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-pasta-risotto.webp'),
        'blog/dictionary-cheese-cured-meat' => array('title' => '奶酪与意大利腌肉词典：帕尔马干酪、莫扎里拉和火腿到底有什么区别？ | LuxurEat', 'description' => '意大利奶酪和腌肉拥有大量地方名称。这些名字并不只是“不同品牌”。很多时候，它们分别代表不同原料、生产方式、成熟时间和地域传统。', 'lang' => 'zh', 'alternate' => 'en/blog/dictionary-cheese-cured-meat', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-cheese-cured-meat.webp'),
        'blog/dictionary-cooking-methods' => array('title' => '意大利烹饪方法词典：煎、烤、炖、焗在意大利菜单上怎么说？ | LuxurEat', 'description' => '看懂一道菜，不只是看懂食材。菜单上的烹饪方法同样会直接告诉我们一道菜大概是什么口感。', 'lang' => 'zh', 'alternate' => 'en/blog/dictionary-cooking-methods', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-cooking-methods.webp'),
        'blog/dictionary-food-labels' => array('title' => '意大利食品标签词典：原产地保护、有机、特级初榨到底代表什么？ | LuxurEat', 'description' => '意大利食品包装上经常出现很多看起来很“高级”的词。但真正重要的不是这些词看起来有多专业，而是理解：哪些属于正式食品分类或认证，哪些只是普通营销表达。', 'lang' => 'zh', 'alternate' => 'en/blog/dictionary-food-labels', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-food-labels.webp'),
        'en/blog/dictionary-italian-menu' => array('title' => 'Understanding an Italian Menu: From Antipasto to Dolce | LuxurEat', 'description' => 'Opening an Italian restaurant menu for the first time can be confusing even if words such as pizza and pasta are already familiar. Why does the “first course” come after the starter?', 'lang' => 'en', 'alternate' => 'blog/dictionary-italian-menu', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-italian-menu.webp'),
        'en/blog/dictionary-pasta-risotto' => array('title' => 'Pasta and Risotto Dictionary: Shape, Sauce or Cooking Method? | LuxurEat', 'description' => 'Italy has an enormous variety of pasta names. For someone discovering Italian cuisine for the first time, they can easily become one vague category: pasta is pasta.', 'lang' => 'en', 'alternate' => 'blog/dictionary-pasta-risotto', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-pasta-risotto.webp'),
        'en/blog/dictionary-cheese-cured-meat' => array('title' => 'Italian Cheese and Cured Meat Dictionary | LuxurEat', 'description' => 'Italian cheeses and cured meats carry many regional names. These names are not simply brands. They can describe different ingredients, production methods, ageing periods, and territories.', 'lang' => 'en', 'alternate' => 'blog/dictionary-cheese-cured-meat', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-cheese-cured-meat.webp'),
        'en/blog/dictionary-cooking-methods' => array('title' => 'Italian Cooking Method Dictionary | LuxurEat', 'description' => 'Understanding ingredients is only one part of reading an Italian menu. Cooking terminology can immediately tell us what texture and preparation style to expect.', 'lang' => 'en', 'alternate' => 'blog/dictionary-cooking-methods', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-cooking-methods.webp'),
        'en/blog/dictionary-food-labels' => array('title' => 'Italian Food Label Dictionary: PDO, PGI, Organic and Extra Virgin | LuxurEat', 'description' => 'Italian food packaging often contains terms and symbols that can appear highly technical. The important question is not how premium they look. It is what they actually mean.', 'lang' => 'en', 'alternate' => 'blog/dictionary-food-labels', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/dictionary-food-labels.webp'),
        'blog/producers-truffle-hunters' => array('title' => '森林里的大师：意大利松露采集者如何读懂地下世界？ | LuxurEat', 'description' => '松露抵达餐桌之前，并不存在整齐排列的果园，也没有一条能够准确预测每一颗松露位置的生产线。真正的故事往往从森林开始。意大利传统松露采集者被称为 tartufai。他们的工作不是简单地“找到昂贵食材”，而是理解树木、土壤、湿度、季节和动物之间复杂的关系。', 'lang' => 'zh', 'alternate' => 'en/blog/producers-truffle-hunters', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-truffle-hunters.jpg'),
        'blog/producers-parmigiano-cheesemakers' => array('title' => '奶酪工坊的清晨：帕尔马干酪制酪师与艾米利亚的土地 | LuxurEat', 'description' => '制作帕尔马干酪的工作，很早就开始了。在传统奶酪工坊里，制酪师面对的不是一条完全自动化的配方，而是每天都会略有变化的牛奶、温度、时间和凝乳状态。这种职业在意大利被称为 casaro——制酪师。', 'lang' => 'zh', 'alternate' => 'en/blog/producers-parmigiano-cheesemakers', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-parmigiano-cheesemakers.webp'),
        'blog/producers-modena-acetaia' => array('title' => '屋顶下的时间：摩德纳传统香醋与一座家庭醋坊 | LuxurEat', 'description' => '有些意大利食品靠火候完成。有些靠发酵。而摩德纳传统香醋最重要的原料之一，是时间。', 'lang' => 'zh', 'alternate' => 'en/blog/producers-modena-acetaia', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-modena-acetaia.webp'),
        'blog/producers-neapolitan-pizzaiuolo' => array('title' => '那不勒斯披萨师：为什么做披萨也可以是一门文化遗产？ | LuxurEat', 'description' => '在全世界，披萨已经变得极其普遍。但在那不勒斯，“披萨师”并不仅仅意味着一个负责把面团送进烤炉的人。真正的传统职业叫做：pizzaiuolo。', 'lang' => 'zh', 'alternate' => 'en/blog/producers-neapolitan-pizzaiuolo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-neapolitan-pizzaiuolo.webp'),
        'blog/producers-modern-gelatiere' => array('title' => '意式冰淇淋师：从经验手艺到现代配方技术 | LuxurEat', 'description' => '站在意式冰淇淋柜台前，我们看到的是一排颜色、口味和柔软质地。但柜台后面的工作远比“把牛奶和糖冻起来”复杂。制作意式手工冰淇淋的专业人员被称为 gelatiere。', 'lang' => 'zh', 'alternate' => 'en/blog/producers-modern-gelatiere', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-modern-gelatiere.webp'),
        'en/blog/producers-truffle-hunters' => array('title' => 'Masters of the Forest: How Italian Truffle Hunters Read the Underground World | LuxurEat', 'description' => 'Before a truffle reaches the table, there is no perfectly organised orchard and no production line capable of predicting the exact position of every truffle.', 'lang' => 'en', 'alternate' => 'blog/producers-truffle-hunters', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-truffle-hunters.jpg'),
        'en/blog/producers-parmigiano-cheesemakers' => array('title' => 'Morning at the Dairy: Parmigiano Reggiano Cheesemakers and the Land of Emilia | LuxurEat', 'description' => 'Parmigiano Reggiano production begins early in the day. Inside a traditional dairy, the cheesemaker is not simply following an entirely automated formula.', 'lang' => 'en', 'alternate' => 'blog/producers-parmigiano-cheesemakers', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-parmigiano-cheesemakers.webp'),
        'en/blog/producers-modena-acetaia' => array('title' => 'Time Beneath the Roof: Traditional Balsamic Vinegar and the Acetaia of Modena | LuxurEat', 'description' => 'Some Italian foods depend primarily on heat. Others depend on fermentation. For Traditional Balsamic Vinegar of Modena, one of the most important ingredients is simply: time.', 'lang' => 'en', 'alternate' => 'blog/producers-modena-acetaia', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-modena-acetaia.webp'),
        'en/blog/producers-neapolitan-pizzaiuolo' => array('title' => 'The Neapolitan Pizzaiuolo: Why Making Pizza Can Be Cultural Heritage | LuxurEat', 'description' => 'Pizza has become one of the world\'s most common foods. In Naples, however, a pizzaiuolo is more than someone whose job is to place dough in an oven. In 2017, the Art of the Neapolitan Pizzaiuolo was inscribed on UNESCO\'s Representative List of the Intangible Cultural Heritage of Humanity.', 'lang' => 'en', 'alternate' => 'blog/producers-neapolitan-pizzaiuolo', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-neapolitan-pizzaiuolo.webp'),
        'en/blog/producers-modern-gelatiere' => array('title' => 'The Gelatiere: From Artisan Experience to Modern Formula Technology | LuxurEat', 'description' => 'At a Gelateria, customers see colour, flavour, and smooth texture. Behind the counter, the work is far more complex than simply freezing milk and sugar.', 'lang' => 'en', 'alternate' => 'blog/producers-modern-gelatiere', 'indexable' => true, 'type' => 'Article', 'image' => 'media/academy/producers-modern-gelatiere.webp'),
        'product/white-truffle-oil-60ml' => array('title' => '白松露风味油 · 60 ml | LuxurEat', 'description' => '以橄榄油和食用香料调制的白松露风味油，适合为意面、烩饭、蛋料理与冷盘增添松露香气。 产品规格为 60 ml。', 'lang' => 'zh', 'alternate' => 'en/product/white-truffle-oil-60ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-02.webp', 'sku' => 'white-truffle-oil-60ml', 'category' => '调味油'),
        'en/product/white-truffle-oil-60ml' => array('title' => 'Olio aromatizzato al tartufo bianco · 60 ml | LuxurEat', 'description' => 'A white-truffle-flavoured olive oil for finishing pasta, risotto, egg dishes and cold plates. Format: 60 ml.', 'lang' => 'en', 'alternate' => 'product/white-truffle-oil-60ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-02.webp', 'sku' => 'white-truffle-oil-60ml', 'category' => 'Seasoning Oil'),
        'product/white-truffle-oil-250ml' => array('title' => '白松露风味油 · 250 ml | LuxurEat', 'description' => '以橄榄油和食用香料调制的白松露风味油，适合为意面、烩饭、蛋料理与冷盘增添松露香气。 产品规格为 250 ml。', 'lang' => 'zh', 'alternate' => 'en/product/white-truffle-oil-250ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-01.webp', 'sku' => 'white-truffle-oil-250ml', 'category' => '调味油'),
        'en/product/white-truffle-oil-250ml' => array('title' => 'Olio aromatizzato al tartufo bianco · 250 ml | LuxurEat', 'description' => 'A white-truffle-flavoured olive oil for finishing pasta, risotto, egg dishes and cold plates. Format: 250 ml.', 'lang' => 'en', 'alternate' => 'product/white-truffle-oil-250ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-01.webp', 'sku' => 'white-truffle-oil-250ml', 'category' => 'Seasoning Oil'),
        'product/white-truffle-evoo-60ml' => array('title' => '白松露特级初榨橄榄油 · 60 ml | LuxurEat', 'description' => '以特级初榨橄榄油、0.2%春季白松露干和食用香料调制，兼具橄榄油果香与白松露香气。 产品规格为 60 ml。', 'lang' => 'zh', 'alternate' => 'en/product/white-truffle-evoo-60ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-04.webp', 'sku' => 'white-truffle-evoo-60ml', 'category' => '调味油'),
        'en/product/white-truffle-evoo-60ml' => array('title' => 'Olio extravergine al tartufo bianco · 60 ml | LuxurEat', 'description' => 'Extra virgin olive oil blended with 0.2% dried spring white truffle and flavouring for a rounded truffle finish. Format: 60 ml.', 'lang' => 'en', 'alternate' => 'product/white-truffle-evoo-60ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-04.webp', 'sku' => 'white-truffle-evoo-60ml', 'category' => 'Seasoning Oil'),
        'product/white-truffle-evoo-250ml' => array('title' => '白松露特级初榨橄榄油 · 250 ml | LuxurEat', 'description' => '以特级初榨橄榄油、0.2%春季白松露干和食用香料调制，兼具橄榄油果香与白松露香气。 产品规格为 250 ml。', 'lang' => 'zh', 'alternate' => 'en/product/white-truffle-evoo-250ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-03.webp', 'sku' => 'white-truffle-evoo-250ml', 'category' => '调味油'),
        'en/product/white-truffle-evoo-250ml' => array('title' => 'Olio extravergine al tartufo bianco · 250 ml | LuxurEat', 'description' => 'Extra virgin olive oil blended with 0.2% dried spring white truffle and flavouring for a rounded truffle finish. Format: 250 ml.', 'lang' => 'en', 'alternate' => 'product/white-truffle-evoo-250ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-03.webp', 'sku' => 'white-truffle-evoo-250ml', 'category' => 'Seasoning Oil'),
        'product/summer-truffle-slices-50g' => array('title' => '夏季黑松露片 · 50 g | LuxurEat', 'description' => '精选夏季黑松露切片，以橄榄油、食用盐和食用香料调制，适合直接点缀主菜、意面与烩饭。 产品规格为 50 g。', 'lang' => 'zh', 'alternate' => 'en/product/summer-truffle-slices-50g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-06.webp', 'sku' => 'summer-truffle-slices-50g', 'category' => '食用菌罐头'),
        'en/product/summer-truffle-slices-50g' => array('title' => 'Fette tartufo nero estate · 50 g | LuxurEat', 'description' => 'Slices of summer black truffle preserved in olive oil, ready for garnishing mains, pasta and risotto. Format: 50 g.', 'lang' => 'en', 'alternate' => 'product/summer-truffle-slices-50g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-06.webp', 'sku' => 'summer-truffle-slices-50g', 'category' => 'Canned Edible Fungi'),
        'product/summer-truffle-slices-80g' => array('title' => '夏季黑松露片 · 80 g | LuxurEat', 'description' => '精选夏季黑松露切片，以橄榄油、食用盐和食用香料调制，适合直接点缀主菜、意面与烩饭。 产品规格为 80 g。', 'lang' => 'zh', 'alternate' => 'en/product/summer-truffle-slices-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-07.webp', 'sku' => 'summer-truffle-slices-80g', 'category' => '食用菌罐头'),
        'en/product/summer-truffle-slices-80g' => array('title' => 'Fette tartufo nero estate · 80 g | LuxurEat', 'description' => 'Slices of summer black truffle preserved in olive oil, ready for garnishing mains, pasta and risotto. Format: 80 g.', 'lang' => 'en', 'alternate' => 'product/summer-truffle-slices-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-07.webp', 'sku' => 'summer-truffle-slices-80g', 'category' => 'Canned Edible Fungi'),
        'product/summer-truffle-slices-180g' => array('title' => '夏季黑松露片 · 180 g | LuxurEat', 'description' => '精选夏季黑松露切片，以橄榄油、食用盐和食用香料调制，适合直接点缀主菜、意面与烩饭。 产品规格为 180 g。', 'lang' => 'zh', 'alternate' => 'en/product/summer-truffle-slices-180g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-05.webp', 'sku' => 'summer-truffle-slices-180g', 'category' => '食用菌罐头'),
        'en/product/summer-truffle-slices-180g' => array('title' => 'Fette tartufo nero estate · 180 g | LuxurEat', 'description' => 'Slices of summer black truffle preserved in olive oil, ready for garnishing mains, pasta and risotto. Format: 180 g.', 'lang' => 'en', 'alternate' => 'product/summer-truffle-slices-180g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-05.webp', 'sku' => 'summer-truffle-slices-180g', 'category' => 'Canned Edible Fungi'),
        'product/summer-truffle-slices-water-350g' => array('title' => '水浸夏季黑松露片 · 350 g | LuxurEat', 'description' => '水浸夏季黑松露片保留食材本味与清晰质感，适合厨房按需调味和二次加工。 产品规格为 350 g。', 'lang' => 'zh', 'alternate' => 'en/product/summer-truffle-slices-water-350g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-08.webp', 'sku' => 'summer-truffle-slices-water-350g', 'category' => '食用菌罐头'),
        'en/product/summer-truffle-slices-water-350g' => array('title' => 'Fette tartufo nero estate in acqua · 350 g | LuxurEat', 'description' => 'Summer black truffle slices preserved in water for a clean flavour and flexible professional-kitchen use. Format: 350 g.', 'lang' => 'en', 'alternate' => 'product/summer-truffle-slices-water-350g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-08.webp', 'sku' => 'summer-truffle-slices-water-350g', 'category' => 'Canned Edible Fungi'),
        'product/summer-truffle-slices-oil-350g' => array('title' => '葵花籽油浸夏季黑松露片 · 350 g | LuxurEat', 'description' => '夏季黑松露含量40%，以葵花籽油浸渍，风味柔和，适合餐饮批量应用与成品装饰。 产品规格为 350 g。', 'lang' => 'zh', 'alternate' => 'en/product/summer-truffle-slices-oil-350g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-09.webp', 'sku' => 'summer-truffle-slices-oil-350g', 'category' => '食用菌罐头'),
        'en/product/summer-truffle-slices-oil-350g' => array('title' => 'Fette tartufo nero estate in olio di girasole · 350 g | LuxurEat', 'description' => 'Summer black truffle slices at 40%, preserved in sunflower oil for balanced flavour and food-service use. Format: 350 g.', 'lang' => 'en', 'alternate' => 'product/summer-truffle-slices-oil-350g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-09.webp', 'sku' => 'summer-truffle-slices-oil-350g', 'category' => 'Canned Edible Fungi'),
        'product/truffle-honey-50g' => array('title' => '松露蜂蜜 · 50 g | LuxurEat', 'description' => '以99%洋槐蜜融合白松露风味，适合搭配奶酪、烤肉、甜点与礼赠组合。 产品规格为 50 g。', 'lang' => 'zh', 'alternate' => 'en/product/truffle-honey-50g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-11.webp', 'sku' => 'truffle-honey-50g', 'category' => '复合调味品'),
        'en/product/truffle-honey-50g' => array('title' => 'Miele al tartufo · 50 g | LuxurEat', 'description' => 'Acacia honey at 99% with white truffle character, designed for cheese, roasted meats, desserts and gifting. Format: 50 g.', 'lang' => 'en', 'alternate' => 'product/truffle-honey-50g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-11.webp', 'sku' => 'truffle-honey-50g', 'category' => 'Compound Condiment'),
        'product/truffle-honey-170g' => array('title' => '松露蜂蜜 · 170 g | LuxurEat', 'description' => '以99%洋槐蜜融合白松露风味，适合搭配奶酪、烤肉、甜点与礼赠组合。 产品规格为 170 g。', 'lang' => 'zh', 'alternate' => 'en/product/truffle-honey-170g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-10.webp', 'sku' => 'truffle-honey-170g', 'category' => '复合调味品'),
        'en/product/truffle-honey-170g' => array('title' => 'Miele al tartufo · 170 g | LuxurEat', 'description' => 'Acacia honey at 99% with white truffle character, designed for cheese, roasted meats, desserts and gifting. Format: 170 g.', 'lang' => 'en', 'alternate' => 'product/truffle-honey-170g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-10.webp', 'sku' => 'truffle-honey-170g', 'category' => 'Compound Condiment'),
        'product/white-truffle-sauce-80g' => array('title' => '白松露酱 · 80 g | LuxurEat', 'description' => '以双孢蘑菇、橄榄油和1%意大利白松露制成的细腻复合酱料，可用于意面、烩饭、肉类和酱汁调配。 产品规格为 80 g。', 'lang' => 'zh', 'alternate' => 'en/product/white-truffle-sauce-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-13.webp', 'sku' => 'white-truffle-sauce-80g', 'category' => '复合调味品'),
        'en/product/white-truffle-sauce-80g' => array('title' => 'Salsa al tartufo bianco · 80 g | LuxurEat', 'description' => 'A smooth sauce of button mushrooms, olive oil and 1% Italian white truffle for pasta, risotto, meats and sauces. Format: 80 g.', 'lang' => 'en', 'alternate' => 'product/white-truffle-sauce-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-13.webp', 'sku' => 'white-truffle-sauce-80g', 'category' => 'Compound Condiment'),
        'product/white-truffle-sauce-500g' => array('title' => '白松露酱 · 500 g | LuxurEat', 'description' => '以双孢蘑菇、橄榄油和1%意大利白松露制成的细腻复合酱料，可用于意面、烩饭、肉类和酱汁调配。 产品规格为 500 g。', 'lang' => 'zh', 'alternate' => 'en/product/white-truffle-sauce-500g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-12.webp', 'sku' => 'white-truffle-sauce-500g', 'category' => '复合调味品'),
        'en/product/white-truffle-sauce-500g' => array('title' => 'Salsa al tartufo bianco · 500 g | LuxurEat', 'description' => 'A smooth sauce of button mushrooms, olive oil and 1% Italian white truffle for pasta, risotto, meats and sauces. Format: 500 g.', 'lang' => 'en', 'alternate' => 'product/white-truffle-sauce-500g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-12.webp', 'sku' => 'white-truffle-sauce-500g', 'category' => 'Compound Condiment'),
        'product/black-truffle-sauce-80g' => array('title' => '黑松露酱 · 80 g | LuxurEat', 'description' => '以双孢蘑菇、橄榄油、夏季黑松露和黑橄榄调制的浓郁松露酱，适合热菜、酱汁与烘焙馅料。 产品规格为 80 g。', 'lang' => 'zh', 'alternate' => 'en/product/black-truffle-sauce-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-16.webp', 'sku' => 'black-truffle-sauce-80g', 'category' => '复合调味品'),
        'en/product/black-truffle-sauce-80g' => array('title' => 'Salsa al tartufo nero · 80 g | LuxurEat', 'description' => 'A rich sauce of button mushrooms, olive oil, summer black truffle and black olives for hot dishes, sauces and savoury fillings. Format: 80 g.', 'lang' => 'en', 'alternate' => 'product/black-truffle-sauce-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-16.webp', 'sku' => 'black-truffle-sauce-80g', 'category' => 'Compound Condiment'),
        'product/black-truffle-sauce-180g' => array('title' => '黑松露酱 · 180 g | LuxurEat', 'description' => '以双孢蘑菇、橄榄油、夏季黑松露和黑橄榄调制的浓郁松露酱，适合热菜、酱汁与烘焙馅料。 产品规格为 180 g。', 'lang' => 'zh', 'alternate' => 'en/product/black-truffle-sauce-180g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-14.webp', 'sku' => 'black-truffle-sauce-180g', 'category' => '复合调味品'),
        'en/product/black-truffle-sauce-180g' => array('title' => 'Salsa al tartufo nero · 180 g | LuxurEat', 'description' => 'A rich sauce of button mushrooms, olive oil, summer black truffle and black olives for hot dishes, sauces and savoury fillings. Format: 180 g.', 'lang' => 'en', 'alternate' => 'product/black-truffle-sauce-180g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-14.webp', 'sku' => 'black-truffle-sauce-180g', 'category' => 'Compound Condiment'),
        'product/black-truffle-sauce-500g' => array('title' => '黑松露酱 · 500 g | LuxurEat', 'description' => '以双孢蘑菇、橄榄油、夏季黑松露和黑橄榄调制的浓郁松露酱，适合热菜、酱汁与烘焙馅料。 产品规格为 500 g。', 'lang' => 'zh', 'alternate' => 'en/product/black-truffle-sauce-500g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-15.webp', 'sku' => 'black-truffle-sauce-500g', 'category' => '复合调味品'),
        'en/product/black-truffle-sauce-500g' => array('title' => 'Salsa al tartufo nero · 500 g | LuxurEat', 'description' => 'A rich sauce of button mushrooms, olive oil, summer black truffle and black olives for hot dishes, sauces and savoury fillings. Format: 500 g.', 'lang' => 'en', 'alternate' => 'product/black-truffle-sauce-500g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-15.webp', 'sku' => 'black-truffle-sauce-500g', 'category' => 'Compound Condiment'),
        'product/whole-summer-truffles-35g' => array('title' => '整颗夏季黑松露 · 35 g | LuxurEat', 'description' => '整颗夏季黑松露以水和食用盐保存，便于切片、刨削或加入酱汁，保留完整食材形态。 产品规格为 35 g。', 'lang' => 'zh', 'alternate' => 'en/product/whole-summer-truffles-35g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-18.webp', 'sku' => 'whole-summer-truffles-35g', 'category' => '食用菌罐头'),
        'en/product/whole-summer-truffles-35g' => array('title' => 'Tartufi estivi naturali · 35 g | LuxurEat', 'description' => 'Whole summer black truffles preserved in water and salt, ready for slicing, shaving or sauce preparation. Format: 35 g.', 'lang' => 'en', 'alternate' => 'product/whole-summer-truffles-35g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-18.webp', 'sku' => 'whole-summer-truffles-35g', 'category' => 'Canned Edible Fungi'),
        'product/whole-summer-truffles-55g' => array('title' => '整颗夏季黑松露 · 55 g | LuxurEat', 'description' => '整颗夏季黑松露以水和食用盐保存，便于切片、刨削或加入酱汁，保留完整食材形态。 产品规格为 55 g。', 'lang' => 'zh', 'alternate' => 'en/product/whole-summer-truffles-55g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-19.webp', 'sku' => 'whole-summer-truffles-55g', 'category' => '食用菌罐头'),
        'en/product/whole-summer-truffles-55g' => array('title' => 'Tartufi estivi naturali · 55 g | LuxurEat', 'description' => 'Whole summer black truffles preserved in water and salt, ready for slicing, shaving or sauce preparation. Format: 55 g.', 'lang' => 'en', 'alternate' => 'product/whole-summer-truffles-55g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-19.webp', 'sku' => 'whole-summer-truffles-55g', 'category' => 'Canned Edible Fungi'),
        'product/whole-summer-truffles-110g' => array('title' => '整颗夏季黑松露 · 110 g | LuxurEat', 'description' => '整颗夏季黑松露以水和食用盐保存，便于切片、刨削或加入酱汁，保留完整食材形态。 产品规格为 110 g。', 'lang' => 'zh', 'alternate' => 'en/product/whole-summer-truffles-110g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-20.webp', 'sku' => 'whole-summer-truffles-110g', 'category' => '食用菌罐头'),
        'en/product/whole-summer-truffles-110g' => array('title' => 'Tartufi estivi naturali · 110 g | LuxurEat', 'description' => 'Whole summer black truffles preserved in water and salt, ready for slicing, shaving or sauce preparation. Format: 110 g.', 'lang' => 'en', 'alternate' => 'product/whole-summer-truffles-110g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-20.webp', 'sku' => 'whole-summer-truffles-110g', 'category' => 'Canned Edible Fungi'),
        'product/whole-summer-truffles-275g' => array('title' => '整颗夏季黑松露 · 275 g | LuxurEat', 'description' => '整颗夏季黑松露以水和食用盐保存，便于切片、刨削或加入酱汁，保留完整食材形态。 产品规格为 275 g。', 'lang' => 'zh', 'alternate' => 'en/product/whole-summer-truffles-275g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-17.webp', 'sku' => 'whole-summer-truffles-275g', 'category' => '食用菌罐头'),
        'en/product/whole-summer-truffles-275g' => array('title' => 'Tartufi estivi naturali · 275 g | LuxurEat', 'description' => 'Whole summer black truffles preserved in water and salt, ready for slicing, shaving or sauce preparation. Format: 275 g.', 'lang' => 'en', 'alternate' => 'product/whole-summer-truffles-275g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-17.webp', 'sku' => 'whole-summer-truffles-275g', 'category' => 'Canned Edible Fungi'),
        'product/winter-black-truffle-sauce-80g' => array('title' => '冬季黑松露酱 · 80 g | LuxurEat', 'description' => '含2.9%冬季黑松露与5%黑喇叭菌，层次深沉且不含麸质，适合精细餐饮调味。 产品规格为 80 g。', 'lang' => 'zh', 'alternate' => 'en/product/winter-black-truffle-sauce-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-23.webp', 'sku' => 'winter-black-truffle-sauce-80g', 'category' => '复合调味品'),
        'en/product/winter-black-truffle-sauce-80g' => array('title' => 'Salsa al tartufo nero invernale · 80 g | LuxurEat', 'description' => 'A gluten-free sauce with 2.9% winter black truffle and 5% black trumpet mushrooms for refined savoury applications. Format: 80 g.', 'lang' => 'en', 'alternate' => 'product/winter-black-truffle-sauce-80g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-23.webp', 'sku' => 'winter-black-truffle-sauce-80g', 'category' => 'Compound Condiment'),
        'product/winter-black-truffle-sauce-180g' => array('title' => '冬季黑松露酱 · 180 g | LuxurEat', 'description' => '含2.9%冬季黑松露与5%黑喇叭菌，层次深沉且不含麸质，适合精细餐饮调味。 产品规格为 180 g。', 'lang' => 'zh', 'alternate' => 'en/product/winter-black-truffle-sauce-180g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-21.webp', 'sku' => 'winter-black-truffle-sauce-180g', 'category' => '复合调味品'),
        'en/product/winter-black-truffle-sauce-180g' => array('title' => 'Salsa al tartufo nero invernale · 180 g | LuxurEat', 'description' => 'A gluten-free sauce with 2.9% winter black truffle and 5% black trumpet mushrooms for refined savoury applications. Format: 180 g.', 'lang' => 'en', 'alternate' => 'product/winter-black-truffle-sauce-180g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-21.webp', 'sku' => 'winter-black-truffle-sauce-180g', 'category' => 'Compound Condiment'),
        'product/winter-black-truffle-sauce-500g' => array('title' => '冬季黑松露酱 · 500 g | LuxurEat', 'description' => '含2.9%冬季黑松露与5%黑喇叭菌，层次深沉且不含麸质，适合精细餐饮调味。 产品规格为 500 g。', 'lang' => 'zh', 'alternate' => 'en/product/winter-black-truffle-sauce-500g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-22.webp', 'sku' => 'winter-black-truffle-sauce-500g', 'category' => '复合调味品'),
        'en/product/winter-black-truffle-sauce-500g' => array('title' => 'Salsa al tartufo nero invernale · 500 g | LuxurEat', 'description' => 'A gluten-free sauce with 2.9% winter black truffle and 5% black trumpet mushrooms for refined savoury applications. Format: 500 g.', 'lang' => 'en', 'alternate' => 'product/winter-black-truffle-sauce-500g', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-22.webp', 'sku' => 'winter-black-truffle-sauce-500g', 'category' => 'Compound Condiment'),
        'product/winter-black-truffle-juice-350ml' => array('title' => '冬季黑松露汁 · 350 ml | LuxurEat', 'description' => '以99.5%冬季黑松露汁制成的不含麸质复合调味汁，适合强化高汤、酱汁与烩饭的松露风味。 产品规格为 350 ml。', 'lang' => 'zh', 'alternate' => 'en/product/winter-black-truffle-juice-350ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-24.webp', 'sku' => 'winter-black-truffle-juice-350ml', 'category' => '复合调味汁'),
        'en/product/winter-black-truffle-juice-350ml' => array('title' => 'Succo di tartufo nero pregiato · 350 ml | LuxurEat', 'description' => 'A gluten-free seasoning sauce made with 99.5% winter black truffle juice for enriching stocks, sauces and risotto. Format: 350 ml.', 'lang' => 'en', 'alternate' => 'product/winter-black-truffle-juice-350ml', 'indexable' => true, 'type' => 'Product', 'image' => 'media/products/appennino/product-24.webp', 'sku' => 'winter-black-truffle-juice-350ml', 'category' => 'Compound Seasoning Sauce'),
        'events/fhc-shanghai-2026' => array('title' => 'LuxurEat（露意膳）亮相FHC上海环球食品展。 | LuxurEat', 'description' => '2026年11月10日至12日，第二十九届FHC上海环球食品展将在上海浦东新国际博览中心举行。LuxurEat（露意膳）将携TruffleEat、CaviarEat、Ugolini等品牌亮相N3馆N3A10展位。', 'lang' => 'zh', 'alternate' => 'en/events/fhc-shanghai-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/fhc-shanghai-2026-banner.webp', 'startDate' => '2026-11-10', 'endDate' => '2026-11-12', 'location' => '上海浦东新国际博览中心 N3馆N3A10展位'),
        'en/events/fhc-shanghai-2026' => array('title' => 'LuxurEat (露意膳) at FHC Shanghai Global Food Trade Show | LuxurEat', 'description' => 'From November 10 to 12, 2026, the 29th FHC Shanghai Global Food Trade Show will take place at the Shanghai New International Expo Centre. LuxurEat (露意膳) will present TruffleEat, CaviarEat, Ugolini, and other brands at Booth N3A10 in Hall N3.', 'lang' => 'en', 'alternate' => 'events/fhc-shanghai-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/fhc-shanghai-2026-banner.webp', 'startDate' => '2026-11-10', 'endDate' => '2026-11-12', 'location' => 'Booth N3A10, Hall N3, Shanghai New International Expo Centre'),
        'events/cifie-changsha-2026' => array('title' => '意大利风味，与长沙相遇。 | LuxurEat', 'description' => '作为国家内贸流通重点展会、中部地区唯一获得UFI认证的食品餐饮专业展，第十一届中国国际食品餐饮博览会将于2026年9月18日至20日在长沙国际会展中心举办。LuxurEat（露意膳）将携TrufflEat、CaviarEat与TinCaviar亮相，与全国及国际市场的专业伙伴相见。', 'lang' => 'zh', 'alternate' => 'en/events/cifie-changsha-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/cifie-changsha-2026-banner.webp', 'startDate' => '2026-09-18', 'endDate' => '2026-09-20', 'location' => '长沙国际会展中心'),
        'en/events/cifie-changsha-2026' => array('title' => 'Italian flavor meets Changsha. | LuxurEat', 'description' => 'The 11th China International Food & Catering Expo will take place at the Changsha International Convention and Exhibition Centre from September 18 to 20, 2026. As a key national exhibition for domestic trade and Central China’s only UFI-certified professional food and catering fair, it will welcome LuxurEat (露意膳), TrufflEat, CaviarEat, and TinCaviar alongside partners from China and international markets.', 'lang' => 'en', 'alternate' => 'events/cifie-changsha-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/cifie-changsha-2026-banner.webp', 'startDate' => '2026-09-18', 'endDate' => '2026-09-20', 'location' => 'Changsha International Convention and Exhibition Centre'),
        'events/marca-china-2026' => array('title' => 'LuxurEat（露意膳）亮相广州国际自有品牌展。 | LuxurEat', 'description' => '中国食品市场正向健康化、品质化、地域化与年轻化发展。LuxurEat（露意膳） 将在广州与专业买家及合作伙伴共同探索新的产品与商业机会。', 'lang' => 'zh', 'alternate' => 'en/events/marca-china-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/marca-china-2026.png', 'startDate' => '2026-09-08', 'endDate' => '2026-09-09', 'location' => '广州保利世贸展览馆'),
        'en/events/marca-china-2026' => array('title' => 'LuxurEat (露意膳) at Marca China 2026 | LuxurEat', 'description' => 'China\'s food market is moving toward healthier, higher-quality, more regional, and younger expressions. In Guangzhou, LuxurEat (露意膳) will explore new products and partnerships with professional buyers and industry partners.', 'lang' => 'en', 'alternate' => 'events/marca-china-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/marca-china-2026.png', 'startDate' => '2026-09-08', 'endDate' => '2026-09-09', 'location' => 'Poly World Trade Expo Centre, Guangzhou'),
        'events/sial-guangzhou-2026' => array('title' => 'LuxurEat（露意膳）广州：2026 SIAL西雅展。 | LuxurEat', 'description' => '2026年9月3日至5日，SIAL西雅展（广州）将在广州保利世贸博览馆举行。LuxurEat（露意膳）将携TruffleEat、CaviarEat、Ugolini等品牌亮相。', 'lang' => 'zh', 'alternate' => 'en/events/sial-guangzhou-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/sial-guangzhou-2026-banner.webp', 'startDate' => '2026-09-03', 'endDate' => '2026-09-05', 'location' => '广州保利世贸博览馆'),
        'en/events/sial-guangzhou-2026' => array('title' => 'LuxurEat (露意膳) Guangzhou: SIAL 2026 | LuxurEat', 'description' => 'From September 3 to 5, 2026, SIAL Guangzhou will take place at the Poly World Trade Expo Centre. LuxurEat (露意膳) will present TruffleEat, CaviarEat, Ugolini, and other brands.', 'lang' => 'en', 'alternate' => 'events/sial-guangzhou-2026', 'indexable' => true, 'type' => 'Event', 'image' => 'media/events/sial-guangzhou-2026-banner.webp', 'startDate' => '2026-09-03', 'endDate' => '2026-09-05', 'location' => 'Poly World Trade Expo Centre, Guangzhou'),
        'recipe/truffle-eggs' => array('title' => '松露鸡蛋 | LuxurEat', 'description' => '松露鸡蛋是一道美味佳肴，它将新鲜鸡蛋的清爽与松露浓郁的香气完美融合。无论是早餐、早午餐，还是作为精致的开胃菜，这道菜都堪称完美之选。做法简单快捷，其香气和精致口感定会让您爱不释手。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-eggs', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-eggs.webp', 'category' => '早餐食谱', 'totalTime' => 'PT10M', 'yield' => '1人份', 'ingredients' => array('2个新鲜鸡蛋', '1茶匙特级初榨橄榄油或一小块黄油', '4-5片薄薄的黑松露片', '几片新鲜欧芹叶（可选）'), 'instructions' => array('在不粘锅中倒入少许油或一小块黄油，加热。', '轻轻敲开鸡蛋，用中小火煮，使蛋黄保持柔软。', '加少许盐和胡椒粉。', '鸡蛋快熟时，将松露片放在鸡蛋上，让热气释放松露的香气。'), 'nutrition' => '能量: 950千焦（约225千卡）; 饱和脂肪: 5克（25%）; 碳水化合物（糖类）: 1克; 蛋白质: 12克'),
        'recipe/truffle-toast' => array('title' => '水煮蛋配松露烤面包片 | LuxurEat', 'description' => '烤面包片配水煮蛋和松露，是一道精致的餐桌小点，非常适合招待客人或打造优雅的周日早午餐。金黄酥脆的面包与绵密的鸡蛋和浓郁的松露香气完美融合，带来令人难以抗拒的美味和口感。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-toast', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-toast.webp', 'category' => '早餐食谱', 'totalTime' => 'PT15M', 'yield' => '2人份', 'ingredients' => array('4片乡村面包或法棍面包', '2个新鲜鸡蛋', '1汤匙白醋', '2汤匙新鲜涂抹型奶酪（例如罗比奥拉奶酪或里科塔奶酪）', '5-6片薄薄的黑松露片', '1茶匙刺山柑（可选）', '新鲜罗勒叶', '适量特级初榨橄榄油', '盐和胡椒粉适量'), 'instructions' => array('将面包片烤至酥脆，加热烤盘或不粘锅。', '准备水煮蛋：将一锅水和一汤匙醋烧开。用勺子在水面上搅出一个漩涡，然后轻轻地将鸡蛋打入漩涡中心。煮2-3分钟，直到蛋白凝固但蛋黄仍然溏心。', '组装烤面包片：在面包片上涂抹奶油奶酪，放上水煮蛋，最后放上几片松露。', '装饰：淋上少许橄榄油，撒上刺山柑（可选）和几片新鲜罗勒叶。用少许盐和胡椒粉调味。'), 'nutrition' => '能量: 1300千焦（约310千卡）; 饱和脂肪: 6克（30%）; 碳水化合物（糖类）: 3克; 蛋白质: 12克'),
        'recipe/truffle-tagliolini' => array('title' => '白松露或黑松露细面 | LuxurEat', 'description' => '精致而令人回味无穷的头盘，是庆祝特殊场合的完美之选。新鲜的鸡蛋细面拌入黄油和帕玛森奶酪，再撒上大量的白松露或黑松露碎，带来令人难忘的香气体验。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-tagliolini', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-tagliolini.webp', 'category' => '第一道主食', 'totalTime' => 'PT25M', 'yield' => '2人份', 'ingredients' => array('250克意大利饺子（奶酪馅或蘑菇馅）', '30克黄油', '2汤匙鲜奶油', '4-5片新鲜黑松露', '1汤匙磨碎的帕玛森干酪', '新鲜切碎的欧芹适量', '盐和胡椒粉适量'), 'instructions' => array('煮馄饨：将一锅加盐的水烧开，然后按照包装上的说明煮馄饨（如果是新鲜的，则煮3-4分钟）。', '准备酱汁。在平底锅中，用小火融化黄油，加入奶油和一汤匙煮馄饨的水。搅拌至酱汁浓稠顺滑。', '将馄饨轻轻沥干水分，倒入盛有酱汁的锅中。加入帕玛森奶酪，翻炒1-2分钟。', '装饰盘子，最后放上几片新鲜黑松露和少许切碎的欧芹。'), 'nutrition' => '能量: 1750千焦（约420千卡）; 饱和脂肪: 9克（45%）; 碳水化合物（糖类）: 3克; 蛋白质: 14克'),
        'recipe/truffle-ravioli' => array('title' => '松露奶油酱馄饨 | LuxurEat', 'description' => '松露奶油酱汁馄饨，将新鲜意面的细腻口感与松露独特的香气完美融合。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-ravioli', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-ravioli.webp', 'category' => '第一道主食', 'totalTime' => 'PT25M', 'yield' => '2人份', 'ingredients' => array('250克意大利饺子（奶酪馅或蘑菇馅）', '30克黄油', '2汤匙鲜奶油', '4-5片新鲜黑松露', '1汤匙磨碎的帕玛森干酪', '新鲜切碎的欧芹适量', '盐和胡椒粉适量'), 'instructions' => array('煮馄饨：将一锅加盐的水烧开，然后按照包装上的说明煮馄饨（如果是新鲜的，则煮3-4分钟）。', '准备酱汁。在平底锅中，用小火融化黄油，加入奶油和一汤匙煮馄饨的水。搅拌至酱汁浓稠顺滑。', '将馄饨轻轻沥干水分，倒入盛有酱汁的锅中。加入帕玛森奶酪，翻炒1-2分钟。', '装饰盘子，最后放上几片新鲜黑松露和少许切碎的欧芹。'), 'nutrition' => '能量: 1750千焦（约420千卡）; 饱和脂肪: 9克（45%）; 碳水化合物（糖类）: 3克; 蛋白质: 14克'),
        'recipe/black-truffle-risotto' => array('title' => '黑松露烩饭 | LuxurEat', 'description' => '黑松露烩饭将奶油米饭的香滑口感与松露的浓郁风味完美结合，成就了一道制作简单却又精致美味的佳肴。', 'lang' => 'zh', 'alternate' => 'en/recipe/black-truffle-risotto', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-black-truffle-risotto.webp', 'category' => '第一道主食', 'totalTime' => 'PT30M', 'yield' => '2人份', 'ingredients' => array('160克卡纳罗利米', '20克黄油', '1汤匙特级初榨橄榄油', '30克磨碎的帕玛森干酪', '半个小洋葱（或青葱）', '50毫升干白葡萄酒', '4-5片新鲜黑松露薄片', '盐和胡椒粉适量'), 'instructions' => array('将洋葱切碎，放入平底锅中，加入黄油和少许油，用小火炒至变色。', '烘烤：加入卡纳罗利米，翻炒2-3分钟，直至米粒变得透明。', '脱釉：倒入白葡萄酒，让其完全蒸发。', '烹煮时，逐渐加入热肉汤，一次一勺，并不断搅拌。继续烹煮约15-18分钟。', '关火后，加入磨碎的帕玛森奶酪和一小块黄油，搅拌至顺滑浓稠。', '装饰盘子，最后撒上新鲜黑松露碎屑和少许胡椒粉。'), 'nutrition' => '能量: 1800千焦（约430千卡）; 饱和脂肪: 8克（40%）; 碳水化合物（糖类）: 2克; 蛋白质: 10克'),
        'recipe/mushroom-soup' => array('title' => '奶油蘑菇浓汤 | LuxurEat', 'description' => '一道温暖舒适的开胃菜，非常适合秋日享用。这款奶油蘑菇汤口感顺滑，散发着浓郁的森林香气，搭配金黄酥脆的烤面包丁或淋上少许松露油，风味更佳。', 'lang' => 'zh', 'alternate' => 'en/recipe/mushroom-soup', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-mushroom-soup.webp', 'category' => '第一道主食', 'totalTime' => 'PT35M', 'yield' => '2人份', 'ingredients' => array('300克混合新鲜蘑菇（香菇、牛肝菌或小菇）', '1个中等大小的土豆', '半个洋葱', '1瓣大蒜', '2汤匙特级初榨橄榄油', '400毫升热蔬菜汤', '2汤匙鲜奶油（可选）', '盐和胡椒粉适量', '新鲜欧芹碎，用于装饰', '烤面包丁，用于佐餐'), 'instructions' => array('准备食材。用湿布擦拭蘑菇，然后切片。土豆去皮，切成小丁。洋葱和大蒜切碎。', '翻炒。在平底锅中加热橄榄油，将洋葱和大蒜翻炒2-3分钟。加入蘑菇和土豆，继续翻炒约5分钟，让各种食材的味道融合。', '烹煮。倒入热蔬菜汤，盖上盖子，用中火慢炖约20分钟，直到蔬菜变软。', '搅拌。离火，用手持搅拌机将所有食材搅拌至顺滑浓稠。如果想要更浓郁的口感，可以加入奶油并搅拌均匀。', '调整口味后即可享用。用盐和胡椒调味，撒上切碎的欧芹装饰，趁热配上金黄酥脆的面包丁或淋上少许松露油。'), 'nutrition' => '能量: 870千焦（约210千卡）; 饱和脂肪: 2克（10%）; 碳水化合物（糖类）: 14克（其中4克为西葫芦）; 蛋白质: 6克'),
        'recipe/beef-carpaccio-scallop-truffle' => array('title' => '扇贝松露牛肉薄片 | LuxurEat', 'description' => '牛肉薄片配扇贝和松露，将生牛肉的浓郁、扇贝的鲜嫩与黑松露的馥郁香气完美融合，是一道优雅而赏心悦目的菜肴。', 'lang' => 'zh', 'alternate' => 'en/recipe/beef-carpaccio-scallop-truffle', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-beef-carpaccio-scallop-truffle.webp', 'category' => '第二道主食', 'totalTime' => 'PT15M', 'yield' => '2人份', 'ingredients' => array('120克牛肉薄片（极薄片）', '4个新鲜扇贝', '4-5片黑松露', '1汤匙特级初榨橄榄油', '1茶匙柠檬汁', '盐和胡椒粉适量', '几片混合沙拉蔬菜叶（例如混合蔬菜或芝麻菜）', '几滴绿色酱汁（可选）'), 'instructions' => array('准备扇贝：在不粘锅中倒入少许油，轻轻加热，将扇贝两面各煎1分钟，保持中心柔软。', '将牛肉薄片均匀地铺在平盘上，形成一层。', '将扇贝放在生牛肉片的中央。', '淋上少许橄榄油和柠檬汁，撒上盐和胡椒粉。最后以黑松露碎屑和沙拉叶装饰，如有需要，再加几滴青莎莎酱或豌豆奶油点缀。'), 'nutrition' => '能量: 850千焦（约200千卡）; 饱和脂肪: 2克（10%）; 碳水化合物（糖类）: 1克; 蛋白质: 22克'),
        'recipe/shrimp-tartare-truffle' => array('title' => '脆爽蔬菜松露虾仁鞑靼 | LuxurEat', 'description' => '一道清新精致的菜肴，生虾的鲜甜与蔬菜的爽脆、松露的浓郁香气完美融合，兼具视觉美感和味觉享受。', 'lang' => 'zh', 'alternate' => 'en/recipe/shrimp-tartare-truffle', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-shrimp-tartare-truffle.webp', 'category' => '第二道主食', 'totalTime' => 'PT20M', 'yield' => '2人份', 'ingredients' => array('200克新鲜红虾，去壳冷冻', '4-5片新鲜黑松露', '6个樱桃番茄', '半根黄瓜', '¼个黄甜椒', '半个酸橙', '1汤匙特级初榨橄榄油', '盐和胡椒粉适量', '几片沙拉叶和一些可食用花卉（用于装饰）'), 'instructions' => array('准备虾仁：用刀将虾仁切碎，直至达到细腻但不过于浓稠的状态。用橄榄油、青柠汁、盐和胡椒粉调味。', '将黄瓜、小番茄和甜椒切成非常小的丁，拌成色彩鲜艳、口感爽脆的混合蔬菜。', '用糕点切割器组装：底部放上蔬菜，然后将虾仁鞑靼放在上面。', '用松露片、可食用花卉、少许油和几滴香醋浓缩汁或绿色乳液（可选）进行装饰。'), 'nutrition' => '能量: 600千焦（约145千卡）; 饱和脂肪: 1克（5%）; 碳水化合物（糖类）: 2克; 蛋白质: 14克'),
        'recipe/sweet-bread-butter-caviar' => array('title' => '甜面包配黄油和鱼子酱 | LuxurEat', 'description' => '甜面包配黄油和鱼子酱是一种优雅的小食，柔软香甜的奶油蛋卷面包、醇厚的黄油与鱼子酱的鲜美风味完美融合。', 'lang' => 'zh', 'alternate' => 'en/recipe/sweet-bread-butter-caviar', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-sweet-bread-butter-caviar.webp', 'category' => '甜点食谱', 'totalTime' => 'PT10M', 'yield' => '4人份', 'ingredients' => array('4块甜面包，例如奶油蛋卷面包', '40克优质黄油（最好是无盐黄油）', '20克鱼子酱（例如鲟鱼子酱或Beluga鱼子酱）', '少许糖粉（可选，用于装饰）'), 'instructions' => array('将奶油蛋卷面包切成4块立方体或厚片，保持面包柔软。', '将黄油在室温下软化，然后均匀涂抹在面包上。', '将鱼子酱轻轻放在每块涂了黄油的面包上。', '置于优雅的盘子上。如果想增强对比度，可以轻轻撒上糖粉。'), 'nutrition' => '能量: 720千焦（约170千卡）; 饱和脂肪: 5克（25%）; 碳水化合物（糖类）: 10克; 蛋白质: 5克'),
        'recipe/truffle-tiramisu' => array('title' => '松露提拉米苏 | LuxurEat', 'description' => '松露提拉米苏是传统甜点的精致演绎，将经典提拉米苏的绵密奶香与松露的浓郁香气融合，呈现独特而优雅的风味。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-tiramisu', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-tiramisu.webp', 'category' => '甜点食谱', 'totalTime' => 'PT30M', 'yield' => '4人份', 'ingredients' => array('250克马斯卡彭奶酪', '2个新鲜鸡蛋（蛋黄和蛋白分开）', '60克糖', '100克手指饼干', '100毫升浓缩咖啡', '1汤匙咖啡利口酒（可选）', '5-6片薄薄的黑松露片', '1茶匙松露油（可选，用于增强香气）', '适量无糖可可粉', '饼干或巧克力片，用于装饰'), 'instructions' => array('准备咖啡：将咖啡煮好后放凉。如果需要，可以加入利口酒。', '制作马斯卡彭松露奶油：将蛋黄和糖打发至颜色变浅，加入马斯卡彭奶酪并轻轻搅拌。另将蛋白打发至硬性发泡，从底部向上拌入蛋黄混合物，最后淋上少许松露油。', '将浸泡过咖啡的手指饼干和马斯卡彭奶油交替铺在模具或糕点圈中，并在每层之间放入薄薄的松露片。', '最后铺上一层奶油，撒上无糖可可粉，再用松露碎屑和酥脆饼干屑装饰。', '放入冰箱冷藏至少2小时后再食用。'), 'nutrition' => '能量: 1300千焦（约310千卡）; 饱和脂肪: 7克（35%）; 碳水化合物（糖类）: 20克; 蛋白质: 7克'),
        'recipe/olive-bruschetta' => array('title' => '番茄罗勒意式烤面包 | LuxurEat', 'description' => '成熟番茄、烤面包、蒜与特级初榨橄榄油构成最经典的意式前菜之一。重点在于面包的酥脆、番茄的新鲜度，以及最后加入的橄榄油果香。', 'lang' => 'zh', 'alternate' => 'en/recipe/olive-bruschetta', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-bruschetta.webp', 'category' => '橄榄油食谱', 'totalTime' => 'PT15M', 'yield' => '4人份', 'ingredients' => array('乡村面包160克，切4片', '成熟番茄400克', '特级初榨橄榄油30克（约33毫升）', '蒜1瓣（约5克）', '新鲜罗勒8克', '细盐2克', '现磨黑胡椒适量'), 'instructions' => array('番茄洗净、擦干并切成约1厘米小丁。加入20克橄榄油、盐和撕碎的罗勒，轻轻拌匀后静置10分钟。', '面包在烤箱、烤架或干燥平底锅中烤至两面金黄且表面酥脆。', '趁热用切开的蒜瓣轻擦面包表面，不要反复摩擦，以免蒜味压过番茄和橄榄油。', '将番茄沥去过多汁液后铺在面包上，淋剩余10克橄榄油，磨少量黑胡椒，立即上桌。'), 'nutrition' => '能量: 约850千焦 / 205千卡; 脂肪: 约9克; 其中饱和脂肪: 约1.5克; 碳水化合物: 约24克; 其中糖: 约4克; 蛋白质: 约5克; 膳食纤维: 约2.5克; 钠: 约390毫克'),
        'recipe/olive-pasta' => array('title' => '蒜香辣椒橄榄油意面 | LuxurEat', 'description' => '经典的 蒜香辣椒橄榄油意面 依靠蒜、辣椒、特级初榨橄榄油和富含淀粉的面水形成轻薄乳化，而不是用大量油把意面浸湿。', 'lang' => 'zh', 'alternate' => 'en/recipe/olive-pasta', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-aglio-olio.webp', 'category' => '橄榄油食谱', 'totalTime' => 'PT20M', 'yield' => '4人份', 'ingredients' => array('干意大利细面 320克', '特级初榨橄榄油60克（约66毫升）', '蒜4瓣（约20克），切薄片', '干辣椒1–2个，或辣椒碎1–2克', '新鲜欧芹12克，切碎，可选但推荐', '细盐约6克，用于煮面水', '保留煮面水约150毫升'), 'instructions' => array('在充足沸水中加盐，放入意面，煮至比包装标示的最佳嚼劲时间少约1分钟，舀出约150毫升面水备用。', '冷锅加入橄榄油和蒜片，以中低火缓慢加热。蒜应逐渐释放香气并变成浅金色，不要炸至深褐。', '加入辣椒，继续低火加热数十秒。', '将意面直接转入锅中，加入约80毫升面水，以中火快速翻拌。', '根据需要少量补充面水，使橄榄油和淀粉水形成乳化，并均匀附着在面条表面。', '当意面达到理想嚼劲、锅底几乎没有游离水分时关火，加入欧芹，翻拌后立即装盘。'), 'nutrition' => '能量: 约1810千焦 / 435千卡; 脂肪: 约16克; 其中饱和脂肪: 约2.5克; 碳水化合物: 约59克; 其中糖: 约3克; 蛋白质: 约11克; 膳食纤维: 约3克; 钠: 取决于煮面水盐度和沥水程度'),
        'recipe/pizza-margherita' => array('title' => '经典玛格丽特披萨 | LuxurEat', 'description' => '以耐心发酵的面团、番茄、马苏里拉奶酪与罗勒，完成一张配料克制、饼边轻盈的经典披萨。', 'lang' => 'zh', 'alternate' => 'en/recipe/pizza-margherita', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-pizza-margherita.webp', 'category' => '披萨学院', 'totalTime' => 'PT30M', 'yield' => '2张小披萨', 'ingredients' => array('500克披萨面粉', '325克水', '10克盐', '1克干酵母', '250克番茄碎', '200克马苏里拉奶酪', '罗勒与特级初榨橄榄油'), 'instructions' => array('混合面粉、水和酵母，静置20分钟后加入盐，揉至面团光滑。', '室温发酵后分割成两份，冷藏熟成8—24小时。', '回温后轻柔展开面团，保留饼边气泡，铺上番茄与奶酪。', '以烤箱可达到的最高温度烘烤至饼边膨起上色，出炉后加入罗勒与橄榄油。'), 'nutrition' => '能量: 约2850千焦（约680千卡/张）; 脂肪: 约19克; 碳水化合物: 约98克; 蛋白质: 约28克'),
        'recipe/gelato-classic' => array('title' => '经典意式手工冰淇淋 | LuxurEat', 'description' => '以均衡甜度、细腻质地与清晰风味呈现经典意式手工冰淇淋。', 'lang' => 'zh', 'alternate' => 'en/recipe/gelato-classic', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-gelato-classic.webp', 'category' => '意式手工冰淇淋', 'totalTime' => 'PT35M', 'yield' => '6人份', 'ingredients' => array('550克全脂牛奶', '150克淡奶油', '100克糖', '30克脱脂奶粉', '一小撮盐'), 'instructions' => array('混合糖与奶粉，缓慢加入牛奶和淡奶油并搅匀。', '加热至82°C左右后离火，加入盐并充分乳化。', '快速降温并冷藏至少4小时，让基底成熟。', '倒入冰淇淋机搅冻，完成后短时冷冻定型；食用前稍微回温。'), 'nutrition' => '能量: 约870千焦（约208千卡/份）; 脂肪: 约12克; 碳水化合物: 约20克; 蛋白质: 约6克'),
        'en/recipe/truffle-eggs' => array('title' => 'Truffle Eggs | LuxurEat', 'description' => 'Truffle eggs bring together the freshness of eggs and the unmistakable aroma of truffle. Ideal for breakfast, brunch, or a refined appetizer, this quick recipe delivers an elegant texture and deeply fragrant finish.', 'lang' => 'en', 'alternate' => 'recipe/truffle-eggs', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-eggs.webp', 'category' => 'Breakfast Recipe', 'totalTime' => 'PT10M', 'yield' => '1 serving', 'ingredients' => array('2 fresh eggs', '1 teaspoon extra-virgin olive oil or a small knob of butter', '4–5 thin slices of black truffle', 'A few fresh parsley leaves (optional)'), 'instructions' => array('Heat a little oil or a small knob of butter in a non-stick pan.', 'Crack in the eggs gently and cook over medium-low heat, keeping the yolks soft.', 'Season with a little salt and pepper.', 'Just before the eggs are ready, arrange the truffle slices on top and let the heat release their aroma.'), 'nutrition' => 'Energy: 950 kJ (about 225 kcal); Saturated fat: 5 g (25%); Carbohydrates (sugars): 1 g; Protein: 12 g'),
        'en/recipe/truffle-toast' => array('title' => 'Poached Egg and Truffle Toast | LuxurEat', 'description' => 'Crisp toast with poached egg and truffle is a refined table bite, perfect for entertaining or an elegant Sunday brunch. Golden bread, silky egg, and deep truffle aroma create an irresistible balance of flavor and texture.', 'lang' => 'en', 'alternate' => 'recipe/truffle-toast', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-toast.webp', 'category' => 'Breakfast Recipe', 'totalTime' => 'PT15M', 'yield' => '2 servings', 'ingredients' => array('4 slices country bread or baguette', '2 fresh eggs', '1 tablespoon white vinegar', '2 tablespoons fresh spreadable cheese, such as robiola or ricotta', '5–6 thin slices of black truffle', '1 teaspoon capers (optional)', 'Fresh basil leaves', 'Extra-virgin olive oil, as needed', 'Salt and pepper, to taste'), 'instructions' => array('Toast the bread until crisp in a hot grill pan or non-stick pan.', 'Poach the eggs: bring a pan of water and the vinegar to a gentle boil. Stir the water into a whirlpool, slide an egg into the center, and cook for 2–3 minutes until the white is set and the yolk remains soft.', 'Assemble the toast: spread the cheese over each slice, add the poached egg, and finish with the truffle slices.', 'Garnish with a little olive oil, capers if using, and fresh basil. Season lightly with salt and pepper.'), 'nutrition' => 'Energy: 1,300 kJ (about 310 kcal); Saturated fat: 6 g (30%); Carbohydrates (sugars): 3 g; Protein: 12 g'),
        'en/recipe/truffle-tagliolini' => array('title' => 'Tagliolini with White or Black Truffle | LuxurEat', 'description' => 'An elegant, memorable first course made for special occasions. Fresh egg pasta is tossed with butter and Parmesan, then finished generously with white or black truffle for an unforgettable aroma.', 'lang' => 'en', 'alternate' => 'recipe/truffle-tagliolini', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-tagliolini.webp', 'category' => 'First Courses', 'totalTime' => 'PT25M', 'yield' => '2 servings', 'ingredients' => array('250 g filled pasta, with cheese or mushroom filling', '30 g butter', '2 tablespoons fresh cream', '4–5 slices fresh black truffle', '1 tablespoon grated Parmesan', 'Fresh chopped parsley, as needed', 'Salt and pepper, to taste'), 'instructions' => array('Bring a pan of salted water to the boil and cook the pasta according to the package directions, or for 3–4 minutes if fresh.', 'Melt the butter over low heat. Add the cream and one tablespoon of pasta water, stirring until smooth and thickened.', 'Drain the pasta gently and add it to the sauce. Stir in the Parmesan and toss for 1–2 minutes.', 'Plate and finish with fresh black truffle slices and a little chopped parsley.'), 'nutrition' => 'Energy: 1,750 kJ (about 420 kcal); Saturated fat: 9 g (45%); Carbohydrates (sugars): 3 g; Protein: 14 g'),
        'en/recipe/truffle-ravioli' => array('title' => 'Ravioli with Truffle Cream Sauce | LuxurEat', 'description' => 'Ravioli with truffle cream sauce brings together the delicacy of fresh pasta and the unmistakable aroma of truffle.', 'lang' => 'en', 'alternate' => 'recipe/truffle-ravioli', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-ravioli.webp', 'category' => 'First Courses', 'totalTime' => 'PT25M', 'yield' => '2 servings', 'ingredients' => array('250 g ravioli, with cheese or mushroom filling', '30 g butter', '2 tablespoons fresh cream', '4–5 slices fresh black truffle', '1 tablespoon grated Parmesan', 'Fresh chopped parsley, as needed', 'Salt and pepper, to taste'), 'instructions' => array('Bring a pan of salted water to the boil and cook the ravioli according to the package directions, or for 3–4 minutes if fresh.', 'Melt the butter over low heat. Add the cream and one tablespoon of pasta water, stirring until smooth and thickened.', 'Drain the ravioli gently and add them to the sauce. Stir in the Parmesan and toss for 1–2 minutes.', 'Plate and finish with fresh black truffle slices and a little chopped parsley.'), 'nutrition' => 'Energy: 1,750 kJ (about 420 kcal); Saturated fat: 9 g (45%); Carbohydrates (sugars): 3 g; Protein: 14 g'),
        'en/recipe/black-truffle-risotto' => array('title' => 'Black Truffle Risotto | LuxurEat', 'description' => 'Black truffle risotto combines the silky richness of creamy rice with the intense flavor of truffle in a dish that is simple to make yet refined.', 'lang' => 'en', 'alternate' => 'recipe/black-truffle-risotto', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-black-truffle-risotto.webp', 'category' => 'First Courses', 'totalTime' => 'PT30M', 'yield' => '2 servings', 'ingredients' => array('160 g Carnaroli rice', '20 g butter', '1 tablespoon extra-virgin olive oil', '30 g grated Parmesan', '½ small onion or shallot', '50 ml dry white wine', '4–5 thin slices fresh black truffle', 'Salt and pepper, to taste'), 'instructions' => array('Finely chop the onion and soften it over low heat with the butter and a little oil.', 'Add the rice and toast for 2–3 minutes, until the grains become translucent.', 'Pour in the white wine and let it evaporate completely.', 'Add hot stock gradually, one ladle at a time, stirring continuously. Cook for about 15–18 minutes.', 'Remove from the heat and stir in the Parmesan and a small knob of butter until smooth and creamy.', 'Plate and finish with fresh black truffle and a little pepper.'), 'nutrition' => 'Energy: 1,800 kJ (about 430 kcal); Saturated fat: 8 g (40%); Carbohydrates (sugars): 2 g; Protein: 10 g'),
        'en/recipe/mushroom-soup' => array('title' => 'Creamy Mushroom Soup | LuxurEat', 'description' => 'A warm and comforting first course for autumn. This silky mushroom soup carries deep woodland aromas and is especially good with golden croutons or a drizzle of truffle oil.', 'lang' => 'en', 'alternate' => 'recipe/mushroom-soup', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-mushroom-soup.webp', 'category' => 'First Courses', 'totalTime' => 'PT35M', 'yield' => '2 servings', 'ingredients' => array('300 g mixed fresh mushrooms, such as shiitake, porcini, or button mushrooms', '1 medium potato', '½ onion', '1 garlic clove', '2 tablespoons extra-virgin olive oil', '400 ml hot vegetable stock', '2 tablespoons fresh cream (optional)', 'Salt and pepper, to taste', 'Fresh chopped parsley, to garnish', 'Croutons, to serve'), 'instructions' => array('Wipe the mushrooms with a damp cloth and slice them. Peel and dice the potato; finely chop the onion and garlic.', 'Heat the olive oil and sauté the onion and garlic for 2–3 minutes. Add the mushrooms and potato and cook for about 5 minutes.', 'Pour in the hot vegetable stock, cover, and simmer over medium heat for about 20 minutes, until tender.', 'Remove from the heat and blend until smooth. Stir in the cream for a richer texture, if desired.', 'Season with salt and pepper, garnish with parsley, and serve hot with golden croutons or a drizzle of truffle oil.'), 'nutrition' => 'Energy: 870 kJ (about 210 kcal); Saturated fat: 2 g (10%); Carbohydrates (sugars): 14 g (including 4 g sugars); Protein: 6 g'),
        'en/recipe/beef-carpaccio-scallop-truffle' => array('title' => 'Beef Carpaccio with Scallops and Truffle | LuxurEat', 'description' => 'An elegant dish that brings together the rich flavor of thinly sliced beef, the tenderness of scallops, and the unmistakable fragrance of black truffle.', 'lang' => 'en', 'alternate' => 'recipe/beef-carpaccio-scallop-truffle', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-beef-carpaccio-scallop-truffle.webp', 'category' => 'Main Courses', 'totalTime' => 'PT15M', 'yield' => '2 servings', 'ingredients' => array('120 g very thinly sliced beef carpaccio', '4 fresh scallops', '4–5 slices black truffle', '1 tablespoon extra-virgin olive oil', '1 teaspoon lemon juice', 'Salt and pepper, to taste', 'A few mixed salad leaves, such as mesclun or rocket', 'A few drops green sauce (optional)'), 'instructions' => array('Warm a little oil in a non-stick pan and sear the scallops for 1 minute on each side, keeping the centers tender.', 'Arrange the beef slices evenly in a single layer on a flat plate.', 'Place the scallops in the center of the beef carpaccio.', 'Drizzle with olive oil and lemon juice, then season with salt and pepper. Finish with black truffle, salad leaves, and, if desired, a few drops of green sauce or pea cream.'), 'nutrition' => 'Energy: 850 kJ (about 200 kcal); Saturated fat: 2 g (10%); Carbohydrates (sugars): 1 g; Protein: 22 g'),
        'en/recipe/shrimp-tartare-truffle' => array('title' => 'Shrimp Tartare with Crisp Vegetables and Truffle | LuxurEat', 'description' => 'A fresh, refined dish in which the sweetness of raw shrimp meets crisp vegetables and the deep aroma of truffle, balancing visual elegance with bright flavor.', 'lang' => 'en', 'alternate' => 'recipe/shrimp-tartare-truffle', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-shrimp-tartare-truffle.webp', 'category' => 'Main Courses', 'totalTime' => 'PT20M', 'yield' => '2 servings', 'ingredients' => array('200 g fresh red shrimp, peeled and frozen', '4–5 slices fresh black truffle', '6 cherry tomatoes', '½ cucumber', '¼ yellow bell pepper', '½ lime', '1 tablespoon extra-virgin olive oil', 'Salt and pepper, to taste', 'Salad leaves and edible flowers, to garnish'), 'instructions' => array('Finely chop the shrimp with a knife until delicate but not pasty. Season with olive oil, lime juice, salt, and pepper.', 'Cut the cucumber, cherry tomatoes, and yellow pepper into very small dice, creating a colorful, crisp vegetable mixture.', 'Use a ring mould to assemble the tartare, placing the vegetables at the base and the shrimp tartare on top.', 'Finish with truffle slices, edible flowers, a little oil, and, if desired, a few drops of balsamic reduction or green emulsion.'), 'nutrition' => 'Energy: 600 kJ (about 145 kcal); Saturated fat: 1 g (5%); Carbohydrates (sugars): 2 g; Protein: 14 g'),
        'en/recipe/sweet-bread-butter-caviar' => array('title' => 'Sweet Bread with Butter and Caviar | LuxurEat', 'description' => 'Sweet bread with butter and caviar is an elegant bite in which soft brioche, rich butter, and savory caviar create a distinctive contrast of sweetness and salinity.', 'lang' => 'en', 'alternate' => 'recipe/sweet-bread-butter-caviar', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-sweet-bread-butter-caviar.webp', 'category' => 'Dessert Recipe', 'totalTime' => 'PT10M', 'yield' => '4 servings', 'ingredients' => array('4 pieces sweet bread, such as brioche', '40 g high-quality butter, preferably unsalted', '20 g caviar, such as sturgeon or Beluga caviar', 'A little icing sugar, optional, to garnish'), 'instructions' => array('Cut the brioche into 4 cubes or thick slices, keeping the bread soft.', 'Soften the butter at room temperature and spread it evenly over the bread.', 'Gently place the caviar over each buttered piece of bread.', 'Arrange on an elegant plate and, for a stronger sweet-salty contrast, finish with a very light dusting of icing sugar.'), 'nutrition' => 'Energy: 720 kJ (about 170 kcal); Saturated fat: 5 g (25%); Carbohydrates (sugars): 10 g; Protein: 5 g'),
        'en/recipe/truffle-tiramisu' => array('title' => 'Truffle Tiramisu | LuxurEat', 'description' => 'Truffle tiramisu is a refined interpretation of the traditional dessert, combining silky mascarpone with the deep aroma of truffle for an unexpected, elegant finish.', 'lang' => 'en', 'alternate' => 'recipe/truffle-tiramisu', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-tiramisu.webp', 'category' => 'Dessert Recipe', 'totalTime' => 'PT30M', 'yield' => '4 servings', 'ingredients' => array('250 g mascarpone', '2 fresh eggs, yolks and whites separated', '60 g sugar', '100 g ladyfingers', '100 ml espresso', '1 tablespoon coffee liqueur, optional', '5–6 thin slices black truffle', '1 teaspoon truffle oil, optional, to intensify the aroma', 'Unsweetened cocoa powder, as needed', 'Biscuits or chocolate shards, to decorate'), 'instructions' => array('Prepare the coffee and leave it to cool. Add the liqueur if desired.', 'Beat the egg yolks with the sugar until pale, then gently mix in the mascarpone. In a separate bowl, whisk the egg whites to stiff peaks and fold them into the mascarpone mixture from the bottom upward. Finish with a little truffle oil.', 'Alternate coffee-soaked ladyfingers and mascarpone cream in a mould or pastry ring, adding thin truffle slices between the layers.', 'Finish with a layer of cream and unsweetened cocoa, then decorate with truffle shavings and crisp biscuit crumbs.', 'Refrigerate for at least 2 hours before serving.'), 'nutrition' => 'Energy: 1,300 kJ (about 310 kcal); Saturated fat: 7 g (35%); Carbohydrates (sugars): 20 g; Protein: 7 g'),
        'en/recipe/olive-bruschetta' => array('title' => 'Tomato and Basil Bruschetta | LuxurEat', 'description' => 'Ripe tomatoes, toasted bread, garlic and extra-virgin olive oil make one of Italy’s classic antipasti. Crisp bread, fresh tomato and the fruitiness of the finishing oil are the essentials.', 'lang' => 'en', 'alternate' => 'recipe/olive-bruschetta', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-bruschetta.webp', 'category' => 'Olive Oil Recipes', 'totalTime' => 'PT15M', 'yield' => 'Serves 4', 'ingredients' => array('160 g country bread, cut into 4 slices', '400 g ripe tomatoes', '30 g extra-virgin olive oil (about 33 ml)', '1 garlic clove (about 5 g)', '8 g fresh basil', '2 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Wash and dry the tomatoes, then cut into 1 cm dice. Mix gently with 20 g of the oil, the salt and torn basil; rest for 10 minutes.', 'Toast the bread in an oven, on a grill or in a dry frying pan until golden and crisp on both sides.', 'While hot, rub each slice lightly with the cut garlic. Do not over-rub or the garlic will dominate.', 'Drain excess tomato juice, spoon the mixture over the bread, drizzle with the remaining 10 g oil, add black pepper and serve immediately.'), 'nutrition' => 'Energy: About 850 kJ / 205 kcal; Fat: About 9 g; of which saturates: About 1.5 g; Carbohydrate: About 24 g; of which sugars: About 4 g; Protein: About 5 g; Fibre: About 2.5 g; Sodium: About 390 mg'),
        'en/recipe/olive-pasta' => array('title' => 'Spaghetti with Garlic, Olive Oil and Chilli | LuxurEat', 'description' => 'Classic Aglio, Olio e Peperoncino uses garlic, chilli, extra-virgin olive oil and starchy pasta water to form a light emulsion rather than soaking the pasta in oil.', 'lang' => 'en', 'alternate' => 'recipe/olive-pasta', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-aglio-olio.webp', 'category' => 'Olive Oil Recipes', 'totalTime' => 'PT20M', 'yield' => 'Serves 4', 'ingredients' => array('320 g dried spaghetti', '60 g extra-virgin olive oil (about 66 ml)', '4 garlic cloves (about 20 g), thinly sliced', '1–2 dried chillies, or 1–2 g chilli flakes', '12 g fresh parsley, chopped, optional but recommended', 'About 6 g fine salt for the pasta water', 'About 150 ml reserved pasta water'), 'instructions' => array('Cook the pasta in plenty of salted boiling water until about 1 minute short of the package’s al dente time. Reserve about 150 ml pasta water.', 'Add the oil and garlic to a cold pan and warm slowly over medium-low heat. Let the garlic become pale gold, never dark brown.', 'Add the chilli and cook over low heat for a few seconds.', 'Transfer the pasta directly to the pan, add about 80 ml pasta water and toss briskly over medium heat.', 'Add small amounts of pasta water as needed so the oil and starch emulsify and coat the spaghetti.', 'When the pasta is al dente and almost no free liquid remains, turn off the heat, add parsley, toss and serve immediately.'), 'nutrition' => 'Energy: About 1,810 kJ / 435 kcal; Fat: About 16 g; of which saturates: About 2.5 g; Carbohydrate: About 59 g; of which sugars: About 3 g; Protein: About 11 g; Fibre: About 3 g; Sodium: Depends on pasta-water salinity and draining'),
        'en/recipe/pizza-margherita' => array('title' => 'Classic Margherita pizza | LuxurEat', 'description' => 'Patiently fermented dough, tomato, mozzarella and basil make a restrained classic with an airy rim and clear wheat flavour.', 'lang' => 'en', 'alternate' => 'recipe/pizza-margherita', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-pizza-margherita.webp', 'category' => 'Pizza Academy', 'totalTime' => 'PT30M', 'yield' => '2 small pizzas', 'ingredients' => array('500 g pizza flour', '325 g water', '10 g salt', '1 g dry yeast', '250 g crushed tomato', '200 g mozzarella', 'Basil and extra-virgin olive oil'), 'instructions' => array('Mix flour, water and yeast; rest for 20 minutes, add salt and knead until smooth.', 'Bulk-ferment, divide into two balls and cold-proof for 8–24 hours.', 'Bring to room temperature, open gently while preserving the rim, then add tomato and mozzarella.', 'Bake at the oven\'s highest temperature until risen and charred; finish with basil and olive oil.'), 'nutrition' => 'Energy: About 2,850 kJ (680 kcal) per pizza; Fat: About 19 g; Carbohydrate: About 98 g; Protein: About 28 g'),
        'en/recipe/gelato-classic' => array('title' => 'Classic artisan Gelato | LuxurEat', 'description' => 'Balanced sweetness, fine texture and clear flavour define this classic artisan Gelato.', 'lang' => 'en', 'alternate' => 'recipe/gelato-classic', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-gelato-classic.webp', 'category' => 'Artisan Gelato', 'totalTime' => 'PT35M', 'yield' => '6 servings', 'ingredients' => array('550 g whole milk', '150 g cream', '100 g sugar', '30 g skimmed milk powder', 'A small pinch of salt'), 'instructions' => array('Combine sugar and milk powder, then whisk gradually into the milk and cream.', 'Heat to about 82°C, remove from the heat and emulsify with salt.', 'Cool rapidly and mature in the refrigerator for at least 4 hours.', 'Churn, freeze briefly to set and temper slightly before serving.'), 'nutrition' => 'Energy: About 870 kJ (208 kcal) per serving; Fat: About 12 g; Carbohydrate: About 20 g; Protein: About 6 g'),
        'recipe/olive-panzanella' => array('title' => '托斯卡纳面包沙拉 | LuxurEat', 'description' => '托斯卡纳面包沙拉 是托斯卡纳及意大利中部经典的陈面包沙拉。略微回软的面包吸收番茄、醋和特级初榨橄榄油的风味，形成清爽又保留结构感的夏季料理。', 'lang' => 'zh', 'alternate' => 'en/recipe/olive-panzanella', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-panzanella.webp', 'category' => '橄榄油食谱', 'totalTime' => 'PT55M', 'yield' => '4人份', 'ingredients' => array('陈乡村面包240克', '成熟番茄500克', '黄瓜180克', '红洋葱80克', '新鲜罗勒12克', '特级初榨橄榄油40克（约44毫升）', '红酒醋20克', '冷饮用水约120毫升', '细盐3克', '现磨黑胡椒适量'), 'instructions' => array('面包切成约1厘米厚片或较大的块，均匀洒上冷水和约一半红酒醋。', '静置10–15分钟，使面包回软，但不要完全泡烂。', '番茄切块，黄瓜切片，红洋葱切薄片；洋葱过辣时可在冷水中浸泡10分钟后彻底沥干。', '轻轻挤去面包中过多水分，再用手撕成大小均匀的块。', '将面包、番茄、黄瓜、洋葱和罗勒混合。', '加入剩余红酒醋、橄榄油、盐和黑胡椒，轻轻拌匀。', '冷藏静置约30分钟。上桌前再次拌匀，并微调酸度和盐度。'), 'nutrition' => '能量: 约1160千焦 / 280千卡; 脂肪: 约12克; 其中饱和脂肪: 约2克; 碳水化合物: 约37克; 其中糖: 约7克; 蛋白质: 约7克; 膳食纤维: 约4克; 钠: 约530毫克'),
        'en/recipe/olive-panzanella' => array('title' => 'Tuscan Panzanella | LuxurEat', 'description' => 'Panzanella is Tuscany’s classic stale-bread salad. Softened bread absorbs tomato, vinegar and extra-virgin olive oil while retaining enough structure for a fresh summer dish.', 'lang' => 'en', 'alternate' => 'recipe/olive-panzanella', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-panzanella.webp', 'category' => 'Olive Oil Recipes', 'totalTime' => 'PT55M', 'yield' => 'Serves 4', 'ingredients' => array('240 g stale country bread', '500 g ripe tomatoes', '180 g cucumber', '80 g red onion', '12 g fresh basil', '40 g extra-virgin olive oil (about 44 ml)', '20 g red-wine vinegar', 'About 120 ml cold potable water', '3 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Cut the bread into 1 cm slices or large pieces and sprinkle evenly with cold water and half the vinegar.', 'Rest for 10–15 minutes so the bread softens without becoming mushy.', 'Cut the tomatoes, slice the cucumber and thinly slice the onion. If the onion is too sharp, soak in cold water for 10 minutes and drain thoroughly.', 'Gently squeeze excess water from the bread and tear it into even pieces.', 'Combine bread, tomatoes, cucumber, onion and basil.', 'Add the remaining vinegar, oil, salt and pepper and toss gently.', 'Refrigerate for about 30 minutes. Toss again before serving and adjust acidity and salt.'), 'nutrition' => 'Energy: About 1,160 kJ / 280 kcal; Fat: About 12 g; of which saturates: About 2 g; Carbohydrate: About 37 g; of which sugars: About 7 g; Protein: About 7 g; Fibre: About 4 g; Sodium: About 530 mg'),
        'recipe/olive-pinzimonio' => array('title' => '意式生蔬菜配橄榄油蘸汁 | LuxurEat', 'description' => '意式生蔬菜配橄榄油蘸汁 以新鲜、脆爽的时令蔬菜搭配特级初榨橄榄油、盐、黑胡椒和少量柠檬汁，直接展现优质橄榄油的果香、苦味和辛香感。', 'lang' => 'zh', 'alternate' => 'en/recipe/olive-pinzimonio', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-pinzimonio.webp', 'category' => '橄榄油食谱', 'totalTime' => 'PT20M', 'yield' => '4人份', 'ingredients' => array('胡萝卜160克', '西芹120克', '茴香球200克', '红甜椒150克', '小萝卜100克', '菊苣或红菊苣120克', '特级初榨橄榄油60克（约66毫升）', '新鲜柠檬汁20克', '细盐2克', '现磨黑胡椒适量'), 'instructions' => array('所有蔬菜在流动饮用水下充分洗净并彻底擦干。', '胡萝卜、西芹、茴香和甜椒切成长条，小萝卜对半切，菊苣分成适合手持的叶片。', '将橄榄油、柠檬汁、盐和黑胡椒放入小碗，用叉子快速搅拌形成暂时乳化的蘸汁。', '将蔬菜分类摆放在冷盘中，蘸汁按人数分装成小碗，现蘸现食。'), 'nutrition' => '能量: 约790千焦 / 190千卡; 脂肪: 约15克; 其中饱和脂肪: 约2克; 碳水化合物: 约13克; 其中糖: 约7克; 蛋白质: 约3克; 膳食纤维: 约5克; 钠: 约300毫克'),
        'en/recipe/olive-pinzimonio' => array('title' => 'Italian Raw Vegetables with Olive-Oil Dip | LuxurEat', 'description' => 'Pinzimonio pairs crisp seasonal vegetables with extra-virgin olive oil, salt, pepper and a little lemon, showing the oil’s fruit, bitterness and pepperiness with almost no cooking.', 'lang' => 'en', 'alternate' => 'recipe/olive-pinzimonio', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-olive-pinzimonio.webp', 'category' => 'Olive Oil Recipes', 'totalTime' => 'PT20M', 'yield' => 'Serves 4', 'ingredients' => array('160 g carrots', '120 g celery', '200 g fennel bulb', '150 g red sweet pepper', '100 g radishes', '120 g chicory or radicchio', '60 g extra-virgin olive oil (about 66 ml)', '20 g fresh lemon juice', '2 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Wash all vegetables thoroughly under potable running water and dry completely.', 'Cut carrots, celery, fennel and pepper into batons, halve the radishes and separate chicory into hand-held leaves.', 'Whisk the oil, lemon juice, salt and pepper with a fork to make a temporary emulsion.', 'Arrange the vegetables by type on a chilled platter, divide the dip into individual bowls and dip only when eating.'), 'nutrition' => 'Energy: About 790 kJ / 190 kcal; Fat: About 15 g; of which saturates: About 2 g; Carbohydrate: About 13 g; of which sugars: About 7 g; Protein: About 3 g; Fibre: About 5 g; Sodium: About 300 mg'),
        'recipe/light-fennel-orange-salad' => array('title' => '西西里茴香橙子黑橄榄沙拉 | LuxurEat', 'description' => '清脆茴香、鲜甜橙子与黑橄榄组成西西里风格沙拉，以橙汁和特级初榨橄榄油完成清爽调味。', 'lang' => 'zh', 'alternate' => 'en/recipe/light-fennel-orange-salad', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-light-fennel-orange-salad.webp', 'category' => '健康轻食', 'totalTime' => 'PT20M', 'yield' => '4人份', 'ingredients' => array('茴香球500克', '橙子400克（约3个，以去皮后重量计）', '去核黑橄榄80克', '特级初榨橄榄油25克（约28毫升）', '新鲜橙汁20克', '细盐1.5克', '现磨黑胡椒适量', '茴香叶少量，装饰用'), 'instructions' => array('茴香洗净并彻底擦干，保留少量茴香叶；球茎纵向切半后切成均匀薄片。', '橙子去皮并彻底去除白色筋膜，在碗上方切出果肉瓣并保留流出的果汁。', '黑橄榄对半切开。', '将20克橙汁、橄榄油、盐和黑胡椒搅拌成调味汁。', '茴香、橙子和橄榄轻轻混合，淋入调味汁再次拌匀。', '以茴香叶装饰并尽快上桌。'), 'nutrition' => '能量: 约640千焦 / 155千卡; 脂肪: 约9克; 其中饱和脂肪: 约1.5克; 碳水化合物: 约18克; 其中糖: 约12克; 蛋白质: 约3克; 膳食纤维: 约6克; 钠: 约260毫克'),
        'en/recipe/light-fennel-orange-salad' => array('title' => 'Sicilian Fennel, Orange and Black Olive Salad | LuxurEat', 'description' => 'Crisp fennel, sweet orange and black olives form a Sicilian-style salad finished with fresh orange juice and extra-virgin olive oil.', 'lang' => 'en', 'alternate' => 'recipe/light-fennel-orange-salad', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-light-fennel-orange-salad.webp', 'category' => 'Healthy Light Meals', 'totalTime' => 'PT20M', 'yield' => 'Serves 4', 'ingredients' => array('500 g fennel bulbs', '400 g oranges (about 3, peeled weight)', '80 g pitted black olives', '25 g extra-virgin olive oil (about 28 ml)', '20 g fresh orange juice', '1.5 g fine salt', 'Freshly ground black pepper', 'A few fennel fronds to garnish'), 'instructions' => array('Wash and dry the fennel, reserving a few fronds. Halve the bulbs lengthways and slice evenly and thinly.', 'Peel the oranges, remove all white pith and cut out the segments over a bowl, retaining the juice.', 'Halve the olives.', 'Whisk 20 g orange juice with the oil, salt and pepper.', 'Gently combine fennel, orange and olives, add the dressing and toss again.', 'Garnish with fennel fronds and serve promptly.'), 'nutrition' => 'Energy: About 640 kJ / 155 kcal; Fat: About 9 g; of which saturates: About 1.5 g; Carbohydrate: About 18 g; of which sugars: About 12 g; Protein: About 3 g; Fibre: About 6 g; Sodium: About 260 mg'),
        'recipe/light-grilled-zucchini' => array('title' => '香草柠檬腌烤西葫芦 | LuxurEat', 'description' => '薄切西葫芦经高温快速烤制，再以柠檬、薄荷、蒜与橄榄油短暂腌渍，保留清脆质地。', 'lang' => 'zh', 'alternate' => 'en/recipe/light-grilled-zucchini', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-light-grilled-zucchini.webp', 'category' => '健康轻食', 'totalTime' => 'PT35M', 'yield' => '4人份', 'ingredients' => array('西葫芦700克', '特级初榨橄榄油30克（约33毫升）', '新鲜柠檬汁25克', '蒜1瓣（约5克），切末', '新鲜薄荷8克，或欧芹10克', '细盐2.5克', '现磨黑胡椒适量'), 'instructions' => array('西葫芦洗净擦干，纵向切成4–5毫米厚片。', '烤盘或烤架充分预热。', '西葫芦分批铺开，每面烤约1–2分钟至出现烤痕且刚刚变软，不要堆叠。', '橄榄油、柠檬汁、蒜、盐和黑胡椒混合，加入切碎薄荷。', '将温热西葫芦分层摆放，每层刷少量调味汁。', '静置约15分钟入味；若不立即食用，应冷藏保存。'), 'nutrition' => '能量: 约485千焦 / 115千卡; 脂肪: 约8克; 其中饱和脂肪: 约1克; 碳水化合物: 约7克; 其中糖: 约5克; 蛋白质: 约3克; 膳食纤维: 约2克; 钠: 约260毫克'),
        'en/recipe/light-grilled-zucchini' => array('title' => 'Marinated Grilled Zucchini | LuxurEat', 'description' => 'Thin courgette slices are grilled quickly, then briefly marinated with lemon, mint, garlic and olive oil to retain their bite.', 'lang' => 'en', 'alternate' => 'recipe/light-grilled-zucchini', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-light-grilled-zucchini.webp', 'category' => 'Healthy Light Meals', 'totalTime' => 'PT35M', 'yield' => 'Serves 4', 'ingredients' => array('700 g courgettes', '30 g extra-virgin olive oil (about 33 ml)', '25 g fresh lemon juice', '1 garlic clove (about 5 g), minced', '8 g fresh mint, or 10 g parsley', '2.5 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Wash and dry the courgettes, then slice lengthways 4–5 mm thick.', 'Preheat a griddle or grill thoroughly.', 'Grill in uncrowded batches for 1–2 minutes per side, until marked and just tender.', 'Mix the oil, lemon juice, garlic, salt and pepper, then add chopped mint.', 'Layer the warm courgettes, brushing each layer with a little dressing.', 'Rest for about 15 minutes. Refrigerate if not serving immediately.'), 'nutrition' => 'Energy: About 485 kJ / 115 kcal; Fat: About 8 g; of which saturates: About 1 g; Carbohydrate: About 7 g; of which sugars: About 5 g; Protein: About 3 g; Fibre: About 2 g; Sodium: About 260 mg'),
        'recipe/light-sea-bass-acqua-pazza' => array('title' => '意式番茄水煮海鲈鱼 | LuxurEat', 'description' => '海鲈鱼在番茄、蒜、白葡萄酒和少量水构成的浅汤中轻柔煮熟，是坎帕尼亚经典的清淡鱼料理。', 'lang' => 'zh', 'alternate' => 'en/recipe/light-sea-bass-acqua-pazza', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-light-sea-bass-acqua-pazza.webp', 'category' => '健康轻食', 'totalTime' => 'PT30M', 'yield' => '4人份', 'ingredients' => array('去骨海鲈鱼排600克', '樱桃番茄350克，对半切', '水180毫升', '干白葡萄酒80毫升，可选', '特级初榨橄榄油25克', '蒜2瓣（约10克）', '新鲜欧芹15克', '细盐3克', '现磨黑胡椒适量'), 'instructions' => array('鱼排擦干，确认无残留鱼刺，以盐和黑胡椒轻调味。', '宽锅加入橄榄油和拍裂的蒜，以中低火加热至出香。', '加入番茄，中火翻炒约3分钟。', '倒入白葡萄酒并煮1分钟使酒精挥发；不用酒时直接加等量水。', '加入180毫升水，煮至轻微沸腾。', '鱼排皮面朝下放入浅汤，盖锅以小火煮约6–8分钟。', '根据厚度轻轻翻面，继续煮2–4分钟，中心温度达到63°C。', '取出鱼排，汤汁按需稍微收浓。', '加入欧芹，调整盐和黑胡椒。', '将番茄和浅汤浇在鱼排周围，立即上桌。'), 'nutrition' => '能量: 约1045千焦 / 250千卡; 脂肪: 约10克; 其中饱和脂肪: 约2克; 碳水化合物: 约5克; 其中糖: 约3克; 蛋白质: 约36克; 膳食纤维: 约1.5克; 钠: 约440毫克'),
        'en/recipe/light-sea-bass-acqua-pazza' => array('title' => 'Sea Bass all’Acqua Pazza | LuxurEat', 'description' => 'Sea bass cooks gently in a shallow broth of tomato, garlic, white wine and water in this light Campanian classic.', 'lang' => 'en', 'alternate' => 'recipe/light-sea-bass-acqua-pazza', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-light-sea-bass-acqua-pazza.webp', 'category' => 'Healthy Light Meals', 'totalTime' => 'PT30M', 'yield' => 'Serves 4', 'ingredients' => array('600 g boneless sea-bass fillets', '350 g cherry tomatoes, halved', '180 ml water', '80 ml dry white wine, optional', '25 g extra-virgin olive oil', '2 garlic cloves (about 10 g)', '15 g fresh parsley', '3 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Pat the fish dry, remove any pin bones and season lightly with salt and pepper.', 'Warm the oil and crushed garlic in a wide pan over medium-low heat.', 'Add the tomatoes and cook over medium heat for about 3 minutes.', 'Add the wine and boil for 1 minute; if omitting it, add the same volume of water.', 'Add 180 ml water and bring to a gentle simmer.', 'Place the fish skin-side down in the shallow broth, cover and cook gently for 6–8 minutes.', 'Depending on thickness, turn carefully and cook for another 2–4 minutes, to 63°C at the centre.', 'Remove the fish and reduce the broth slightly if needed.', 'Add parsley and adjust salt and pepper.', 'Spoon the tomatoes and broth around the fish and serve immediately.'), 'nutrition' => 'Energy: About 1,045 kJ / 250 kcal; Fat: About 10 g; of which saturates: About 2 g; Carbohydrate: About 5 g; of which sugars: About 3 g; Protein: About 36 g; Fibre: About 1.5 g; Sodium: About 440 mg'),
        'recipe/family-spaghetti-pomodoro' => array('title' => '经典番茄意面 | LuxurEat', 'description' => '以番茄、蒜、罗勒和橄榄油完成的意大利国民经典，材料容易获得，关键在于酱汁收浓与面水乳化。', 'lang' => 'zh', 'alternate' => 'en/recipe/family-spaghetti-pomodoro', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-family-spaghetti-pomodoro.webp', 'category' => '适合中国家庭的意大利菜', 'totalTime' => 'PT35M', 'yield' => '4人份', 'ingredients' => array('干意大利细面 320克', '番茄泥 500克', '特级初榨橄榄油30克', '蒜1瓣（约5克）', '新鲜罗勒12克', '细盐约7克（含煮面水）', '保留煮面水约150毫升'), 'instructions' => array('宽锅中加入橄榄油和拍裂的蒜，以中低火加热至出香，不要上色过深。', '加入番茄泥和少量盐，中小火煮约15–20分钟，期间偶尔搅拌。', '加入一半罗勒，继续煮至酱汁浓稠而仍有流动性。', '另锅烧足量水，加盐后放入意面。', '意面煮至比包装标示的最佳嚼劲时间少约1分钟，保留约150毫升面水。', '意面转入番茄酱锅，加入少量面水，中火翻拌约1分钟。', '按需要继续少量补充面水，使酱汁均匀包裹面条。', '关火，加入剩余罗勒并拌匀。', '装盘后可淋少量橄榄油，立即上桌。'), 'nutrition' => '能量: 约1530千焦 / 365千卡; 脂肪: 约9克; 其中饱和脂肪: 约1.5克; 碳水化合物: 约62克; 其中糖: 约7克; 蛋白质: 约12克; 膳食纤维: 约4克; 钠: 取决于煮面水盐度'),
        'en/recipe/family-spaghetti-pomodoro' => array('title' => 'Classic Spaghetti al Pomodoro | LuxurEat', 'description' => 'This Italian staple uses tomato, garlic, basil and olive oil. The ingredients are accessible; reducing the sauce and emulsifying with pasta water make the difference.', 'lang' => 'en', 'alternate' => 'recipe/family-spaghetti-pomodoro', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-family-spaghetti-pomodoro.webp', 'category' => 'Italian Food for Chinese Homes', 'totalTime' => 'PT35M', 'yield' => 'Serves 4', 'ingredients' => array('320 g dried spaghetti', '500 g tomato passata', '30 g extra-virgin olive oil', '1 garlic clove (about 5 g)', '12 g fresh basil', 'About 7 g fine salt, including pasta water', 'About 150 ml reserved pasta water'), 'instructions' => array('Warm the oil and crushed garlic over medium-low heat until aromatic, without deep browning.', 'Add the passata and a little salt; simmer for 15–20 minutes, stirring occasionally.', 'Add half the basil and cook until thick but still fluid.', 'Bring plenty of water to the boil in another pot, salt it and add the pasta.', 'Cook until about 1 minute short of the package’s al dente time and reserve 150 ml pasta water.', 'Transfer the pasta to the sauce, add a little pasta water and toss over medium heat for 1 minute.', 'Add more pasta water in small amounts until the sauce coats the spaghetti evenly.', 'Turn off the heat, add the remaining basil and toss.', 'Plate, finish with a little oil if desired and serve immediately.'), 'nutrition' => 'Energy: About 1,530 kJ / 365 kcal; Fat: About 9 g; of which saturates: About 1.5 g; Carbohydrate: About 62 g; of which sugars: About 7 g; Protein: About 12 g; Fibre: About 4 g; Sodium: Depends on pasta-water salinity'),
        'recipe/family-zucchini-frittata' => array('title' => '西葫芦意式煎蛋 | LuxurEat', 'description' => '先炒去西葫芦多余水分，再与鸡蛋和帕玛森凝固成柔嫩烘蛋，是适合家庭平底锅完成的意大利日常菜。', 'lang' => 'zh', 'alternate' => 'en/recipe/family-zucchini-frittata', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-family-zucchini-frittata.webp', 'category' => '适合中国家庭的意大利菜', 'totalTime' => 'PT35M', 'yield' => '4人份', 'ingredients' => array('鸡蛋6个（约300克）', '西葫芦450克', '洋葱80克', '帕玛森奶酪30克，磨碎', '特级初榨橄榄油20克', '细盐2.5克', '现磨黑胡椒适量'), 'instructions' => array('西葫芦洗净擦干，切成薄片；洋葱切细丝。', '不粘平底锅加入一半橄榄油，中火炒洋葱至变软。', '加入西葫芦和少量盐，中高火炒至水分明显减少，约6–8分钟。', '鸡蛋打入大碗，加入帕玛森、剩余盐和黑胡椒，搅匀。', '将炒好的西葫芦稍微放凉后加入蛋液。', '擦净锅底，加入剩余橄榄油并以中低火加热。', '倒入蛋液，轻轻晃平。', '盖锅，以小火煮8–10分钟，直至边缘凝固、中心仍略湿。', '借助大盘翻面，或放入预热烤箱上火完成表面。', '继续加热3–5分钟，中心温度达到71°C。', '静置5分钟后切块上桌。'), 'nutrition' => '能量: 约990千焦 / 235千卡; 脂肪: 约17克; 其中饱和脂肪: 约5.5克; 碳水化合物: 约6克; 其中糖: 约4克; 蛋白质: 约16克; 膳食纤维: 约1.5克; 钠: 约500毫克'),
        'en/recipe/family-zucchini-frittata' => array('title' => 'Italian Zucchini Frittata | LuxurEat', 'description' => 'Courgettes are sautéed to remove excess moisture, then set with eggs and Parmigiano in an everyday Italian frittata suited to a home frying pan.', 'lang' => 'en', 'alternate' => 'recipe/family-zucchini-frittata', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-family-zucchini-frittata.webp', 'category' => 'Italian Food for Chinese Homes', 'totalTime' => 'PT35M', 'yield' => 'Serves 4', 'ingredients' => array('6 eggs (about 300 g)', '450 g courgettes', '80 g onion', '30 g Parmigiano, grated', '20 g extra-virgin olive oil', '2.5 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Wash and dry the courgettes and slice thinly; thinly slice the onion.', 'Warm half the oil in a non-stick pan and soften the onion over medium heat.', 'Add courgettes and a little salt; cook over medium-high heat for 6–8 minutes until much of the moisture evaporates.', 'Beat the eggs with Parmigiano, remaining salt and pepper.', 'Cool the courgettes slightly and add them to the egg mixture.', 'Wipe the pan, add the remaining oil and warm over medium-low heat.', 'Pour in the egg mixture and level it gently.', 'Cover and cook over low heat for 8–10 minutes, until the edges set and the centre remains slightly moist.', 'Turn with a large plate, or finish the top under a preheated grill.', 'Cook for another 3–5 minutes, to 71°C at the centre.', 'Rest for 5 minutes, then slice and serve.'), 'nutrition' => 'Energy: About 990 kJ / 235 kcal; Fat: About 17 g; of which saturates: About 5.5 g; Carbohydrate: About 6 g; of which sugars: About 4 g; Protein: About 16 g; Fibre: About 1.5 g; Sodium: About 500 mg'),
        'recipe/family-chicken-cacciatora' => array('title' => '意式猎人烩鸡 | LuxurEat', 'description' => '鸡腿与洋葱、胡萝卜、西芹、番茄、白葡萄酒和香草慢炖，是中部意大利风格浓郁而适合家庭操作的主菜。', 'lang' => 'zh', 'alternate' => 'en/recipe/family-chicken-cacciatora', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-family-chicken-cacciatora.webp', 'category' => '适合中国家庭的意大利菜', 'totalTime' => 'PT1M', 'yield' => '4人份', 'ingredients' => array('带骨去皮鸡腿900克', '番茄泥400克', '洋葱100克', '胡萝卜80克', '西芹60克', '特级初榨橄榄油30克', '干白葡萄酒100毫升', '蒜1瓣（约5克）', '迷迭香1枝', '月桂叶1片', '细盐5克', '现磨黑胡椒适量'), 'instructions' => array('鸡腿擦干，以盐和黑胡椒调味。', '厚底锅加入橄榄油，中高火分批将鸡腿表面煎至金黄。', '鸡腿取出备用，锅中保留油脂。', '加入切碎的洋葱、胡萝卜和西芹，中火炒5–7分钟。', '加入蒜、迷迭香和月桂叶，炒至出香。', '倒入白葡萄酒，刮起锅底焦香物并煮至液体约减半。', '加入番茄泥并搅匀。', '鸡腿放回锅中，酱汁应覆盖鸡肉约一半。', '煮沸后转小火，半盖锅盖炖约30–35分钟。', '期间翻动鸡腿，并按需少量加水。', '确认鸡肉最厚处中心温度达到74°C。', '取出迷迭香和月桂叶。', '按需收浓酱汁并调整盐和黑胡椒。', '静置数分钟后上桌。'), 'nutrition' => '能量: 约1900千焦 / 455千卡; 脂肪: 约26克; 其中饱和脂肪: 约6克; 碳水化合物: 约11克; 其中糖: 约7克; 蛋白质: 约44克; 膳食纤维: 约3克; 钠: 约760毫克'),
        'en/recipe/family-chicken-cacciatora' => array('title' => 'Italian Hunter-Style Chicken | LuxurEat', 'description' => 'Chicken thighs braised with onion, carrot, celery, tomato, white wine and herbs make a deeply flavoured Central Italian-style main course for the home kitchen.', 'lang' => 'en', 'alternate' => 'recipe/family-chicken-cacciatora', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-family-chicken-cacciatora.webp', 'category' => 'Italian Food for Chinese Homes', 'totalTime' => 'PT1M', 'yield' => 'Serves 4', 'ingredients' => array('900 g bone-in, skinless chicken thighs', '400 g tomato passata', '100 g onion', '80 g carrot', '60 g celery', '30 g extra-virgin olive oil', '100 ml dry white wine', '1 garlic clove (about 5 g)', '1 rosemary sprig', '1 bay leaf', '5 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Pat the chicken dry and season with salt and pepper.', 'Heat the oil in a heavy pan and brown the chicken in batches over medium-high heat.', 'Remove the chicken, leaving the fat in the pan.', 'Add chopped onion, carrot and celery and cook for 5–7 minutes.', 'Add garlic, rosemary and bay and cook until aromatic.', 'Pour in the wine, scrape up the browned residue and reduce the liquid by about half.', 'Add the passata and stir.', 'Return the chicken; the sauce should come about halfway up the pieces.', 'Bring to the boil, reduce to low, partially cover and simmer for 30–35 minutes.', 'Turn the chicken during cooking and add a little water if needed.', 'Check that the thickest part reaches 74°C.', 'Remove rosemary and bay.', 'Reduce the sauce if needed and adjust salt and pepper.', 'Rest for a few minutes before serving.'), 'nutrition' => 'Energy: About 1,900 kJ / 455 kcal; Fat: About 26 g; of which saturates: About 6 g; Carbohydrate: About 11 g; of which sugars: About 7 g; Protein: About 44 g; Fibre: About 3 g; Sodium: About 760 mg'),
        'recipe/truffle-summer-crostini' => array('title' => '翁布里亚夏季黑松露烤面包 | LuxurEat', 'description' => '翁布里亚风格烤面包以蒜香橄榄油承接夏季松露，低温处理并在最后刨片，保留易挥发香气。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-summer-crostini', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-summer-crostini.webp', 'category' => '松露食谱', 'totalTime' => 'PT20M', 'yield' => '4人份', 'ingredients' => array('乡村面包200克，切8片', '夏季松露 夏季黑松露 30克', '特级初榨橄榄油50克', '蒜1瓣（约5克）', '细盐1.5克'), 'instructions' => array('松露以软刷彻底刷去泥土，快速冲洗后立即擦干。', '将20克松露细磨，剩余10克留作刨片。', '橄榄油与拍裂的蒜以极低火温热，不要让蒜上色。', '移除蒜并让油稍微降温，再拌入磨碎松露和盐。', '面包烤至两面金黄酥脆。', '趁热将松露油酱均匀涂在烤面包上。', '刨上剩余松露，立即上桌。'), 'nutrition' => '能量: 约1090千焦 / 260千卡; 脂肪: 约13克; 其中饱和脂肪: 约2克; 碳水化合物: 约26克; 其中糖: 约3克; 蛋白质: 约6克; 膳食纤维: 约2.5克; 钠: 约400毫克'),
        'en/recipe/truffle-summer-crostini' => array('title' => 'Umbrian Crostini with Summer Black Truffle | LuxurEat', 'description' => 'Umbrian-style crostini use garlic-scented olive oil to carry summer truffle, handled at low heat and shaved at the end to preserve its volatile aroma.', 'lang' => 'en', 'alternate' => 'recipe/truffle-summer-crostini', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-summer-crostini.webp', 'category' => 'Truffle Recipes', 'totalTime' => 'PT20M', 'yield' => 'Serves 4', 'ingredients' => array('200 g country bread, cut into 8 slices', '30 g summer truffle (Tuber aestivum)', '50 g extra-virgin olive oil', '1 garlic clove (about 5 g)', '1.5 g fine salt'), 'instructions' => array('Brush all soil from the truffle, rinse briefly and dry immediately.', 'Finely grate 20 g truffle and reserve 10 g for shaving.', 'Warm the oil and crushed garlic over very low heat without browning the garlic.', 'Remove the garlic, let the oil cool slightly, then mix in the grated truffle and salt.', 'Toast the bread until golden and crisp on both sides.', 'Spread the warm toast evenly with the truffle oil mixture.', 'Shave over the remaining truffle and serve immediately.'), 'nutrition' => 'Energy: About 1,090 kJ / 260 kcal; Fat: About 13 g; of which saturates: About 2 g; Carbohydrate: About 26 g; of which sugars: About 3 g; Protein: About 6 g; Fibre: About 2.5 g; Sodium: About 400 mg'),
        'recipe/truffle-trout' => array('title' => '翁布里亚黑松露鳟鱼 | LuxurEat', 'description' => '来自翁布里亚 瓦尔内里纳 的传统搭配，以温和烹调的鳟鱼承接黑松露和蒜香橄榄油。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-trout', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-trout.webp', 'category' => '松露食谱', 'totalTime' => 'PT35M', 'yield' => '2人份', 'ingredients' => array('整条去内脏鳟鱼2条，每条约300克', '黑松露30克', '特级初榨橄榄油30克', '蒜1瓣（约5克）', '细盐3克', '新鲜欧芹5克，可选'), 'instructions' => array('鳟鱼清理干净，冲洗后彻底擦干。', '鱼腹和表面轻撒盐。', '烤箱预热至190°C，鳟鱼放入烤盘并淋少量橄榄油。', '烤约15–18分钟，至鱼肉刚熟、中心温度达到63°C。', '其余橄榄油与拍裂的蒜以低火温热，不要上色。', '移除蒜，加入20克细磨黑松露。', '鳟鱼去头、去骨并保持鱼肉完整。', '将松露油汁淋在鱼肉上。', '刨上剩余10克松露，可用欧芹点缀后立即上桌。'), 'nutrition' => '能量: 约2280千焦 / 545千卡; 脂肪: 约35克; 其中饱和脂肪: 约6克; 碳水化合物: 约1克; 其中糖: 少于1克; 蛋白质: 约63克; 膳食纤维: 约0.5克; 钠: 约730毫克'),
        'en/recipe/truffle-trout' => array('title' => 'Umbrian Trout with Black Truffle | LuxurEat', 'description' => 'A Valnerina tradition from Umbria, pairing gently cooked trout with black truffle and garlic-scented olive oil.', 'lang' => 'en', 'alternate' => 'recipe/truffle-trout', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-trout.webp', 'category' => 'Truffle Recipes', 'totalTime' => 'PT35M', 'yield' => 'Serves 2', 'ingredients' => array('2 whole gutted trout, about 300 g each', '30 g black truffle', '30 g extra-virgin olive oil', '1 garlic clove (about 5 g)', '3 g fine salt', '5 g fresh parsley, optional'), 'instructions' => array('Clean the trout, rinse and dry thoroughly.', 'Season the cavities and surfaces lightly with salt.', 'Preheat the oven to 190°C, place the trout in a roasting dish and drizzle with a little oil.', 'Roast for 15–18 minutes, until just cooked and 63°C at the centre.', 'Warm the remaining oil and crushed garlic over low heat without browning.', 'Remove the garlic and add 20 g finely grated black truffle.', 'Remove the heads and bones while keeping the fillets intact.', 'Spoon over the truffle oil.', 'Shave over the remaining 10 g truffle, garnish with parsley if desired and serve immediately.'), 'nutrition' => 'Energy: About 2,280 kJ / 545 kcal; Fat: About 35 g; of which saturates: About 6 g; Carbohydrate: About 1 g; of which sugars: Less than 1 g; Protein: About 63 g; Fibre: About 0.5 g; Sodium: About 730 mg'),
        'recipe/truffle-lamb' => array('title' => '翁布里亚黑松露烩羊肉 | LuxurEat', 'description' => '翁布里亚 瓦尔内里纳 风格慢炖羊肉，以白葡萄酒、迷迭香和黑松露完成；松露在最后加入，避免香气流失。', 'lang' => 'zh', 'alternate' => 'en/recipe/truffle-lamb', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-lamb.webp', 'category' => '松露食谱', 'totalTime' => 'PT1M', 'yield' => '6人份', 'ingredients' => array('去骨羊肩或羊腿肉1.2千克，切约4厘米块', '黑松露50克', '特级初榨橄榄油35克', '蒜2瓣（约10克）', '迷迭香2克', '干白葡萄酒180毫升', '低盐或无盐高汤约250毫升，按需', '细盐7克', '现磨黑胡椒适量'), 'instructions' => array('羊肉擦干，以盐和黑胡椒调味。', '厚底锅加入橄榄油，中高火分批将羊肉煎至表面金黄。', '羊肉取出备用。', '锅中加入拍裂的蒜和迷迭香，短暂炒香。', '羊肉放回锅中，倒入白葡萄酒。', '刮起锅底焦香物，煮至酒液约减半。', '加入足量高汤，使液体达到羊肉约一半高度。', '煮沸后转小火，半盖锅盖炖约60–75分钟。', '期间翻动并按需补充少量高汤，直至羊肉柔嫩。', '确认中心温度至少达到63°C并静置；慢炖通常会达到更高温度。', '关火，加入30克细磨黑松露拌入酱汁。', '装盘后刨上剩余20克松露，立即上桌。'), 'nutrition' => '能量: 约2130千焦 / 510千卡; 脂肪: 约25克; 其中饱和脂肪: 约8克; 碳水化合物: 约2克; 其中糖: 约1克; 蛋白质: 约57克; 膳食纤维: 少于1克; 钠: 约580毫克'),
        'en/recipe/truffle-lamb' => array('title' => 'Umbrian Lamb with Black Truffle | LuxurEat', 'description' => 'A Valnerina-style lamb braise with white wine, rosemary and black truffle; the truffle is added at the end to preserve its aroma.', 'lang' => 'en', 'alternate' => 'recipe/truffle-lamb', 'indexable' => true, 'type' => 'Recipe', 'image' => 'media/journal/recipe-truffle-lamb.webp', 'category' => 'Truffle Recipes', 'totalTime' => 'PT1M', 'yield' => 'Serves 6', 'ingredients' => array('1.2 kg boneless lamb shoulder or leg, cut into 4 cm pieces', '50 g black truffle', '35 g extra-virgin olive oil', '2 garlic cloves (about 10 g)', '2 g rosemary', '180 ml dry white wine', 'About 250 ml low-salt or unsalted stock, as needed', '7 g fine salt', 'Freshly ground black pepper'), 'instructions' => array('Pat the lamb dry and season with salt and pepper.', 'Heat the oil in a heavy pan and brown the lamb in batches over medium-high heat.', 'Remove the lamb.', 'Briefly cook crushed garlic and rosemary in the pan.', 'Return the lamb and add the wine.', 'Scrape up the browned residue and reduce the wine by about half.', 'Add enough stock to come halfway up the lamb.', 'Bring to the boil, reduce to low, partially cover and braise for 60–75 minutes.', 'Turn the meat and add small amounts of stock as needed, until tender.', 'Ensure the centre reaches at least 63°C and rest; a braise normally reaches a higher temperature.', 'Turn off the heat and stir 30 g finely grated black truffle into the sauce.', 'Plate, shave over the remaining 20 g truffle and serve immediately.'), 'nutrition' => 'Energy: About 2,130 kJ / 510 kcal; Fat: About 25 g; of which saturates: About 8 g; Carbohydrate: About 2 g; of which sugars: About 1 g; Protein: About 57 g; Fibre: Less than 1 g; Sodium: About 580 mg'),
    );
}

function luxureat_static_disable_yoast_output() {
    $path = luxureat_static_current_path();
    $path = $path === '' || $path === '__home' ? 'zh' : $path;
    $aliases = luxureat_static_aliases();
    $route = isset($aliases[$path]) ? $aliases[$path] : $path;
    if (!isset(luxureat_static_seo_catalog()[$route]) || !function_exists('YoastSEO')) {
        return;
    }

    $front_end = YoastSEO()->classes->get(Yoast\WP\SEO\Integrations\Front_End_Integration::class);
    remove_action('wpseo_head', array($front_end, 'present_head'), -9999);
}
add_action('template_redirect', 'luxureat_static_disable_yoast_output', -20);

function luxureat_static_seo_head() {
    $path = luxureat_static_current_path();
    $path = $path === '' || $path === '__home' ? 'zh' : $path;
    $aliases = luxureat_static_aliases();
    $route = isset($aliases[$path]) ? $aliases[$path] : $path;
    $catalog = luxureat_static_seo_catalog();
    if (!isset($catalog[$route])) {
        return;
    }

    $meta = $catalog[$route];
    $alternate = isset($catalog[$meta['alternate']]) ? $catalog[$meta['alternate']] : null;
    $zh_route = $meta['lang'] === 'zh' ? $route : $meta['alternate'];
    $canonical = luxureat_static_url($route);
    $image = get_template_directory_uri() . '/assets/' . $meta['image'];
    if (empty($meta['indexable'])) {
        echo '<meta name="robots" content="noindex,follow">' . "
";
    }
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "
";
    echo '<link rel="alternate" hreflang="zh-CN" href="' . esc_url(luxureat_static_url($zh_route)) . '">' . "
";
    if ($alternate) {
        $en_route = $meta['lang'] === 'en' ? $route : $meta['alternate'];
        echo '<link rel="alternate" hreflang="en" href="' . esc_url(luxureat_static_url($en_route)) . '">' . "
";
    }
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url(luxureat_static_url($zh_route)) . '">' . "
";
    $og_type = in_array($meta['type'], array('Article', 'Product'), true) ? strtolower($meta['type']) : 'website';
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "
";
    echo '<meta property="og:site_name" content="LuxurEat">' . "
";
    echo '<meta property="og:locale" content="' . esc_attr($meta['lang'] === 'zh' ? 'zh_CN' : 'en_US') . '">' . "
";
    echo '<meta property="og:title" content="' . esc_attr($meta['title']) . '">' . "
";
    echo '<meta property="og:description" content="' . esc_attr($meta['description']) . '">' . "
";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "
";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "
";
    echo '<meta name="twitter:card" content="summary_large_image">' . "
";
    echo '<meta name="twitter:title" content="' . esc_attr($meta['title']) . '">' . "
";
    echo '<meta name="twitter:description" content="' . esc_attr($meta['description']) . '">' . "
";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "
";
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $meta['type'],
        'name' => $meta['title'],
        'headline' => $meta['type'] === 'Article' ? $meta['title'] : null,
        'description' => $meta['description'],
        'url' => $canonical,
        'image' => $image,
        'inLanguage' => $meta['lang'] === 'zh' ? 'zh-CN' : 'en',
        'isPartOf' => array('@type' => 'WebSite', 'name' => 'LuxurEat', 'url' => luxureat_static_url('zh')),
        'sku' => $meta['type'] === 'Product' ? $meta['sku'] : null,
        'category' => in_array($meta['type'], array('Product', 'Recipe'), true) ? $meta['category'] : null,
        'brand' => $meta['type'] === 'Product' ? array('@type' => 'Brand', 'name' => 'LuxurEat') : null,
        'startDate' => $meta['type'] === 'Event' ? $meta['startDate'] : null,
        'endDate' => $meta['type'] === 'Event' ? $meta['endDate'] : null,
        'eventStatus' => $meta['type'] === 'Event' ? 'https://schema.org/EventScheduled' : null,
        'eventAttendanceMode' => $meta['type'] === 'Event' ? 'https://schema.org/OfflineEventAttendanceMode' : null,
        'location' => $meta['type'] === 'Event' ? array('@type' => 'Place', 'name' => $meta['location']) : null,
        'organizer' => $meta['type'] === 'Event' ? array('@type' => 'Organization', 'name' => 'LuxurEat', 'url' => luxureat_static_url('zh')) : null,
        'totalTime' => $meta['type'] === 'Recipe' && $meta['totalTime'] ? $meta['totalTime'] : null,
        'recipeYield' => $meta['type'] === 'Recipe' ? $meta['yield'] : null,
        'recipeIngredient' => $meta['type'] === 'Recipe' ? $meta['ingredients'] : null,
        'recipeInstructions' => $meta['type'] === 'Recipe' ? array_map(function ($step) { return array('@type' => 'HowToStep', 'text' => $step); }, $meta['instructions']) : null,
        'nutrition' => $meta['type'] === 'Recipe' && $meta['nutrition'] ? array('@type' => 'NutritionInformation', 'description' => $meta['nutrition']) : null,
        'author' => $meta['type'] === 'Recipe' ? array('@type' => 'Organization', 'name' => 'LuxurEat', 'url' => luxureat_static_url('zh')) : null,
        'publisher' => in_array($meta['type'], array('Article', 'WebPage'), true) ? array('@type' => 'Organization', 'name' => 'LuxurEat', 'url' => luxureat_static_url('zh'), 'logo' => array('@type' => 'ImageObject', 'url' => get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png')) : null,
    );
    $schema = array_filter($schema, function ($value) { return $value !== null; });
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "
";
    $parent_slugs = array('Article' => 'blog', 'Product' => 'product', 'Event' => 'brand', 'Recipe' => 'recipe');
    if (isset($parent_slugs[$meta['type']])) {
        $is_zh = $meta['lang'] === 'zh';
        $parent_route = ($is_zh ? 'zh/' : 'en/') . $parent_slugs[$meta['type']];
        $parent_names = array(
            'Article' => $is_zh ? '知识博客' : 'Knowledge Blog',
            'Product' => $is_zh ? '系列产品' : 'Products',
            'Event' => $is_zh ? '品牌新闻' : 'Brand News',
            'Recipe' => $is_zh ? '食谱艺术' : 'Recipes',
        );
        $breadcrumb = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array(
                array('@type' => 'ListItem', 'position' => 1, 'name' => $is_zh ? '首页' : 'Home', 'item' => luxureat_static_url($is_zh ? 'zh' : 'en')),
                array('@type' => 'ListItem', 'position' => 2, 'name' => $parent_names[$meta['type']], 'item' => luxureat_static_url($parent_route)),
                array('@type' => 'ListItem', 'position' => 3, 'name' => preg_replace('/ \| LuxurEat$/', '', $meta['title']), 'item' => $canonical),
            ),
        );
        echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "
";
    }
}
remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'luxureat_static_seo_head', 1);

function luxureat_static_woo_catalog() {
    if (!function_exists('wc_get_product_id_by_sku')) {
        return array();
    }

    $cached = get_transient('luxureat_static_woo_catalog');
    if (is_array($cached)) {
        return $cached;
    }

    $catalog = array();
    foreach (array('imperial-beluga-30g', 'royal-oscetra-30g', 'mother-of-pearl-spoons', 'champagne', 'ice-server') as $sku) {
        $product_id = wc_get_product_id_by_sku($sku);
        $product = $product_id ? wc_get_product($product_id) : false;
        if (!$product) {
            continue;
        }

        $image_id = $product->get_image_id();
        $gallery = array_values(array_filter(array_map(function ($attachment_id) {
            return wp_get_attachment_image_url($attachment_id, 'full');
        }, $product->get_gallery_image_ids())));
        $stock_quantity = $product->managing_stock() ? $product->get_stock_quantity() : null;
        $max_quantity = $product->is_sold_individually()
            ? 1
            : ($stock_quantity !== null && !$product->backorders_allowed() ? max(0, (int) $stock_quantity) : 99);

        $catalog[$sku] = array(
            'id' => $product->get_id(),
            'sku' => $sku,
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_short_description() ?: $product->get_description()),
            'price' => (float) $product->get_price(),
            'currency' => html_entity_decode(get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8'),
            'image' => $image_id ? wp_get_attachment_image_url($image_id, 'full') : '',
            'gallery' => $gallery,
            'stockStatus' => $product->get_stock_status(),
            'stockQuantity' => $stock_quantity,
            'available' => $product->is_purchasable() && $product->is_in_stock(),
            'maxQuantity' => $max_quantity,
        );
    }
    set_transient('luxureat_static_woo_catalog', $catalog, MINUTE_IN_SECONDS);
    return $catalog;
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
        'certification-ui' => array('src' => 'assets/js/certification-ui.js', 'dependencies' => array()),
        'product-data' => array('src' => 'assets/data/products.js', 'dependencies' => array()),
        'event-data' => array('src' => 'assets/data/events.js', 'dependencies' => array()),
        'journal-data' => array('src' => 'assets/data/journal.js', 'dependencies' => array()),
        'academy-index' => array('src' => 'assets/data/academy-index.js', 'dependencies' => array()),
        'brand-data' => array('src' => 'assets/data/brand.js', 'dependencies' => array()),
        'core' => array('src' => 'assets/js/core.js', 'dependencies' => array()),
        'new-arrivals' => array('src' => 'assets/js/new-arrivals.js', 'dependencies' => array()),
        'products' => array('src' => 'assets/js/products.js', 'dependencies' => array('product-data')),
        'events' => array('src' => 'assets/js/events.js', 'dependencies' => array('event-data')),
        'journal' => array('src' => 'assets/js/journal.js', 'dependencies' => array('journal-data')),
        'academy' => array('src' => 'assets/js/academy.js', 'dependencies' => array('academy-index')),
        'brand' => array('src' => 'assets/js/brand.js', 'dependencies' => array('brand-data')),
    );
    $assets_by_path = array(
        'zh' => array('core'),
        'zh/about-us' => array('core', 'journal-data', 'journal'),
        'zh/product' => array('core', 'product-data', 'products'),
        'zh/new' => array('core', 'journal-data', 'journal', 'new-arrivals'),
        'zh/recipe' => array('core', 'journal-data', 'journal'),
        'zh/brand' => array('core', 'event-data', 'journal-data', 'journal'),
        'zh/blog' => array('core', 'academy-index', 'academy'),
        'zh/certification' => array('core', 'certification-ui'),
        'zh/cooperation' => array('core', 'brand-data', 'brand'),
        'zh/contact' => array('core', 'brand-data', 'brand'),
        'zh/bag' => array('core', 'product-data', 'products'),
        'en' => array('core'),
        'en/about-us' => array('core', 'journal-data', 'journal'),
        'en/product' => array('core', 'product-data', 'products'),
        'en/new' => array('core', 'journal-data', 'journal', 'new-arrivals'),
        'en/recipe' => array('core', 'journal-data', 'journal'),
        'en/brand' => array('core', 'event-data', 'journal-data', 'journal'),
        'en/blog' => array('core', 'academy-index', 'academy'),
        'en/certification' => array('core', 'certification-ui'),
        'en/cooperation' => array('core', 'brand-data', 'brand'),
        'en/contact' => array('core', 'brand-data', 'brand'),
        'en/bag' => array('core', 'product-data', 'products'),
        'blog/baerii-caviar' => array('core'),
        'en/blog/baerii-caviar' => array('core'),
        'blog/beluga-caviar' => array('core'),
        'en/blog/beluga-caviar' => array('core'),
        'blog/kaluga-amur-caviar' => array('core'),
        'en/blog/kaluga-amur-caviar' => array('core'),
        'blog/oscetra-caviar' => array('core'),
        'en/blog/oscetra-caviar' => array('core'),
        'blog/sevruga-caviar' => array('core'),
        'en/blog/sevruga-caviar' => array('core'),
        'blog/white-sturgeon-caviar' => array('core'),
        'en/blog/white-sturgeon-caviar' => array('core'),
        'blog/history-of-caviar' => array('core'),
        'en/blog/history-of-caviar' => array('core'),
        'blog/main-types-of-caviar' => array('core'),
        'en/blog/main-types-of-caviar' => array('core'),
        'blog/caviar-processing' => array('core'),
        'en/blog/caviar-processing' => array('core'),
        'blog/olive-oil-basics' => array('core'),
        'blog/olive-regions' => array('core'),
        'blog/olive-tasting' => array('core'),
        'blog/gelato-vs-ice-cream' => array('core'),
        'blog/gelato-history' => array('core'),
        'blog/ugolini-gelato-mix' => array('core'),
        'en/blog/olive-oil-basics' => array('core'),
        'en/blog/olive-regions' => array('core'),
        'en/blog/olive-tasting' => array('core'),
        'en/blog/gelato-vs-ice-cream' => array('core'),
        'en/blog/gelato-history' => array('core'),
        'en/blog/ugolini-gelato-mix' => array('core'),
        'blog/italian-food-culture' => array('core'),
        'en/blog/italian-food-culture' => array('core'),
        'blog/italy-regions' => array('core'),
        'en/blog/italy-regions' => array('core'),
        'blog/regional-traditions' => array('core'),
        'en/blog/regional-traditions' => array('core'),
        'blog/ingredients-territory' => array('core'),
        'en/blog/ingredients-territory' => array('core'),
        'blog/table-etiquette' => array('core'),
        'en/blog/table-etiquette' => array('core'),
        'blog/cooking-techniques' => array('core'),
        'en/blog/cooking-techniques' => array('core'),
        'blog/pasta-academy' => array('core'),
        'en/blog/pasta-academy' => array('core'),
        'blog/pairings-class' => array('core'),
        'en/blog/pairings-class' => array('core'),
        'blog/olive-nutrition' => array('core'),
        'en/blog/olive-nutrition' => array('core'),
        'blog/olive-cultivars' => array('core'),
        'en/blog/olive-cultivars' => array('core'),
        'blog/olive-how-to-choose' => array('core'),
        'en/blog/olive-how-to-choose' => array('core'),
        'blog/olive-storage' => array('core'),
        'en/blog/olive-storage' => array('core'),
        'blog/olive-fasting' => array('core'),
        'en/blog/olive-fasting' => array('core'),
        'blog/olive-myths' => array('core'),
        'en/blog/olive-myths' => array('core'),
        'blog/olive-recipes-guide' => array('core'),
        'en/blog/olive-recipes-guide' => array('core'),
        'blog/pizza-fundamentals' => array('core'),
        'en/blog/pizza-fundamentals' => array('core'),
        'blog/gelato-flavours' => array('core'),
        'en/blog/gelato-flavours' => array('core'),
        'blog/nutrition-labels' => array('core'),
        'en/blog/nutrition-labels' => array('core'),
        'blog/allergens-guide' => array('core'),
        'en/blog/allergens-guide' => array('core'),
        'blog/clean-label' => array('core'),
        'en/blog/clean-label' => array('core'),
        'blog/nutrition-myths' => array('core'),
        'en/blog/nutrition-myths' => array('core'),
        'en/blog/italian-gelato-vs-ice-cream' => array('core'),
        'en/blog/story-of-italian-gelato' => array('core'),
        'en/blog/truffle-meets-dessert' => array('core'),
        'en/blog/evo-vs-common-cooking-oil' => array('core'),
        'en/blog/story-of-italian-evo' => array('core'),
        'en/blog/evo-chocolate-dessert' => array('core'),
        'en/blog/choose-use-store-evo' => array('core'),
        'en/blog/neapolitan-roman-pizza-styles' => array('core'),
        'en/blog/story-of-italian-pizza' => array('core'),
        'en/blog/modern-pinsa-romana' => array('core'),
        'en/blog/truffle-gelato-at-home' => array('core'),
        'en/blog/pizza-pinsa-at-home' => array('core'),
        'en/blog/china-italian-gelato' => array('core'),
        'en/blog/china-italian-evo' => array('core'),
        'en/blog/china-pizza-pinsa' => array('core'),
        'blog/italian-gelato-vs-ice-cream' => array('core'),
        'blog/story-of-italian-gelato' => array('core'),
        'blog/truffle-meets-dessert' => array('core'),
        'blog/evo-vs-common-cooking-oil' => array('core'),
        'blog/story-of-italian-evo' => array('core'),
        'blog/evo-chocolate-dessert' => array('core'),
        'blog/choose-use-store-evo' => array('core'),
        'blog/neapolitan-roman-pizza-styles' => array('core'),
        'blog/story-of-italian-pizza' => array('core'),
        'blog/modern-pinsa-romana' => array('core'),
        'blog/truffle-gelato-at-home' => array('core'),
        'blog/pizza-pinsa-at-home' => array('core'),
        'blog/china-italian-gelato' => array('core'),
        'blog/china-italian-evo' => array('core'),
        'blog/china-pizza-pinsa' => array('core'),
        'blog/truffle-what-is-truffle' => array('core'),
        'blog/truffle-truffle-types' => array('core'),
        'blog/truffle-italian-truffle-map' => array('core'),
        'blog/truffle-truffle-aroma-pairing' => array('core'),
        'blog/truffle-buying-truffle-products' => array('core'),
        'en/blog/truffle-what-is-truffle' => array('core'),
        'en/blog/truffle-truffle-types' => array('core'),
        'en/blog/truffle-italian-truffle-map' => array('core'),
        'en/blog/truffle-truffle-aroma-pairing' => array('core'),
        'en/blog/truffle-buying-truffle-products' => array('core'),
        'blog/dictionary-italian-menu' => array('core'),
        'blog/dictionary-pasta-risotto' => array('core'),
        'blog/dictionary-cheese-cured-meat' => array('core'),
        'blog/dictionary-cooking-methods' => array('core'),
        'blog/dictionary-food-labels' => array('core'),
        'en/blog/dictionary-italian-menu' => array('core'),
        'en/blog/dictionary-pasta-risotto' => array('core'),
        'en/blog/dictionary-cheese-cured-meat' => array('core'),
        'en/blog/dictionary-cooking-methods' => array('core'),
        'en/blog/dictionary-food-labels' => array('core'),
        'blog/producers-truffle-hunters' => array('core'),
        'blog/producers-parmigiano-cheesemakers' => array('core'),
        'blog/producers-modena-acetaia' => array('core'),
        'blog/producers-neapolitan-pizzaiuolo' => array('core'),
        'blog/producers-modern-gelatiere' => array('core'),
        'en/blog/producers-truffle-hunters' => array('core'),
        'en/blog/producers-parmigiano-cheesemakers' => array('core'),
        'en/blog/producers-modena-acetaia' => array('core'),
        'en/blog/producers-neapolitan-pizzaiuolo' => array('core'),
        'en/blog/producers-modern-gelatiere' => array('core'),
        'product/white-truffle-oil-60ml' => array('core'),
        'en/product/white-truffle-oil-60ml' => array('core'),
        'product/white-truffle-oil-250ml' => array('core'),
        'en/product/white-truffle-oil-250ml' => array('core'),
        'product/white-truffle-evoo-60ml' => array('core'),
        'en/product/white-truffle-evoo-60ml' => array('core'),
        'product/white-truffle-evoo-250ml' => array('core'),
        'en/product/white-truffle-evoo-250ml' => array('core'),
        'product/summer-truffle-slices-50g' => array('core'),
        'en/product/summer-truffle-slices-50g' => array('core'),
        'product/summer-truffle-slices-80g' => array('core'),
        'en/product/summer-truffle-slices-80g' => array('core'),
        'product/summer-truffle-slices-180g' => array('core'),
        'en/product/summer-truffle-slices-180g' => array('core'),
        'product/summer-truffle-slices-water-350g' => array('core'),
        'en/product/summer-truffle-slices-water-350g' => array('core'),
        'product/summer-truffle-slices-oil-350g' => array('core'),
        'en/product/summer-truffle-slices-oil-350g' => array('core'),
        'product/truffle-honey-50g' => array('core'),
        'en/product/truffle-honey-50g' => array('core'),
        'product/truffle-honey-170g' => array('core'),
        'en/product/truffle-honey-170g' => array('core'),
        'product/white-truffle-sauce-80g' => array('core'),
        'en/product/white-truffle-sauce-80g' => array('core'),
        'product/white-truffle-sauce-500g' => array('core'),
        'en/product/white-truffle-sauce-500g' => array('core'),
        'product/black-truffle-sauce-80g' => array('core'),
        'en/product/black-truffle-sauce-80g' => array('core'),
        'product/black-truffle-sauce-180g' => array('core'),
        'en/product/black-truffle-sauce-180g' => array('core'),
        'product/black-truffle-sauce-500g' => array('core'),
        'en/product/black-truffle-sauce-500g' => array('core'),
        'product/whole-summer-truffles-35g' => array('core'),
        'en/product/whole-summer-truffles-35g' => array('core'),
        'product/whole-summer-truffles-55g' => array('core'),
        'en/product/whole-summer-truffles-55g' => array('core'),
        'product/whole-summer-truffles-110g' => array('core'),
        'en/product/whole-summer-truffles-110g' => array('core'),
        'product/whole-summer-truffles-275g' => array('core'),
        'en/product/whole-summer-truffles-275g' => array('core'),
        'product/winter-black-truffle-sauce-80g' => array('core'),
        'en/product/winter-black-truffle-sauce-80g' => array('core'),
        'product/winter-black-truffle-sauce-180g' => array('core'),
        'en/product/winter-black-truffle-sauce-180g' => array('core'),
        'product/winter-black-truffle-sauce-500g' => array('core'),
        'en/product/winter-black-truffle-sauce-500g' => array('core'),
        'product/winter-black-truffle-juice-350ml' => array('core'),
        'en/product/winter-black-truffle-juice-350ml' => array('core'),
        'events/fhc-shanghai-2026' => array('core'),
        'en/events/fhc-shanghai-2026' => array('core'),
        'events/cifie-changsha-2026' => array('core'),
        'en/events/cifie-changsha-2026' => array('core'),
        'events/marca-china-2026' => array('core'),
        'en/events/marca-china-2026' => array('core'),
        'events/sial-guangzhou-2026' => array('core'),
        'en/events/sial-guangzhou-2026' => array('core'),
        'recipe/truffle-eggs' => array('core'),
        'recipe/truffle-toast' => array('core'),
        'recipe/truffle-tagliolini' => array('core'),
        'recipe/truffle-ravioli' => array('core'),
        'recipe/black-truffle-risotto' => array('core'),
        'recipe/mushroom-soup' => array('core'),
        'recipe/beef-carpaccio-scallop-truffle' => array('core'),
        'recipe/shrimp-tartare-truffle' => array('core'),
        'recipe/sweet-bread-butter-caviar' => array('core'),
        'recipe/truffle-tiramisu' => array('core'),
        'recipe/olive-bruschetta' => array('core'),
        'recipe/olive-pasta' => array('core'),
        'recipe/pizza-margherita' => array('core'),
        'recipe/gelato-classic' => array('core'),
        'en/recipe/truffle-eggs' => array('core'),
        'en/recipe/truffle-toast' => array('core'),
        'en/recipe/truffle-tagliolini' => array('core'),
        'en/recipe/truffle-ravioli' => array('core'),
        'en/recipe/black-truffle-risotto' => array('core'),
        'en/recipe/mushroom-soup' => array('core'),
        'en/recipe/beef-carpaccio-scallop-truffle' => array('core'),
        'en/recipe/shrimp-tartare-truffle' => array('core'),
        'en/recipe/sweet-bread-butter-caviar' => array('core'),
        'en/recipe/truffle-tiramisu' => array('core'),
        'en/recipe/olive-bruschetta' => array('core'),
        'en/recipe/olive-pasta' => array('core'),
        'en/recipe/pizza-margherita' => array('core'),
        'en/recipe/gelato-classic' => array('core'),
        'recipe/olive-panzanella' => array('core'),
        'en/recipe/olive-panzanella' => array('core'),
        'recipe/olive-pinzimonio' => array('core'),
        'en/recipe/olive-pinzimonio' => array('core'),
        'recipe/light-fennel-orange-salad' => array('core'),
        'en/recipe/light-fennel-orange-salad' => array('core'),
        'recipe/light-grilled-zucchini' => array('core'),
        'en/recipe/light-grilled-zucchini' => array('core'),
        'recipe/light-sea-bass-acqua-pazza' => array('core'),
        'en/recipe/light-sea-bass-acqua-pazza' => array('core'),
        'recipe/family-spaghetti-pomodoro' => array('core'),
        'en/recipe/family-spaghetti-pomodoro' => array('core'),
        'recipe/family-zucchini-frittata' => array('core'),
        'en/recipe/family-zucchini-frittata' => array('core'),
        'recipe/family-chicken-cacciatora' => array('core'),
        'en/recipe/family-chicken-cacciatora' => array('core'),
        'recipe/truffle-summer-crostini' => array('core'),
        'en/recipe/truffle-summer-crostini' => array('core'),
        'recipe/truffle-trout' => array('core'),
        'en/recipe/truffle-trout' => array('core'),
        'recipe/truffle-lamb' => array('core'),
        'en/recipe/truffle-lamb' => array('core'),
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
                'newsletterNonce' => wp_create_nonce('luxureat_newsletter'),
                'botChallenge' => luxureat_static_bot_challenge(),
                'loggedIn' => is_user_logged_in(),
                'bag' => is_user_logged_in() ? luxureat_static_get_bag(get_current_user_id()) : array(),
                'bagNonce' => wp_create_nonce('luxureat_bag'),
                'lostPasswordUrl' => wp_lostpassword_url(home_url('/')),
                'logoutUrl' => wp_logout_url(home_url('/')),
            ));
            if (in_array($path, array('zh', 'en'), true)) {
                wp_localize_script('luxureat-core', 'LuxureatCheckout', array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('luxureat_checkout'),
                ));
                wp_localize_script('luxureat-core', 'LuxureatWooCatalog', array(
                    'products' => luxureat_static_woo_catalog(),
                ));
            }
        }
        if ($handle === 'products') {
            wp_localize_script('luxureat-products', 'LuxureatCheckout', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('luxureat_checkout'),
            ));
            wp_localize_script('luxureat-products', 'LuxureatWooCatalog', array(
                'products' => luxureat_static_woo_catalog(),
            ));
        }
        if ($handle === 'brand' && in_array($path, array('zh/contact', 'en/contact'), true)) {
            wp_localize_script('luxureat-brand', 'LuxureatContact', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('luxureat_contact'),
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'luxureat_static_assets');

function luxureat_static_trim_plugin_assets() {
    $path = luxureat_static_current_path();
    $aliases = luxureat_static_aliases();
    $path = isset($aliases[$path]) ? $aliases[$path] : ($path === '' ? 'zh' : $path);
    if (!isset(luxureat_static_routes()[$path])) {
        return;
    }

    foreach (array('woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce-general', 'wc-blocks-style') as $handle) {
        wp_dequeue_style($handle);
    }
    foreach (array(
        'wc-jquery-blockui', 'wc-add-to-cart', 'wc-js-cookie', 'woocommerce',
        'woocommerce-analytics', 'woocommerce-analytics-client', 'sourcebuster-js',
        'wc-order-attribution', 'googlesitekit-events-provider-woocommerce',
        'jetpack-stats', 'jquery', 'jquery-core', 'jquery-migrate'
    ) as $handle) {
        wp_dequeue_script($handle);
    }
}
add_action('wp_enqueue_scripts', 'luxureat_static_trim_plugin_assets', 999);
add_action('wp_print_styles', 'luxureat_static_trim_plugin_assets', PHP_INT_MAX);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
add_filter('emoji_svg_url', '__return_false');

function luxureat_static_filter_plugin_style($html, $handle) {
    $path = luxureat_static_current_path();
    $aliases = luxureat_static_aliases();
    $path = isset($aliases[$path]) ? $aliases[$path] : ($path === '' ? 'zh' : $path);
    if (isset(luxureat_static_routes()[$path]) && in_array($handle, array('wc-blocks-style', 'woocommerce-inline'), true)) {
        return '';
    }
    return $html;
}
add_filter('style_loader_tag', 'luxureat_static_filter_plugin_style', PHP_INT_MAX, 2);

function luxureat_static_delay_analytics($tag, $handle, $src) {
    $path = luxureat_static_current_path();
    $aliases = luxureat_static_aliases();
    $path = isset($aliases[$path]) ? $aliases[$path] : ($path === '' ? 'zh' : $path);
    if ($handle === 'google_gtagjs' && isset(luxureat_static_routes()[$path])) {
        return '<script data-lux-analytics-src="' . esc_url($src) . '"></script>';
    }
    return $tag;
}
add_filter('script_loader_tag', 'luxureat_static_delay_analytics', PHP_INT_MAX, 3);

function luxureat_static_bot_challenge() {
    $payload = time() . '.' . wp_generate_password(16, false, false);
    return $payload . '.' . hash_hmac('sha256', $payload, wp_salt('nonce'));
}

function luxureat_static_verify_bot_challenge() {
    $token = isset($_POST['bot_challenge']) ? sanitize_text_field(wp_unslash($_POST['bot_challenge'])) : '';
    $nonce = isset($_POST['bot_nonce']) ? sanitize_text_field(wp_unslash($_POST['bot_nonce'])) : '';
    $proof = isset($_POST['bot_proof']) ? sanitize_text_field(wp_unslash($_POST['bot_proof'])) : '';
    $parts = explode('.', $token);
    if (
        count($parts) !== 3 ||
        !ctype_digit($parts[0]) ||
        abs(time() - (int) $parts[0]) > 15 * MINUTE_IN_SECONDS ||
        !hash_equals(hash_hmac('sha256', $parts[0] . '.' . $parts[1], wp_salt('nonce')), $parts[2]) ||
        !preg_match('/^[a-f0-9]{32}$/', $nonce) ||
        !ctype_digit($proof) ||
        (int) $proof > 1000000 ||
        substr(hash('sha256', $token . ':' . $nonce . ':' . $proof), 0, 3) !== '000'
    ) {
        return false;
    }

    $replay_key = 'lux_bot_' . hash('sha256', $token . ':' . $nonce . ':' . $proof);
    if (get_transient($replay_key)) {
        return false;
    }
    set_transient($replay_key, 1, 15 * MINUTE_IN_SECONDS);
    return true;
}

function luxureat_static_strong_password($password, $email) {
    return strlen($password) >= 12 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
}

function luxureat_static_sanitize_bag($items) {
    if (!is_array($items)) {
        return array();
    }
    $bag = array();
    foreach (array_slice($items, 0, 20) as $item) {
        $id = isset($item['id']) ? substr(sanitize_text_field($item['id']), 0, 120) : '';
        $sku = isset($item['sku']) ? substr(sanitize_text_field($item['sku']), 0, 120) : '';
        $quantity = isset($item['quantity']) ? absint($item['quantity']) : 0;
        if ($id !== '' && $sku !== '' && $quantity >= 1 && $quantity <= 99) {
            $bag[] = array('id' => $id, 'sku' => $sku, 'quantity' => $quantity);
        }
    }
    return $bag;
}

function luxureat_static_get_bag($user_id) {
    return luxureat_static_sanitize_bag(get_user_meta($user_id, 'luxureat_bag', true));
}

function luxureat_static_bag_ajax() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Authentication required.'), 401);
    }
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_bag')) {
        wp_send_json_error(array('message' => 'Invalid request.'), 403);
    }
    $items = isset($_POST['items']) ? json_decode(wp_unslash($_POST['items']), true) : null;
    if (!is_array($items)) {
        wp_send_json_error(array('message' => 'Invalid bag.'), 400);
    }
    update_user_meta(get_current_user_id(), 'luxureat_bag', luxureat_static_sanitize_bag($items));
    wp_send_json_success();
}
add_action('wp_ajax_luxureat_bag', 'luxureat_static_bag_ajax');

function luxureat_static_silence_account_admin_mail($return, $mail) {
    $to = isset($mail['to']) ? (array) $mail['to'] : array();
    $admin = strtolower((string) get_option('admin_email'));
    $is_admin_recipient = array_filter($to, function ($recipient) use ($admin) {
        return strpos(strtolower((string) $recipient), $admin) !== false;
    });
    $subject = isset($mail['subject']) ? wp_strip_all_tags($mail['subject']) : '';
    if ($is_admin_recipient && preg_match('/new user registration|new subscriber|new subscription|password|(?:user|account|profile|email).*(?:modified|updated|changed|change|attempt)|新用户注册|新订阅者|密码|(?:用户|账户|资料|邮箱).*(?:修改|更新|更改|尝试)/i', $subject)) {
        return true;
    }
    return $return;
}
add_filter('wp_send_new_user_notification_to_admin', '__return_false');
add_filter('pre_wp_mail', 'luxureat_static_silence_account_admin_mail', 10, 2);

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
            $already_subscribed = isset($subscriber['status']) && $subscriber['status'] === 'subscribed';
            $api->subscribeToLists($subscriber['id'], array($list_id), $options);
            if ($already_subscribed) {
                return 'already_subscribed';
            }
        } catch (\MailPoet\API\MP\v1\APIException $error) {
            if ((int) $error->getCode() !== 4) {
                throw $error;
            }
            $api->addSubscriber(array('email' => $email), array($list_id), $options);
        }
        return 'confirmation_sent';
    } catch (\Throwable $error) {
        return new WP_Error('mailpoet_failed');
    }
}

function luxureat_static_newsletter_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_newsletter')) {
        wp_send_json_error(array('message' => "请刷新页面后重试。
Please refresh the page and try again."), 403);
    }
    if (!empty($_POST['company']) || !luxureat_static_verify_bot_challenge()) {
        wp_send_json_error(array('message' => "安全验证失败，请刷新页面后重试。
Security verification failed. Please refresh the page and try again."), 403);
    }
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    if (!is_email($email)) {
        wp_send_json_error(array('message' => "请输入正确的邮箱格式。
Please enter a valid email address."), 400);
    }
    $remote_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $rate_key = 'lux_newsletter_' . hash_hmac('sha256', strtolower($email) . '|' . $remote_address, wp_salt('nonce'));
    if (get_transient($rate_key)) {
        wp_send_json_error(array('message' => "确认邮件已经发送，请检查收件箱或垃圾邮件。
A confirmation email has already been sent. Please check your inbox or spam folder."), 429);
    }
    $subscribed = luxureat_static_mailpoet_subscribe($email);
    if (is_wp_error($subscribed)) {
        wp_send_json_error(array('message' => "订阅失败，请稍后再试。
Subscription failed. Please try again later."), 503);
    }
    set_transient($rate_key, '1', 10 * MINUTE_IN_SECONDS);
    wp_send_json_success(array('state' => 'confirmation_sent', 'message' => "如果该邮箱尚未订阅，确认邮件将会发送。请检查收件箱或垃圾邮件。
If this email is not already subscribed, a confirmation message will be sent. Please check your inbox or spam folder."));
}
add_action('wp_ajax_nopriv_luxureat_newsletter', 'luxureat_static_newsletter_ajax');
add_action('wp_ajax_luxureat_newsletter', 'luxureat_static_newsletter_ajax');

function luxureat_static_send_verification($user_id, $lang) {
    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }
    $token = wp_generate_password(48, false, false);
    update_user_meta($user_id, '_luxureat_email_verified', '0');
    update_user_meta($user_id, '_luxureat_email_token', hash_hmac('sha256', $token, wp_salt('auth')));
    update_user_meta($user_id, '_luxureat_email_expires', time() + DAY_IN_SECONDS);
    update_user_meta($user_id, '_luxureat_email_lang', $lang);
    $url = add_query_arg(array(
        'luxureat_verify' => '1',
        'user' => $user_id,
        'token' => $token,
    ), home_url('/'));
    $is_zh = $lang === 'zh';
    $subject = $is_zh ? '验证您的 LuxurEat（露意膳）账号' : 'Verify your LuxurEat account';
    $body = $is_zh
        ? "请点击以下链接验证邮箱并完成账号注册：

" . $url . "

此链接将在24小时后失效。"
        : "Open the link below to verify your email and finish creating your account:

" . $url . "

This link expires in 24 hours.";
    return wp_mail($user->user_email, $subject, $body);
}

function luxureat_static_verify_email() {
    if (!isset($_GET['luxureat_verify'], $_GET['user'], $_GET['token'])) {
        return;
    }
    $user_id = absint($_GET['user']);
    $token = sanitize_text_field(wp_unslash($_GET['token']));
    $lang = get_user_meta($user_id, '_luxureat_email_lang', true) === 'en' ? 'en' : 'zh';
    $expected = (string) get_user_meta($user_id, '_luxureat_email_token', true);
    $expires = (int) get_user_meta($user_id, '_luxureat_email_expires', true);
    $valid = $expected !== ''
        && $expires >= time()
        && hash_equals($expected, hash_hmac('sha256', $token, wp_salt('auth')));
    if ($valid) {
        update_user_meta($user_id, '_luxureat_email_verified', '1');
        delete_user_meta($user_id, '_luxureat_email_token');
        delete_user_meta($user_id, '_luxureat_email_expires');
        if (get_user_meta($user_id, '_luxureat_newsletter_pending', true) === '1') {
            $user = get_userdata($user_id);
            if ($user) {
                luxureat_static_mailpoet_subscribe($user->user_email);
            }
            delete_user_meta($user_id, '_luxureat_newsletter_pending');
        }
    }
    $home = function_exists('luxureat_static_url') ? luxureat_static_url($lang) : home_url($lang === 'en' ? '/en/' : '/');
    wp_safe_redirect(add_query_arg('account', $valid ? 'verified' : 'verification-failed', $home));
    exit;
}
add_action('template_redirect', 'luxureat_static_verify_email', -1);

function luxureat_static_require_verified_email($user) {
    if ($user instanceof WP_User && get_user_meta($user->ID, '_luxureat_email_verified', true) === '0') {
        $message = determine_locale() === 'zh_CN'
            ? '请先打开验证邮件完成邮箱验证。'
            : 'Please verify your email using the link we sent before signing in.';
        return new WP_Error('luxureat_email_unverified', $message);
    }
    return $user;
}
add_filter('authenticate', 'luxureat_static_require_verified_email', 30);
add_filter('login_errors', function () {
    return determine_locale() === 'zh_CN' ? '登录信息不正确。' : 'The sign-in details are incorrect.';
});

function luxureat_static_rate_keys($scope, $identifier) {
    $remote_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $identifier = strtolower(trim((string) $identifier));
    $salt = wp_salt('nonce');
    return array(
        'ip' => 'lux_rate_' . hash_hmac('sha256', $scope . '|ip|' . $remote_address, $salt),
        'identifier' => 'lux_rate_' . hash_hmac('sha256', $scope . '|identifier|' . $identifier, $salt),
    );
}

function luxureat_static_rate_consume($scope, $identifier, $ip_limit, $identifier_limit, $window) {
    $keys = luxureat_static_rate_keys($scope, $identifier);
    $limits = array('ip' => $ip_limit, 'identifier' => $identifier_limit);
    foreach ($keys as $type => $key) {
        if ((int) get_transient($key) >= $limits[$type]) {
            return false;
        }
    }
    foreach ($keys as $key) {
        set_transient($key, (int) get_transient($key) + 1, $window);
    }
    return true;
}

function luxureat_static_rate_reset($scope, $identifier) {
    foreach (luxureat_static_rate_keys($scope, $identifier) as $key) {
        delete_transient($key);
    }
}

function luxureat_static_account_ajax() {
    $is_zh = isset($_POST['lang']) && sanitize_key(wp_unslash($_POST['lang'])) === 'zh';
    $message = function ($zh, $en) use ($is_zh) { return $is_zh ? $zh : $en; };
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_account')) {
        wp_send_json_error(array('message' => $message('请刷新页面后重试。', 'Please refresh the page and try again.')), 403);
    }
    if (!empty($_POST['company']) || !luxureat_static_verify_bot_challenge()) {
        wp_send_json_error(array('message' => $message('安全验证失败，请刷新页面后重试。', 'Security verification failed. Please refresh the page and try again.')), 403);
    }
    if (is_user_logged_in()) {
        wp_send_json_success();
    }

    $mode = isset($_POST['mode']) ? sanitize_key(wp_unslash($_POST['mode'])) : 'login';
    $raw_email = isset($_POST['email']) ? trim((string) wp_unslash($_POST['email'])) : '';
    $email = sanitize_email($raw_email);
    $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
    if (!is_email($email)) {
        wp_send_json_error(array('message' => $message('电子邮箱不存在或格式错误。', 'The email address does not exist or is invalid.'), 'field' => 'email'), 400);
    }

    if ($mode === 'forgot') {
        if (!luxureat_static_rate_consume('forgot', $email, 5, 3, HOUR_IN_SECONDS)) {
            wp_send_json_error(array('message' => $message('请求过于频繁，请稍后再试。', 'Too many requests. Please try again later.')), 429);
        }
        $user = get_user_by('email', $email);
        if ($user) {
            update_user_meta($user->ID, 'locale', $is_zh ? 'zh_CN' : 'en_US');
            retrieve_password($user->user_login);
        }
        wp_send_json_success(array('message' => $message('如果该邮箱已注册，密码重置链接已发送，请检查收件箱和垃圾邮件。', 'If the email is registered, a reset link has been sent. Please check your inbox and spam folder.')));
    }

    if ($mode === 'register') {
        if (!function_exists('wc_create_new_customer') || get_option('woocommerce_enable_myaccount_registration') !== 'yes') {
            wp_send_json_error(array('message' => $message('暂未开放账号注册。', 'Account registration is not available yet.')), 403);
        }
        if (empty($_POST['consent'])) {
            wp_send_json_error(array('message' => $message('请先阅读并同意用户服务协议和隐私政策。', 'Please read and agree to the Terms of Service and Privacy Policy.')), 400);
        }
        if (!luxureat_static_strong_password($password, $email)) {
            wp_send_json_error(array('message' => $message('密码至少 12 位，并须包含字母和数字。', 'Use at least 12 characters with letters and numbers.')), 400);
        }
        if (!luxureat_static_rate_consume('register', $email, 8, 4, HOUR_IN_SECONDS)) {
            wp_send_json_error(array('message' => $message('请求过于频繁，请稍后再试。', 'Too many requests. Please try again later.')), 429);
        }
        $existing = get_user_by('email', $email);
        if ($existing) {
            $verification_expires = (int) get_user_meta($existing->ID, '_luxureat_email_expires', true);
            if (get_user_meta($existing->ID, '_luxureat_email_verified', true) === '0' && $verification_expires < time()) {
                luxureat_static_send_verification($existing->ID, $is_zh ? 'zh' : 'en');
            }
            wp_send_json_success(array(
                'message' => $message('如果该邮箱可以注册，验证邮件将会发送，请检查收件箱和垃圾邮件。', 'If this email can be registered, a verification message will be sent. Please check your inbox and spam folder.'),
                'requiresVerification' => true,
            ));
        }
        $user_id = wc_create_new_customer($email, '', $password);
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $message('暂时无法创建账号，请稍后再试。', 'The account could not be created. Please try again later.'), 'field' => 'feedback'), 400);
        }
        update_user_meta($user_id, 'locale', $is_zh ? 'zh_CN' : 'en_US');
        if (!empty($_POST['newsletter'])) {
            update_user_meta($user_id, '_luxureat_newsletter_pending', '1');
        } else {
            delete_user_meta($user_id, '_luxureat_newsletter_pending');
        }
        if (!luxureat_static_send_verification($user_id, $is_zh ? 'zh' : 'en')) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
            wp_delete_user($user_id);
            wp_send_json_error(array('message' => $message('验证邮件暂时无法发送，请稍后再试。', 'The verification email could not be sent. Please try again later.'), 'field' => 'feedback'), 500);
        }
        wp_send_json_success(array(
            'message' => $message('验证邮件已发送，请打开邮件中的链接完成注册后再登录。', 'A verification email has been sent. Open its link to finish registration before signing in.'),
            'requiresVerification' => true,
        ));
    }

    if (!luxureat_static_rate_consume('login', $email, 10, 20, 15 * MINUTE_IN_SECONDS)) {
        wp_send_json_error(array('message' => $message('登录尝试过于频繁，请稍后再试。', 'Too many sign-in attempts. Please try again later.'), 'field' => 'feedback'), 429);
    }
    $invalid_login = array('message' => $message('邮箱或密码不正确。', 'Incorrect email or password.'), 'field' => 'feedback');
    $user = get_user_by('email', $email);
    if (!$user || get_user_meta($user->ID, '_luxureat_email_verified', true) === '0' || $password === '') {
        wp_send_json_error($invalid_login, 401);
    }
    $credentials = array(
        'user_login' => $user->user_login,
        'user_password' => $password,
        'remember' => !empty($_POST['remember']),
    );
    $signed_in = wp_signon($credentials, is_ssl());
    if (is_wp_error($signed_in)) {
        wp_send_json_error($invalid_login, 401);
    }
    luxureat_static_rate_reset('login', $email);
    wp_send_json_success();
}
add_action('wp_ajax_nopriv_luxureat_account', 'luxureat_static_account_ajax');
add_action('wp_ajax_luxureat_account', 'luxureat_static_account_ajax');

function luxureat_static_contact_ajax() {
    $is_zh = isset($_POST['lang']) && sanitize_key(wp_unslash($_POST['lang'])) === 'zh';
    $message = function ($zh, $en) use ($is_zh) { return $is_zh ? $zh : $en; };
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_contact')) {
        wp_send_json_error(array('message' => $message('请刷新页面后重试。', 'Please refresh the page and try again.')), 403);
    }
    if (!empty($_POST['company'])) {
        wp_send_json_error(array('message' => $message('安全验证失败，请刷新页面后重试。', 'Security verification failed. Please refresh the page and try again.')), 403);
    }

    $name = isset($_POST['name']) ? trim(sanitize_text_field(wp_unslash($_POST['name']))) : '';
    $phone = isset($_POST['phone']) ? trim(sanitize_text_field(wp_unslash($_POST['phone']))) : '';
    $raw_email = isset($_POST['email']) ? trim((string) wp_unslash($_POST['email'])) : '';
    $email = sanitize_email($raw_email);
    $inquiry_type = isset($_POST['inquiry_type']) ? trim(sanitize_text_field(wp_unslash($_POST['inquiry_type']))) : '';
    $content = isset($_POST['message']) ? trim(sanitize_textarea_field(wp_unslash($_POST['message']))) : '';
    $allowed_types = array(
        '产品与采购咨询', '经销及渠道合作', '酒店餐饮与专业供应', '自有品牌与私人定制',
        '企业礼赠与项目合作', '品牌、媒体合作', '其他',
        'Product & Purchasing Enquiries', 'Distribution & Channel Partnerships',
        'Hospitality, Catering & Professional Supply', 'Private Label & Bespoke Customisation',
        'Corporate Gifting & Project Partnerships', 'Brand & Media Partnerships', 'Other',
    );
    if ($name === '' || $phone === '' || $content === '' || !in_array($inquiry_type, $allowed_types, true)) {
        wp_send_json_error(array('message' => $message('请填写所有必填信息。', 'Please complete all required fields.')), 400);
    }
    if (strlen($name) > 240 || strlen($phone) > 120 || strlen($content) > 12000 || ($raw_email !== '' && !is_email($email))) {
        wp_send_json_error(array('message' => $message('请检查所填信息后重试。', 'Please check the information and try again.')), 400);
    }

    $remote_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
    $rate_key = 'lux_contact_' . hash_hmac('sha256', $remote_address, wp_salt('nonce'));
    if (get_transient($rate_key)) {
        wp_send_json_error(array('message' => $message('信息已提交，请稍后再试。', 'Your message was submitted. Please wait before trying again.')), 429);
    }

    $subject = $name . ' + ' . $inquiry_type . ' + ' . $phone;
    $not_provided = $message('未提供', 'Not provided');
    $body = $message('姓名', 'Name') . '：' . $name . "
"
        . $message('电话', 'Phone') . '：' . $phone . "
"
        . $message('电子邮箱', 'Email') . '：' . ($email ?: $not_provided) . "

"
        . $message('咨询内容', 'Message') . "：
" . $content;
    $headers = $email ? array('Reply-To: ' . $name . ' <' . $email . '>') : array();
    if (!wp_mail('roberto@ugolinigroup.com', $subject, $body, $headers)) {
        wp_send_json_error(array('message' => $message('暂时无法发送，请稍后再试。', 'Your message could not be sent. Please try again later.')), 500);
    }
    set_transient($rate_key, 1, 30);
    wp_send_json_success(array('message' => $message('信息已发送，我们会尽快与您联系。', 'Your message has been sent. We will be in touch soon.')));
}
add_action('wp_ajax_nopriv_luxureat_contact', 'luxureat_static_contact_ajax');
add_action('wp_ajax_luxureat_contact', 'luxureat_static_contact_ajax');

function luxureat_static_password_hint() {
    return determine_locale() === 'zh_CN'
        ? '至少 12 位，须包含字母和数字。'
        : 'Use at least 12 characters with letters and numbers.';
}
add_filter('password_hint', 'luxureat_static_password_hint', 999);
add_filter('woocommerce_min_password_strength', '__return_zero', 999);
add_action('validate_password_reset', function ($errors, $user) {
    if (isset($_POST['pass1']) && !luxureat_static_strong_password((string) wp_unslash($_POST['pass1']), $user->user_email)) {
        $errors->add('password_reset_mismatch', luxureat_static_password_hint());
    }
}, 10, 2);

function luxureat_static_checkout_ajax() {
    $is_zh = isset($_POST['lang']) && sanitize_key(wp_unslash($_POST['lang'])) === 'zh';
    $message = function ($zh, $en) use ($is_zh) { return $is_zh ? $zh : $en; };
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'luxureat_checkout')) {
        wp_send_json_error(array('message' => $message('请刷新页面后重试。', 'Please refresh the page and try again.')), 403);
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => $message('请先登录账号，然后继续结算。', 'Please sign in before continuing to checkout.')), 401);
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
    update_user_meta(get_current_user_id(), 'luxureat_bag', luxureat_static_sanitize_bag($items));

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
    $checkout_url = $is_zh ? wc_get_checkout_url() : add_query_arg('lang', 'en', wc_get_checkout_url());
    wp_send_json_success(array('checkoutUrl' => $checkout_url));
}
add_action('wp_ajax_nopriv_luxureat_checkout', 'luxureat_static_checkout_ajax');
add_action('wp_ajax_luxureat_checkout', 'luxureat_static_checkout_ajax');

function luxureat_static_reduce_paid_bag($order_id) {
    $order = function_exists('wc_get_order') ? wc_get_order($order_id) : false;
    if (!$order || $order->get_meta('_luxureat_bag_reduced')) {
        return;
    }
    $user_id = $order->get_user_id();
    if (!$user_id) {
        return;
    }
    $purchased = array();
    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        $sku = $product ? $product->get_sku() : '';
        if ($sku !== '') {
            $purchased[$sku] = isset($purchased[$sku]) ? $purchased[$sku] + $item->get_quantity() : $item->get_quantity();
        }
    }
    $bag = array_values(array_filter(array_map(function ($item) use (&$purchased) {
        if (!isset($purchased[$item['sku']])) {
            return $item;
        }
        $paid_quantity = min($item['quantity'], $purchased[$item['sku']]);
        $quantity = $item['quantity'] - $paid_quantity;
        $purchased[$item['sku']] -= $paid_quantity;
        if ($purchased[$item['sku']] <= 0) {
            unset($purchased[$item['sku']]);
        }
        return $quantity > 0 ? array_merge($item, array('quantity' => $quantity)) : null;
    }, luxureat_static_get_bag($user_id))));
    update_user_meta($user_id, 'luxureat_bag', $bag);
    $order->update_meta_data('_luxureat_bag_reduced', 1);
    $order->save();
}
add_action('woocommerce_payment_complete', 'luxureat_static_reduce_paid_bag');
add_action('woocommerce_order_status_processing', 'luxureat_static_reduce_paid_bag');
add_action('woocommerce_order_status_completed', 'luxureat_static_reduce_paid_bag');

function luxureat_static_require_account_for_checkout() {
    if (function_exists('is_checkout') && is_checkout() && !is_user_logged_in() && !wp_doing_ajax()) {
        wp_safe_redirect(add_query_arg('account', 'required', home_url('/')));
        exit;
    }
}
add_action('template_redirect', 'luxureat_static_require_account_for_checkout', 0);

function luxureat_static_translate_shipping_rates($rates) {
    $language = function_exists('WC') && WC()->session ? WC()->session->get('luxureat_checkout_lang', 'zh') : 'zh';
    if ($language !== 'zh') {
        return $rates;
    }
    foreach ($rates as $rate) {
        if (is_object($rate) && method_exists($rate, 'get_method_id') && $rate->get_method_id() === 'free_shipping') {
            $rate->set_label('免费配送');
        }
    }
    return $rates;
}
add_filter('woocommerce_package_rates', 'luxureat_static_translate_shipping_rates', 100);

function luxureat_static_restrict_test_payment($gateways) {
    if (!current_user_can('manage_woocommerce')) {
        unset($gateways['cheque']);
    }
    return $gateways;
}
add_filter('woocommerce_available_payment_gateways', 'luxureat_static_restrict_test_payment', 100);

function luxureat_static_cart_item_images($images, $cart_item) {
    $product = isset($cart_item['data']) ? $cart_item['data'] : false;
    if (!$product instanceof WC_Product) {
        return $images;
    }
    if ($product->get_image_id()) {
        return $images;
    }
    $files = array(
        'imperial-beluga-30g' => 'academy/beluga-caviar-cover-new-page-bg.png',
        'royal-oscetra-30g' => 'academy/oscetra-caviar-cover.png',
        'mother-of-pearl-spoons' => 'journal/caviar-etiquette-service.webp',
        'champagne' => 'brand/home-values-caviar-plating.webp',
        'ice-server' => 'brand/partnership-solution-caviar-service.jpg',
    );
    $sku = $product->get_sku();
    if (!isset($files[$sku])) {
        return $images;
    }
    $url = get_template_directory_uri() . '/assets/media/' . $files[$sku];
    return array((object) array(
        'id' => $product->get_id(),
        'src' => $url,
        'thumbnail' => $url,
        'srcset' => '',
        'sizes' => '',
        'name' => $product->get_name(),
        'alt' => $product->get_name(),
    ));
}
add_filter('woocommerce_store_api_cart_item_images', 'luxureat_static_cart_item_images', 10, 2);

function luxureat_static_remove_checkout_marketing_optin($integration_registry) {
    if (
        is_object($integration_registry)
        && method_exists($integration_registry, 'is_registered')
        && method_exists($integration_registry, 'unregister')
        && $integration_registry->is_registered('mailpoet')
    ) {
        $integration_registry->unregister('mailpoet');
    }
}
add_action(
    'woocommerce_blocks_checkout_block_registration',
    'luxureat_static_remove_checkout_marketing_optin',
    100
);

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
    $headers['Content-Security-Policy'] = "frame-ancestors 'self'; base-uri 'self'; object-src 'none'; upgrade-insecure-requests";
    $headers['Content-Security-Policy-Report-Only'] = "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'self'; form-action 'self'; script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https://*.openstreetmap.org https://*.wp.com; font-src 'self' data:; connect-src 'self' https://www.google-analytics.com https://*.google-analytics.com https://*.wp.com; frame-src 'self' https://trufflebar.com https://*.google.com; media-src 'self'; upgrade-insecure-requests";
    $headers['X-Frame-Options'] = 'SAMEORIGIN';
    $headers['X-Content-Type-Options'] = 'nosniff';
    $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
    $headers['Permissions-Policy'] = 'camera=(), microphone=(), geolocation=()';
    $headers['Cross-Origin-Opener-Policy'] = 'same-origin-allow-popups';
    if (!is_admin() && !is_user_logged_in() && !is_account_page() && !is_cart() && !is_checkout()) {
        $headers['Cache-Control'] = 'public, max-age=300, stale-while-revalidate=86400';
    }

    return $headers;
}
add_filter('wp_headers', 'luxureat_static_cache_headers');

function luxureat_static_hide_server_version() {
    header_remove('X-Powered-By');
}
add_action('send_headers', 'luxureat_static_hide_server_version', 999);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
add_filter('the_generator', '__return_empty_string');

function luxureat_static_remove_xmlrpc_pingbacks($methods) {
    foreach (array('pingback.ping', 'pingback.extensions.getPingbacks') as $method) {
        unset($methods[$method]);
    }
    return $methods;
}
add_filter('xmlrpc_methods', 'luxureat_static_remove_xmlrpc_pingbacks', 999);

function luxureat_static_restrict_xmlrpc_request() {
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST && (!isset($_GET['for']) || $_GET['for'] !== 'jetpack')) {
        status_header(403);
        nocache_headers();
        exit('XML-RPC is available only for Jetpack.');
    }
}
add_action('init', 'luxureat_static_restrict_xmlrpc_request', 0);

function luxureat_static_cookie_samesite_headers() {
    $cookies = array_values(array_filter(headers_list(), function ($header) {
        return stripos($header, 'Set-Cookie:') === 0;
    }));
    if (!$cookies) {
        return;
    }

    header_remove('Set-Cookie');
    foreach ($cookies as $cookie) {
        if (
            stripos($cookie, 'Set-Cookie: wordpress_') === 0 &&
            stripos($cookie, 'samesite=') === false
        ) {
            $cookie .= '; SameSite=Lax';
        }
        header($cookie, false);
    }
}

function luxureat_static_register_cookie_header_callback() {
    if (function_exists('header_register_callback')) {
        header_register_callback('luxureat_static_cookie_samesite_headers');
    }
}
add_action('init', 'luxureat_static_register_cookie_header_callback', 0);

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

function luxureat_static_refresh_changed_routes() {
    $route_version = md5(wp_json_encode(array(luxureat_static_routes(), luxureat_static_aliases(), 'b77618f49f57d832cd3a10112e8a99b30b4040ac')));
    if (get_option('luxureat_static_route_version') === $route_version) {
        return;
    }
    flush_rewrite_rules(false);
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
    update_option('luxureat_static_route_version', $route_version, false);
}
add_action('init', 'luxureat_static_refresh_changed_routes', 20);
