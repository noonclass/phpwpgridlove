<div class="gridlove-cover-item gridlove-cover-2">
    <?php if( $fimg = gridlove_get_featured_image('cover') ) : ?>
    <div class="gridlove-cover-bg">
        <span class="gridlove-cover">
        <?php echo $fimg; ?>
         <?php if( gridlove_get_option( 'page_fimg_cap' ) && $caption = get_post( get_post_thumbnail_id())->post_excerpt) : ?>
                <figure class="wp-caption-text"><?php echo wp_kses_post( $caption );  ?></figure>
        <?php endif; ?>   
        <span class="gridlove-hidden-overlay"></span>
        </span>
    </div>
    <?php endif; ?>
    <div class="gridlove-cover-content gridlove-cover-reset">
        <div class="overlay-vh-center">
            <div class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </div>
        </div>
    </div>
</div>
