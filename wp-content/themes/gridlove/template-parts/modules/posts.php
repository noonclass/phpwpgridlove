<div id="gridlove-module-<?php echo esc_attr( $m_ind ); ?>" class="gridlove-module module-type-posts">
	
    <?php echo gridlove_get_module_heading( $module ); ?>
	
	<?php $module_query = gridlove_get_module_query( $module, gridlove_module_template_is_paged() ); ?>
	
	<?php if( $module_query->have_posts() ) : ?>

		<?php $grid = gridlove_get_module_layout( $module ); ?>
		<?php $slider_class = gridlove_module_is_slider( $module ) && ( absint( $module_query->post_count) > 1 )  ? 'gridlove-slider' : ''; ?>
		
		<div class="row gridlove-posts <?php echo esc_attr( $slider_class ); ?>">
			<?php $i = 0; while( $module_query->have_posts() ) : $module_query->the_post(); ?>
				<?php if( $i == count( $grid ) ) { $i = 0; } ?>
				<?php $post_col = $grid[$i]['col']; ?>
		        <div class="<?php echo esc_attr( gridlove_get_bootstrap_columns( $post_col )); ?>">
		        	<?php include( locate_template('template-parts/layouts/content-'. $grid[$i]['layout'].'.php') ); ?>
		        </div>

			<?php $i++; endwhile; ?>
		</div>

	<?php endif; ?>

	<?php wp_reset_postdata(); ?>

</div>


<?php
	if( $pagination = gridlove_module_is_paginated( $module ) ) {
		$paged = gridlove_module_template_is_paged();
		$temp_query = $wp_query;
		$wp_query = $module_query;
		get_template_part( 'template-parts/pagination/'. $pagination );
		$wp_query = $temp_query;
	}
?>