<?php if ( has_nav_menu( 'gridlove_social_menu' ) ) : ?>
<li>
		<?php wp_nav_menu( array( 'theme_location' => 'gridlove_social_menu', 'container'=> '', 'menu_class' => 'gridlove-soc-menu', 'link_before' => '<span class="gridlove-social-name">',
'link_after' => '</span>', ) ); ?>
</li>
<?php endif; ?>