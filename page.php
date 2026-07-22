<?php
if (!defined('ABSPATH')) {
    exit;
}

status_header(200);
$is_account_page = function_exists('is_account_page') && is_account_page();
$account_language = $is_account_page && function_exists('luxureat_static_account_language') ? luxureat_static_account_language() : 'zh';
$is_zh_account = $account_language === 'zh';
if ($is_account_page && function_exists('switch_to_locale')) {
    switch_to_locale($is_zh_account ? 'zh_CN' : 'en_US');
}
$account_endpoint = $is_account_page && function_exists('WC') && WC()->query ? WC()->query->get_current_endpoint() : '';
$is_account_dashboard = $is_account_page && is_user_logged_in() && !$account_endpoint;
$account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : get_permalink();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class($is_account_dashboard ? 'lux-wp-page-shell lux-account-dashboard-page' : 'lux-wp-page-shell'); ?>>
<?php wp_body_open(); ?>
<header class="lux-wp-page-header">
    <a class="lux-wp-page-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/brand/luxureat-logo.png'); ?>" alt="LuxurEat">
        <span>LuxurEat <small>露意膳</small></span>
    </a>
    <nav class="lux-wp-page-actions" aria-label="<?php echo esc_attr($is_zh_account ? '账户页导航' : 'Account page navigation'); ?>">
        <?php if ($is_account_page) : ?>
            <span class="lux-language-switch" aria-label="<?php echo esc_attr($is_zh_account ? '语言' : 'Language'); ?>">
                <a href="<?php echo esc_url(add_query_arg('lang', 'zh', $account_url)); ?>"<?php echo $is_zh_account ? ' aria-current="page"' : ''; ?>>中文</a>
                <i aria-hidden="true">/</i>
                <a href="<?php echo esc_url(add_query_arg('lang', 'en', $account_url)); ?>"<?php echo !$is_zh_account ? ' aria-current="page"' : ''; ?>>EN</a>
            </span>
        <?php endif; ?>
        <a class="lux-wp-page-home" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($is_zh_account ? '返回首页' : 'Return to home'); ?></a>
    </nav>
</header>
<main class="lux-wp-page-main">
<?php while (have_posts()) : the_post(); ?>
    <header class="lux-wp-page-title">
        <p><?php echo esc_html($is_zh_account ? '会员中心' : 'Maison Account'); ?></p>
        <h1><?php echo esc_html($is_account_page ? ($is_zh_account ? '我的账户' : 'My account') : get_the_title()); ?></h1>
    </header>
    <div class="lux-wp-page-content"><?php the_content(); ?></div>
<?php endwhile; ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
