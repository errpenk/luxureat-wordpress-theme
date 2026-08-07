<?php
// LUXUREAT_STATIC_SEO_BEGIN
// Generated from site.config.mjs by tools/generate-static-seo.mjs. Do not edit in the theme repository.

if (!defined('ABSPATH')) {
    exit;
}

function luxureat_static_seo_map() {
    static $map = null;
    if (is_array($map)) {
        return $map;
    }

    $json = <<<'LUXUREAT_SEO_JSON'
{
  "/": {
    "title": "LuxurEat 露意膳｜意大利松露、鱼子酱与高端食品",
    "description": "LuxurEat（露意膳）连接意大利优质食材与中国市场，精选松露、鱼子酱及高端食品，并提供餐饮、渠道与品牌合作方案。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/",
    "enPath": "/en/",
    "xDefaultPath": "/"
  },
  "/journal/": {
    "title": "关于 LuxurEat 露意膳｜品牌、全球网络与专业经验",
    "description": "了解 LuxurEat（露意膳）的品牌背景、意大利美食专业经验、全球业务网络，以及面向中国与国际市场的长期合作理念。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/journal/",
    "enPath": "/en/journal/",
    "xDefaultPath": "/journal/"
  },
  "/caviar/": {
    "title": "意大利松露、鱼子酱与高端食品｜LuxurEat 露意膳",
    "description": "浏览 LuxurEat（露意膳）精选松露、鱼子酱与意大利高端食品，面向高端零售、酒店餐饮、礼赠与专业渠道。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/caviar/",
    "enPath": "/en/products/",
    "xDefaultPath": "/caviar/"
  },
  "/rituals/": {
    "title": "意大利食谱与餐饮灵感｜LuxurEat 露意膳",
    "description": "探索松露、鱼子酱及意大利高端食材的食谱、搭配方式与餐饮应用灵感，让专业食材自然进入不同用餐场景。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/rituals/",
    "enPath": "/en/rituals/",
    "xDefaultPath": "/rituals/"
  },
  "/news/": {
    "title": "品牌新闻与全球展会动态｜LuxurEat 露意膳",
    "description": "查看 LuxurEat（露意膳）的品牌新闻、国际展会、市场活动与合作动态，了解我们在中国及全球市场的最新进展。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/news/",
    "enPath": "/en/news/",
    "xDefaultPath": "/news/"
  },
  "/blog/": {
    "title": "意大利美食知识博客｜LuxurEat 露意膳",
    "description": "阅读关于意大利食材、产区、橄榄油、Gelato、营养与美食文化的知识内容，建立从产地到餐桌的专业理解。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/blog/",
    "enPath": "/en/blog/",
    "xDefaultPath": "/blog/"
  },
  "/certification/": {
    "title": "品质认证与食品合规｜LuxurEat 露意膳",
    "description": "了解 LuxurEat（露意膳）关注的食品品质、安全、认证与合规标准，以及面向中国和国际市场的专业保障。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/certification/",
    "enPath": "/en/certification/",
    "xDefaultPath": "/certification/"
  },
  "/gifting/": {
    "title": "商务合作、经销与定制方案｜LuxurEat 露意膳",
    "description": "LuxurEat（露意膳）面向经销商、酒店餐饮、零售与企业客户提供渠道合作、自有品牌、OEM、礼赠及定制解决方案。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/gifting/",
    "enPath": "/en/gifting/",
    "xDefaultPath": "/gifting/"
  },
  "/contact/": {
    "title": "联系 LuxurEat 露意膳｜中国与全球合作咨询",
    "description": "联系 LuxurEat（露意膳）中国团队，咨询产品采购、经销合作、酒店餐饮供应、自有品牌、企业礼赠及品牌合作。",
    "lang": "zh-CN",
    "locale": "zh_CN",
    "alternateLocale": "en_US",
    "zhPath": "/contact/",
    "enPath": "/en/contact/",
    "xDefaultPath": "/contact/"
  },
  "/en/": {
    "title": "LuxurEat | Italian Truffles, Caviar & Premium Foods",
    "description": "LuxurEat connects premium Italian gastronomy with China and global markets, offering curated truffles, caviar, fine foods and professional partnership solutions.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/",
    "enPath": "/en/",
    "xDefaultPath": "/"
  },
  "/en/journal/": {
    "title": "About LuxurEat | Brand, Global Network & Expertise",
    "description": "Discover LuxurEat's background, Italian food expertise, global network and long-term approach to premium gastronomy and international partnerships.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/journal/",
    "enPath": "/en/journal/",
    "xDefaultPath": "/journal/"
  },
  "/en/products/": {
    "title": "Italian Truffles, Caviar & Premium Foods | LuxurEat",
    "description": "Explore LuxurEat's curated selection of truffles, caviar and premium Italian foods for retail, hospitality, gifting and professional distribution.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/caviar/",
    "enPath": "/en/products/",
    "xDefaultPath": "/caviar/"
  },
  "/en/rituals/": {
    "title": "Italian Recipes & Culinary Inspiration | LuxurEat",
    "description": "Explore recipes, pairings and culinary ideas for truffles, caviar and premium Italian ingredients across home, hospitality and professional dining occasions.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/rituals/",
    "enPath": "/en/rituals/",
    "xDefaultPath": "/rituals/"
  },
  "/en/news/": {
    "title": "Brand News & Global Events | LuxurEat",
    "description": "Follow LuxurEat brand news, international exhibitions, market activities and partnership updates across China and global markets.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/news/",
    "enPath": "/en/news/",
    "xDefaultPath": "/news/"
  },
  "/en/blog/": {
    "title": "Italian Food Knowledge & Culture | LuxurEat",
    "description": "Read about Italian ingredients, regions, olive oil, gelato, nutrition and food culture, from origin and production to tasting and everyday use.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/blog/",
    "enPath": "/en/blog/",
    "xDefaultPath": "/blog/"
  },
  "/en/certification/": {
    "title": "Quality, Certification & Food Compliance | LuxurEat",
    "description": "Learn about the quality, food safety, certification and compliance principles supporting LuxurEat products and international market operations.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/certification/",
    "enPath": "/en/certification/",
    "xDefaultPath": "/certification/"
  },
  "/en/gifting/": {
    "title": "Distribution, Private Label & Business Partnerships | LuxurEat",
    "description": "Explore LuxurEat distribution, hospitality supply, private label, OEM, corporate gifting and tailored partnership solutions for professional clients.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/gifting/",
    "enPath": "/en/gifting/",
    "xDefaultPath": "/gifting/"
  },
  "/en/contact/": {
    "title": "Contact LuxurEat | China & Global Partnerships",
    "description": "Contact LuxurEat for product purchasing, distribution, hospitality supply, private label, corporate gifting and brand partnership enquiries.",
    "lang": "en",
    "locale": "en_US",
    "alternateLocale": "zh_CN",
    "zhPath": "/contact/",
    "enPath": "/en/contact/",
    "xDefaultPath": "/contact/"
  }
}
LUXUREAT_SEO_JSON;

    $decoded = json_decode($json, true);
    $map = is_array($decoded) ? $decoded : array();
    return $map;
}

