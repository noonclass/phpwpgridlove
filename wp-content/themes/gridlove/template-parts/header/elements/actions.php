<?php $actions = array_keys( array_filter( gridlove_get_option( 'header_actions' ) ) );  ?>
<?php if ( !empty( $actions ) ): ?>
	<ul class="gridlove-actions gridlove-menu">
		<?php foreach ( $actions as $element ): ?>
			<?php get_template_part( 'template-parts/header/elements/' . $element ); ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>