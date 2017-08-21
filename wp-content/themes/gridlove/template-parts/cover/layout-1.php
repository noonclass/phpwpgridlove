<?php $cover = gridlove_get_cover_query(); ?>

<?php $slider_class = isset( $cover->post_count ) && $cover->post_count > 1 ? 'gridlove-cover-slider' : ''; ?>

<div id="cover" class="gridlove-cover-area gridlove-cover-1 <?php echo esc_attr( $slider_class ); ?>" data-items="1">

    <?php if( $cover->have_posts() ): ?>

        <?php while( $cover->have_posts()): $cover->the_post(); ?>

            <div class="gridlove-cover-item">

                <div class="gridlove-cover-bg">
                    <?php if( $fimg = gridlove_get_featured_image('cover') ) : ?>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="gridlove-cover">
                            <?php echo $fimg; ?>
                            <span class="gridlove-hidden-overlay"></span>
                        </a>
                    <?php endif; ?>  
                </div>

                <div class="gridlove-cover-content">
                    <div class="entry-header">
                        
                        <div class="entry-category">
                            <?php if( $icon = gridlove_get_option('cover_1_icon') ): ?>
                                <?php echo gridlove_get_format_icon(); ?>
                            <?php endif; ?>

                            <?php if( gridlove_get_option('cover_1_cat') ) : ?>
                                <?php echo gridlove_get_category(); ?>
                            <?php endif; ?>
                        </div>
                        
                        <?php the_title( sprintf( '<h2 class="entry-title h1"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                        
                        <?php if( gridlove_get_option('cover_1_excerpt') ) : ?>
                            <div class="entry-content"><?php echo gridlove_get_excerpt( gridlove_get_option('cover_1_excerpt_limit') ); ?></div>
                        <?php endif; ?>
                        
                        <?php if( $meta = gridlove_get_meta_data('cover_1') ) : ?>
                            <div class="entry-meta"><?php echo $meta; ?></div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        
        <?php endwhile; ?>

    <?php endif; ?>


</div>

<?php wp_reset_postdata(); ?>