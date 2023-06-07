<?php

$post_type = get_post_type_object( get_post_type() );
$post_type_name = $post_type ? $post_type->labels->singular_name : '';

$catlist = get_the_category( get_the_ID() ) ?: [];
$categories = array_reduce( $catlist, function($result, $item) {
    $result .= '<a href="'.esc_url( get_category_link( $item ) ).'">'.esc_html( $item->name ).'</a> ';
    return $result;
}, '' );
$categories = empty( $catlist ) ? '' : ( count( $catlist ) > 1 ? __( 'Categories' ) : __( 'Category' )  ).': '.$categories;

$meta = array_values( array_filter( [ $post_type_name, $categories ] ) );

?>
<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> post-preview post" itemscope="" itemtype="https://schema.org/Article">
    <a href="<?php the_permalink() ?>" class="post-thumbnail featuredimage">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium' ); } ?>
    </a>
    <header class="post-header">
        <h2 class="post-title" itemprop="headline">
            <a class="post-title-link" href="<?php the_permalink() ?>"><?php the_title() ?></a>
        </h2>
    </header>
    <div class="post-excerpt">
        <?php the_excerpt() ?>
    </div>
    <footer class="post-footer">
        <?php if ( !empty( $meta ) ) { ?>
        <div class="post-meta">
            <?php echo implode( ', ', $meta ) ?>
        </div>
        <?php } ?>
        <!--
        <time class="post-date post-meta" itemprop="datePublished" content="<?php the_date('Y-m-d') ?>" datetime="<?php the_date('Y-m-d') ?>">
        <?php echo get_the_date() ?>
        </time>
        -->
    </footer>
    <a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ) ?>" class="post-readmore post-button"><?php _e( 'Read more' ) ?></a>
</article>
<?php