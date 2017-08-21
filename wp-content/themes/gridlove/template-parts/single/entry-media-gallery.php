<?php if ( $video = hybrid_media_grabber( array( 'type' => 'gallery', 'split_media' => true ) ) ): ?>
		<div class="entry-media"><?php echo $video; ?></div>
<?php endif; ?>