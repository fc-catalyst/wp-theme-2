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

?>

    <article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry" itemscope="" itemtype="https://schema.org/Article">
        <div class="post-content" itemprop="text">
            <?php if ( !get_post_meta( get_the_ID(), FCT_SET['pref'].'hide-h1', true ) ) { ?>
            <header class="entry-header gutenberg-container">
                <h1 class="entry-title" itemprop="headline"><?php the_title() ?></h1>
            </header>
            <?php } ?>
            <div class="entry-content gutenberg-container">
                <?php the_content() ?>
                <div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
                <?php get_template_part( 'template-parts/post', 'prevnext' ) ?>
            </div>
        </div>
    </article>

    <?php FCT\Sections\print_section( 'aside-left', '<aside class="sidebar-left gutenberg-sidebar">', '</aside>' ) ?>
    <?php FCT\Sections\print_section( 'aside-right', '<aside class="sidebar-right gutenberg-sidebar">', '</aside>' ) ?>
    
    <?php comments_template() ?>

<?php

    endwhile;
endif;

get_footer();
