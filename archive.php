<?php

get_header();

$pre = __( get_the_title( get_option( 'page_for_posts', true ) ), 'fct' );
$title = __( single_cat_title( '', false ), 'fct' );
$title = $pre ? '<small>' . $pre .':</small> ' . $title : $title;

?>
    <div class="gutenberg-container">
        <div class="blog-content">
            <header><!-- ++replace with the gutenberg header -->
                <h1><?php echo $title ?></h1>
            </header>
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