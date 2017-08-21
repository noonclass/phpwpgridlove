<article <?php post_class('gridlove-post gridlove-post-b gridlove-box '.gridlove_highlight_post_class() ); ?>>

    <?php if( $fimg = gridlove_get_featured_image( 'b'. $post_col ) ) : ?>
        <div class="entry-image">
            <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo $fimg; ?></a>
        </div>
    <?php endif; ?>

    <div class="box-inner-ptbr box-col-b entry-sm-overlay">
        <div class="box-inner-ellipsis">
            <div class="entry-category">
                <?php if( $icon = gridlove_get_option('lay_b_icon') ): ?>
                    <?php echo gridlove_get_format_icon(); ?>
                <?php endif; ?>

                <?php if( gridlove_get_option('lay_b_cat') ) : ?>
                    <?php echo gridlove_get_category(); ?>
                <?php endif; ?>
            </div>
            
            <?php the_title( sprintf( '<h2 class="entry-title h3"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <div class="entry-content"><?php echo gridlove_get_excerpt(); ?></div>

        </div>
  
        <?php if( $meta = gridlove_get_meta_data('b') ) : ?>
            <div class="entry-meta"><?php echo $meta; ?></div>
        <?php endif; ?> 
    </div>    


    
       
</article>