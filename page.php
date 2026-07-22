<?php
if (!defined('ABSPATH')) {
    exit;
}

status_header(200);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('lux-wp-page-shell'); ?>>
<?php wp_body_open(); ?>
<header class="lux-wp-page-header">
    <a class="lux-wp-page-brand" href="<?php echo esc_url(home_url('/')); ?>">LuxurEat <span>露意膳</span></a>
    <a class="lux-wp-page-home" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Return to home', 'luxureat-static'); ?></a>
</header>
<main class="lux-wp-page-main">
<?php while (have_posts()) : the_post(); ?>
    <header class="lux-wp-page-title">
        <p>Maison Account</p>
        <h1><?php the_title(); ?></h1>
    </header>
    <div class="lux-wp-page-content"><?php the_content(); ?></div>
<?php endwhile; ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
