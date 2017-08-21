<?php if ( $audio = hybrid_media_grabber( array( 'type' => 'audio', 'split_media' => true ) ) ): ?>
		<div class="entry-media">
			<?php if( gridlove_get_option('single_fimg') && $fimg = gridlove_get_featured_image( 'single', true ) ) : ?> 
				 <?php echo $fimg; ?>
			<?php endif; ?>
			<?php echo $audio; ?>
		</div>
<?php endif; ?>