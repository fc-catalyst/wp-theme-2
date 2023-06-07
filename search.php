<?php

get_header();

?>
    <div class="gutenberg-container">
        <div class="search-content">
            <header><!-- ++replace with the gutenberg header ++ add the line for search results-->
                <h1>
                    <?php _e( 'Search results for', 'fct' ) ?>
                    <small> <?php the_search_query() ?></small>
                </h1>
            </header>
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/search', 'row' );

                endwhile;
                get_template_part( 'template-parts/pagination' );
            endif;
            if ( !have_posts() ) :
                ?>
                    <h2><?php _e( 'No results found.' ) ?></h2>
                <?php
            endif;
            ?>
        </div>
    </div>
<?php

get_footer();
