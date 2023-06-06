<?php

$cat = get_the_category( get_the_ID() )[0];
$cat = $cat ? '<a href="'.esc_url( get_category_link( $cat ) ).'">'.esc_html( $cat->name ).'</a>' : '';

?>
<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> post-preview post" itemscope="" itemtype="https://schema.org/Article">
    <a href="<?php the_permalink() ?>" class="post-thumbnail featuredimage">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>
    </a>
    <header class="post-header">
        <h2 class="post-title" itemprop="headline">
            <a class="post-title-link" href="<?php the_permalink() ?>"><?php the_title() ?></a>
        </h2>
        <!--
        <time class="post-meta post-date" itemprop="datePublished" content="<?php the_date('Y-m-d') ?>" datetime="<?php the_date('Y-m-d') ?>">
            <?php echo get_the_date() ?>
        </time>
        -->
        <?php if ( $cat ) { ?>
            <div class="post-meta post-category">
                <?php echo $cat ?>
            </div>
        <?php } ?>
    </header>
    <div class="post-excerpt">
        <?php the_excerpt() ?>
    </div>
    <a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ) ?>" class="post-readmore post-button"><?php _e( 'Read more' ) ?></a>
</article>
<?php
