<article <?php post_class('gridlove-post gridlove-post-a gridlove-box '. gridlove_highlight_post_class() ); ?>>

    <?php if( $fimg = gridlove_get_featured_image( 'a'. $post_col ) ) : ?>
        <div class="entry-image">
            <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo $fimg; ?></a>
            <div class="entry-category">
                <?php if( $icon = gridlove_get_option('lay_a_icon') ): ?>
                    <?php echo gridlove_get_format_icon(); ?>
                <?php endif; ?>

                <?php if( gridlove_get_option('lay_a_cat') ) : ?>
                    <?php echo gridlove_get_category(); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>


    <div class="box-inner-p">
        <div class="box-inner-ellipsis">
            <?php the_title( sprintf( '<h2 class="entry-title h3"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        </div>
        <?php if( $meta = gridlove_get_meta_data('a') ) : ?>
            <div class="entry-meta"><?php echo $meta; ?></div>
        <?php endif; ?>
    </div>

</article>