function luxureat_static_seo_path() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $request_path = parse_url($request_uri, PHP_URL_PATH);
    $request_path = is_string($request_path) ? $request_path : '/';
    $normalized = '/' . trim($request_path, '/');
    return $normalized === '/' ? '/' : $normalized . '/';
}

function luxureat_static_seo_data() {
    $map = luxureat_static_seo_map();
    $path = luxureat_static_seo_path();
    return isset($map[$path]) && is_array($map[$path]) ? $map[$path] : null;
}

function luxureat_static_seo_document_title($title) {
    $data = luxureat_static_seo_data();
    return $data && !empty($data['title']) ? $data['title'] : $title;
}
add_filter('pre_get_document_title', 'luxureat_static_seo_document_title', 999);

function luxureat_static_disable_yoast_presenters($presenters) {
    return luxureat_static_seo_data() ? array() : $presenters;
}
add_filter('wpseo_frontend_presenters', 'luxureat_static_disable_yoast_presenters', 999);

function luxureat_static_prepare_seo_head() {
    if (!luxureat_static_seo_data()) {
        return;
    }
    remove_action('wp_head', 'rel_canonical');
}
add_action('template_redirect', 'luxureat_static_prepare_seo_head', -150);

function luxureat_static_output_seo_head() {
    $data = luxureat_static_seo_data();
    if (!$data) {
        return;
    }

    $path = luxureat_static_seo_path();
    $canonical = home_url($path);
    $zh_url = home_url($data['zhPath']);
    $en_url = home_url($data['enPath']);
    $x_default_url = home_url($data['xDefaultPath']);
    $image_url = get_template_directory_uri() . '/assets/media/brand/home-hero-truffle-poster.webp';
    $logo_url = get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png';

    if (!current_theme_supports('title-tag')) {
        echo '<title>' . esc_html($data['title']) . '</title>' . "
";
    }
    echo '<meta name="description" content="' . esc_attr($data['description']) . '">' . "
";
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "
";
    echo '<link rel="alternate" hreflang="zh-CN" href="' . esc_url($zh_url) . '">' . "
";
    echo '<link rel="alternate" hreflang="en" href="' . esc_url($en_url) . '">' . "
";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($x_default_url) . '">' . "
";

    echo '<meta property="og:type" content="website">' . "
";
    echo '<meta property="og:site_name" content="LuxurEat">' . "
";
    echo '<meta property="og:title" content="' . esc_attr($data['title']) . '">' . "
";
    echo '<meta property="og:description" content="' . esc_attr($data['description']) . '">' . "
";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "
";
    echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "
";
    echo '<meta property="og:locale" content="' . esc_attr($data['locale']) . '">' . "
";
    echo '<meta property="og:locale:alternate" content="' . esc_attr($data['alternateLocale']) . '">' . "
";

    echo '<meta name="twitter:card" content="summary_large_image">' . "
";
    echo '<meta name="twitter:title" content="' . esc_attr($data['title']) . '">' . "
";
    echo '<meta name="twitter:description" content="' . esc_attr($data['description']) . '">' . "
";
    echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "
";

    $schema = array(
        '@context' => 'https://schema.org',
        '@graph' => array(
            array(
                '@type' => 'Organization',
                '@id' => home_url('/#organization'),
                'name' => 'LuxurEat',
                'alternateName' => '露意膳',
                'url' => home_url('/'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => $logo_url,
                ),
                'email' => 'china@luxureat.com',
            ),
            array(
                '@type' => 'WebSite',
                '@id' => home_url('/#website'),
                'url' => home_url('/'),
                'name' => 'LuxurEat',
                'publisher' => array('@id' => home_url('/#organization')),
                'inLanguage' => array('zh-CN', 'en'),
            ),
            array(
                '@type' => 'WebPage',
                '@id' => $canonical . '#webpage',
                'url' => $canonical,
                'name' => $data['title'],
                'description' => $data['description'],
                'isPartOf' => array('@id' => home_url('/#website')),
                'about' => array('@id' => home_url('/#organization')),
                'inLanguage' => $data['lang'],
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "
";
}
add_action('wp_head', 'luxureat_static_output_seo_head', 0);

// LUXUREAT_STATIC_SEO_END
