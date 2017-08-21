<?php
	global $gridlove_h1_used;
	$logo = gridlove_get_option( 'logo' );
	$brand = !empty( $logo ) ? '<img class="gridlove-logo" src="'.esc_url( $logo ).'" alt="'.esc_attr( get_bloginfo( 'name' ) ).'" >' : get_bloginfo( 'name' );
	$brand_bg_color_class = gridlove_get_option( 'logo_bg' ) ? 'gridlove-branding-bg' : '';
	$logo_only_class = empty( $logo ) ? 'gridlove-txt-logo' : '';
?>

<div class="gridlove-site-branding <?php echo esc_attr( $brand_bg_color_class ); ?> <?php echo esc_attr( $logo_only_class ); ?>">
	<?php if ( is_front_page() && empty($gridlove_h1_used) ) : ?>
		<h1 class="site-title h1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo wp_kses_post( $brand ); ?></a></h1>
	<?php else : ?>
		<span class="site-title h1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo wp_kses_post( $brand ); ?></a></span>
	<?php endif; ?>

	<?php if ( gridlove_get_option( 'header_site_desc' ) ): ?>
		<?php get_template_part( 'template-parts/header/elements/site-desc' ); ?>
	<?php endif; ?>

</div>

<?php $gridlove_h1_used = true; ?>