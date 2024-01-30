<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
		<meta name="format-detection" content="telephone=no">
		<meta name="google-site-verification" content="zYeV7jONmGN8FPz2SDv6tmlocdu6tvCm3Ebf-Zrwp2g">
		<?php wp_head(); ?>
	</head>

<body <?php body_class(); ?> id="document-top">

	<ul class="skip-links">
        <li><a href="#top-nav" class="screen-reader-shortcut"><?php _e( 'Skip to main menu', 'fct' ) ?></a></li>
		<li><a href="#main-content" class="screen-reader-shortcut"><?php _e( 'Skip to main content', 'fct' ) ?></a></li>
		<li><a href="#footer" class="screen-reader-shortcut"><?php _e( 'Skip to footer', 'fct' ) ?></a></li>
	</ul>

	<?php
		// print header ane menu
		if ( FCT\Sections\get_section( 'header' )->menu_below ) {
			FCT\Sections\print_section( 'header', '<header class="site-header gutenberg-container">', '</header>' );
			get_template_part( 'template-parts/navigation', 'top' );
		} else {
			get_template_part( 'template-parts/navigation', 'top' );
			FCT\Sections\print_section( 'header', '<header class="site-header gutenberg-container">', '</header>' );
		}
    	
	?>

	<main id="main-content">
