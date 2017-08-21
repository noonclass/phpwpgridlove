<?php if( gridlove_get_option('page_fimg') && !gridlove_is_paginated_post() &&  $fimg = gridlove_get_featured_image( 'single', true ) ) : ?>
    <div class="entry-image">
        <?php echo $fimg; ?>
         <?php if( gridlove_get_option( 'page_fimg_cap' ) && $caption = get_post( get_post_thumbnail_id())->post_excerpt) : ?>
				<figure class="wp-caption-text"><?php echo wp_kses_post( $caption );  ?></figure>
		<?php endif; ?>
    </div>
<?php endif; ?>