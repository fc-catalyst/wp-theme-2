<?php

get_header();

?>
    <div class="gutenberg-container">
        <div class="blog-content">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'template-parts/post', 'tile' );

                endwhile;
                get_template_part( 'template-parts/pagination' );
            endif;
            ?>
        </div>
    </div>
<?php

get_footer();