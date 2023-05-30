<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
		<meta name="format-detection" content="telephone=no">
		<?php wp_head(); ?>
	</head>

<body <?php body_class(); ?> id="document-top">

	<ul class="skip-links">
        <li><a href="#top-nav" class="screen-reader-shortcut"><?php _e( 'Skip to main menu', 'fct' ) ?></a></li>
		<li><a href="#main-content" class="screen-reader-shortcut"><?php _e( 'Skip to main content', 'fct' ) ?></a></li>
		<li><a href="#footer" class="screen-reader-shortcut"><?php _e( 'Skip to footer', 'fct' ) ?></a></li>
	</ul>

	<header class="site-header gutenberg-container">
        <?php FCT\Sections\print_section( 'header' ) ?>
	</header>

    <?php get_template_part( 'template-parts/navigation', 'top' ) ?>

	<main id="main-content">
