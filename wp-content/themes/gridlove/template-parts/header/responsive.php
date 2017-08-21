<div id="gridlove-header-responsive" class="gridlove-header-responsive hidden-lg-up">

	<div class="container">
		<?php $logo = gridlove_get_option('logo_mini') ? 'logo-mini' : 'logo'; ?>

		<?php get_template_part('template-parts/header/elements/'. $logo ); ?>

		<?php get_template_part('template-parts/header/elements/mobile-actions'); ?>

	</div>

</div>

