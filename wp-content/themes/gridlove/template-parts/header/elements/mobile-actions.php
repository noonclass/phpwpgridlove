<?php $actions = array_keys( array_filter( gridlove_get_option( 'header_actions' ) ) );  ?>

<?php $sidebar_button = false; ?>

<ul class="gridlove-actions gridlove-menu">

	<?php if ( !empty( $actions ) ): ?>
			<?php foreach ( $actions as $element ): ?>
				<?php if( $element == 'sidebar-button') { $sidebar_button = true; } ?>
				<?php if( $element == 'search-form') { $element = 'search-button'; } ?>
				<?php get_template_part( 'template-parts/header/elements/' . $element ); ?>
			<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( !$sidebar_button ): ?>
		<?php get_template_part( 'template-parts/header/elements/sidebar-button' ); ?>
	<?php endif; ?>

</ul>