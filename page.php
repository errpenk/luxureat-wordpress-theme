<?php
if (!defined('ABSPATH')) {
    exit;
}

status_header(200);
$is_account_page = function_exists('is_account_page') && is_account_page();
$is_checkout_page = function_exists('is_checkout') && is_checkout();
$is_customer_page = $is_account_page || $is_checkout_page;
$page_language = $is_customer_page && function_exists('luxureat_static_account_language') ? luxureat_static_account_language() : 'zh';
$is_zh_page = $page_language === 'zh';
if ($is_customer_page && function_exists('switch_to_locale')) {
    switch_to_locale($is_zh_page ? 'zh_CN' : 'en_US');
}
if ($is_customer_page && function_exists('WC') && WC()->session) {
    WC()->session->set('luxureat_checkout_lang', $page_language);
}
$account_endpoint = $is_account_page && function_exists('WC') && WC()->query ? WC()->query->get_current_endpoint() : '';
$is_account_dashboard = $is_account_page && is_user_logged_in() && !$account_endpoint;
$language_url = $is_checkout_page && function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : (function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : get_permalink());
$body_classes = array();
if ($is_account_dashboard) $body_classes[] = 'lux-account-dashboard-page';
if ($is_checkout_page) $body_classes[] = 'lux-checkout-page';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(trim('lux-wp-page-shell ' . implode(' ', $body_classes))); ?>>
<?php wp_body_open(); ?>
<header class="lux-wp-page-header">
    <a class="lux-wp-page-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>" alt="LuxurEat">
        <span>LuxurEat <i aria-hidden="true">｜</i> <small>露意膳</small></span>
    </a>
    <nav class="lux-wp-page-actions" aria-label="<?php echo esc_attr($is_zh_page ? '页面导航' : 'Page navigation'); ?>">
        <?php if ($is_customer_page) : ?>
            <span class="lux-language-switch" aria-label="<?php echo esc_attr($is_zh_page ? '语言' : 'Language'); ?>">
                <a href="<?php echo esc_url(add_query_arg('lang', 'zh', $language_url)); ?>"<?php echo $is_zh_page ? ' aria-current="page"' : ''; ?>>中文</a>
                <i aria-hidden="true">/</i>
                <a href="<?php echo esc_url(add_query_arg('lang', 'en', $language_url)); ?>"<?php echo !$is_zh_page ? ' aria-current="page"' : ''; ?>>EN</a>
            </span>
        <?php endif; ?>
        <a class="lux-wp-page-home" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($is_zh_page ? '返回首页' : 'Return to home'); ?></a>
    </nav>
</header>
<main class="lux-wp-page-main">
<?php while (have_posts()) : the_post(); ?>
    <header class="lux-wp-page-title">
        <p><?php echo esc_html($is_checkout_page ? ($is_zh_page ? '安全结算' : 'Secure checkout') : ($is_zh_page ? '会员中心' : 'Maison Account')); ?></p>
        <h1><?php echo esc_html($is_checkout_page ? ($is_zh_page ? '确认订单' : 'Checkout') : ($is_account_page ? ($is_zh_page ? '我的账户' : 'My account') : get_the_title())); ?></h1>
    </header>
    <div class="lux-wp-page-content"><?php the_content(); ?></div>
<?php endwhile; ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
