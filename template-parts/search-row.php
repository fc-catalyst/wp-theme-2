<?php

$cat = get_the_category( get_the_ID() )[0];
$cat = $cat
    ? ' | Category: <a href="'.esc_url( get_category_link( $cat ) ).'">'.esc_html( $cat->name ).'</a>'
    : '';

$post_type = get_post_type_object( get_post_type() );

$type = $post_type ? $post_type->labels->singular_name : '';

?>
<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry">
    <div class="entry-photo">
        <a class="entry-photo-link" href="<?php the_permalink() ?>">
            <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>
        </a>
    </div>
    <div>
        <header class="entry-header">
            <h2 class="entry-title">
                <a class="entry-title-link" href="<?php the_permalink() ?>"><?php the_title() ?></a>
            </h2>
        </header>
        <div class="entry-details">
            <div class="entry-excerpt">
                <?php the_excerpt() ?>
            </div>
        </div>
        <footer class="entry-meta">
            <?php echo $type ?>
            <?php echo $cat ?>
        </footer>
    </div>
</article>
<?php
