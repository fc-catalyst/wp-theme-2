<?php

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        
        $cats = get_the_category( get_the_ID() );
        $print_cats = [];
        if ( is_array( $cats ) ) {
            foreach ( $cats as $v ) {
                $print_cats[] = '<a href="'.esc_url( get_category_link( $v ) ).'">'.esc_html( $v->name ).'</a>';
            }
        }
        $print_cats = empty( $print_cats ) ? '' : '<p class="post-meta">'.implode( ' | ', $print_cats ).'</p>';

?>

    <article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry <?php echo $print_cats ? 'has-meta' : '' ?>" itemscope="" itemtype="https://schema.org/Article">
        <?php if ( !get_post_meta( get_the_ID(), FCT_SET['pref'].'hide-h1', true ) ) { ?>
        <header class="post-header gutenberg-container">
            <h1 class="post-title" itemprop="headline"><?php the_title() ?></h1>
            <?php echo $print_cats ?>
            <div style="height:0px" aria-hidden="true" class="wp-block-spacer"></div>
        </header>
        <?php } ?>
        <?php if ( function_exists('vvab_ymyl_verified_print') ) { vvab_ymyl_verified_print(); } ?>
        <div class="post-content gutenberg-container" itemprop="text">
            <?php if ( !get_post_meta( get_the_ID(), FCT_SET['pref'].'hide-featured-image', true ) && has_post_thumbnail() ) { ?>
                <figure class="wp-block-image size-full">
                <?php the_post_thumbnail( 'large', ['alt' => esc_attr( get_the_title() )] ) ?>
                </figure>
            <?php } ?>
            <?php the_content() ?>
            <div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
            <?php get_template_part( 'template-parts/post', 'prevnext' ) ?>
        </div>
    </article>

    <?php FCT\Sections\print_section( 'aside-left', '<aside class="sidebar-left gutenberg-sidebar">', '</aside>' ) ?>
    <?php FCT\Sections\print_section( 'aside-right', '<aside class="sidebar-right gutenberg-sidebar">', '</aside>' ) ?>
    
    <?php comments_template() ?>

<?php

    endwhile;
endif;

get_footer();
