<?php if ( has_nav_menu( 'gridlove_main_menu' ) ) : ?>
		<?php wp_nav_menu( array( 'theme_location' => 'gridlove_main_menu', 'container'=> '', 'menu_class' => 'gridlove-mobile-menu' ) ); ?>
<?php else: ?>
	<?php if ( current_user_can( 'manage_options' ) ): ?>
		<ul class="gridlove-mobile-menu">
			<li><a href="<?php echo esc_url(admin_url( 'nav-menus.php' )); ?>"><?php esc_html_e( 'Click here to add main navigation', 'gridlove' ); ?></a></li>
		</ul>
	<?php endif; ?>
<?php endif; ?>
