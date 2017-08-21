<?php if ( $pagination = gridlove_numeric_pagination( __gridlove( 'previous_posts' ), __gridlove( 'next_posts' ) ) ) : ?>
	<nav class="gridlove-pagination">
		<?php echo wp_kses_post( $pagination ); ?>
	</nav>
<?php endif; ?>