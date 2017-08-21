<div class="gridlove-sidebar-action-wrapper">

	<span class="gridlove-action-close"><i class="fa fa-times" aria-hidden="true"></i></span>

	<div class="gridlove-sidebar-action-inside">

		<div class="hidden-lg-up widget gridlove-box widget_nav_menu">
			<?php get_template_part('template-parts/header/elements/mobile-menu'); ?>
		</div>

		<?php if( in_array( 'sidebar-button', array_keys( array_filter( gridlove_get_option( 'header_actions' ) ) ) ) ): ?>
			<?php if ( is_active_sidebar( 'gridlove_header_sidebar' ) ) : ?>
				<?php dynamic_sidebar( 'gridlove_header_sidebar' ); ?>
			<?php else: ?>
				<div class="widget gridlove-box">
					<p>	
						<?php echo wp_kses_post( sprintf( __( 'Your Header Sidebar area is currently empty. Hurry up and <a href="%s" target="_blank">add some widgets</a>.', 'gridlove' ), admin_url( 'widgets.php' ) ) ); ?>
					</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>


	</div>

</div>

<div class="gridlove-sidebar-action-overlay"></div>