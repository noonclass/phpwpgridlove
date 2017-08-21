<?php $cover = gridlove_get_cover_query(); ?>

<?php $slider_class = isset( $cover->post_count ) && $cover->post_count > 1 ? 'gridlove-cover-slider' : ''; ?>

<div id="cover" class="gridlove-cover-wrapper gridlove-cover-area">
    <div class="container">
        <div class="row gridlove-cover-arrows-middle gridlove-cover-3 <?php echo esc_attr( $slider_class ); ?>" data-items="3">
            
            <?php if( $cover->have_posts() ): ?>
            
                    <?php while( $cover->have_posts()): $cover->the_post(); ?>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <article <?php post_class('gridlove-post gridlove-post-d gridlove-box'); ?>>

                                    <?php if( $fimg = gridlove_get_featured_image('d4') ) : ?>
                                        <div class="entry-image">
                                            <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php echo $fimg; ?></a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="entry-overlay box-inner-p">
                                        <div class="entry-category">
                                            <?php if( $icon = gridlove_get_option('cover_3_icon') ): ?>
                                                <?php echo gridlove_get_format_icon(); ?>
                                            <?php endif; ?>

                                            <?php if( gridlove_get_option('cover_3_cat') ) : ?>
                                                <?php echo gridlove_get_category(); ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php the_title( sprintf( '<h2 class="entry-title h3"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                                        <?php if( $meta = gridlove_get_meta_data('cover_3') ) : ?>
                                            <div class="entry-meta"><?php echo $meta; ?></div>
                                        <?php endif; ?>
                                    </div>    

                                </article>
                            </div>
                    
                    <?php endwhile; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php wp_reset_postdata(); ?>