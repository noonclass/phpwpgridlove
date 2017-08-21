<div class="gridlove-header-top">
	<div class="container">

		<?php if( $top_l = gridlove_get_option('header_top_l')): ?>
			<div class="gridlove-slot-l">
				<?php get_template_part( 'template-parts/header/elements/'. $top_l ); ?>  
			</div>
		<?php endif; ?>

		<?php if( $top_c = gridlove_get_option('header_top_c')): ?>
			<div class="gridlove-slot-c">
				<?php get_template_part( 'template-parts/header/elements/'. $top_c ); ?> 
			</div>
		<?php endif; ?>

		<?php if( $top_r = gridlove_get_option('header_top_r')): ?>
			<div class="gridlove-slot-r">
				<?php get_template_part( 'template-parts/header/elements/'. $top_r ); ?> 
			</div>
		<?php endif; ?>
	</div>				
</div>