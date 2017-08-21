<?php if ( has_nav_menu( 'gridlove_social_menu' ) ) : ?>
<li class="gridlove-actions-button gridlove-social-icons">
	<span>
		<i class="fa fa-share-alt"></i>
	</span>
	<ul class="sub-menu">
	<li>
		<?php wp_nav_menu( array( 'theme_location' => 'gridlove_social_menu', 'container'=> '', 'menu_class' => 'gridlove-soc-menu', 'link_before' => '<span class="gridlove-social-name">', 'link_after' => '</span>' ) ); ?>
	</li>
	</ul>
</li>
<?php endif; ?>