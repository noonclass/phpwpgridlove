<?php if( $ad = gridlove_get_option('ad_above_footer') ): ?>
	<div class="conatiner"><div class="gridlove-ad"><?php echo do_shortcode( $ad ); ?></div></div>
<?php endif; ?>