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