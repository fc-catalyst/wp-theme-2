<?php

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
?>

	<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry" itemscope="" itemtype="https://schema.org/CreativeWork">
        <?php if ( !get_post_meta( get_the_ID(), FCT_SET['pref'].'hide-h1', true ) ) { ?>
        <header class="post-header gutenberg-container">
            <h1 class="post-title" itemprop="headline"><?php the_title() ?></h1>
        </header>
        <?php } ?>
        <div class="post-content gutenberg-container" itemprop="text">
            <?php the_content() ?>
        </div>
	</article>

    <?php comments_template() ?>

<?php

    endwhile;
endif;

get_footer();
