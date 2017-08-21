<?php if( gridlove_get_option('single_share') ): ?>

	<?php $share_items = gridlove_get_social_share(); ?>

	<?php if ( !empty( $share_items ) ) : ?>

		<div class="gridlove-share-wrapper">
			<div class="gridlove-share gridlove-box gridlove-sticky-share">

				<?php foreach ( $share_items as $item ): ?>
					<?php echo $item; ?>
				<?php endforeach; ?>

			</div>
		</div>

	<?php endif; ?>
	
<?php endif; ?>