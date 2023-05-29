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
		<li><a href="#main-content" class="screen-reader-shortcut"><?php _e( 'Skip to main content', 'fct' ) ?></a></li>
		<li><a href="#footer" class="screen-reader-shortcut"><?php _e( 'Skip to footer', 'fct' ) ?></a></li>
	</ul>

	<header class="site-header gutenberg-container">
        <?php FCT\Sections\print_section( 'header' ) ?>
	</header>

    <?php if ( has_nav_menu( 'main' ) ) : ?>
    <input type="checkbox" id="nav-top-toggle" aria-hidden="true">
    <nav class="nav-top gutenberg-container" id="nav-top" aria-label="Main menu">
        <div>
            <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) { the_custom_logo(); } else { ?>
            <a href="<?php echo home_url() ?>" class="custom-logo-link" rel="home">
                <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/logo.svg' ?>" width="526" height="160" alt="<?php echo get_bloginfo( 'name' ) ?>"/>
            </a>
            <?php } ?>
            <label for="nav-top-toggle" class="hamburger"></label>
            <?php
                wp_nav_menu( [
                    'theme_location'  => is_user_logged_in() ? 'logged' : 'main',
                    'menu_class'      => 'menu menu-primary',
                    'menu_id'         => 'menu-primary'
                ] );
            ?>
        </div>
    </nav>
    <?php endif ?>

	<main id="main-content">
