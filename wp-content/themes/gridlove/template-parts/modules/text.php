<div id="gridlove-module-<?php echo esc_attr( $m_ind ); ?>" class="gridlove-module module-type-text">
	
	<?php echo gridlove_get_module_heading( $module ); ?>


	<?php if(!empty($module['content'])) : ?>
		<?php $style_class = array(); ?>
		<?php $style_class[] = $module['style'] == 'boxed' ? 'gridlove-box' : ''; ?>
		<?php $style_class[] = $module['center'] ? 'text-center' : ''; ?>
		<div class="gridlove-text-module-content <?php echo esc_attr( implode(' ', $style_class ) ); ?>">
			<?php $module['content'] = !empty($module['autop']) ?  wpautop($module['content']) : $module['content']; ?>
			<?php echo do_shortcode( $module['content']); ?>
		</div>
	<?php endif; ?>

</div>