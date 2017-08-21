<?php if( gridlove_get_option( 'single_related' ) ) : ?>
    
    <?php $related = gridlove_get_related_posts(); ?>

    <?php if( $related->have_posts() ): ?>

        <div class="gridlove-related">
            <div class="gridlove-module">

                <?php echo gridlove_get_heading( array( 'title' => '<h4 class="h2">'.__gridlove('related').'</h2>') ); ?>
                <div class="row">
                    <?php while( $related->have_posts() ) : $related->the_post(); ?>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <?php $post_col = 8; ?>
                            <?php include( locate_template('template-parts/layouts/content-b.php') ); ?>
                        </div>

                    <?php endwhile; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

<?php endif; ?>