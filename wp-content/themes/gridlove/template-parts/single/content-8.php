<article id="post-<?php the_ID(); ?>" <?php post_class( 'gridlove-box box-vm gridlove-box-nm'); ?>>
	<div class="box-inner-p-bigger box-single gridlove-to-center">
		<?php get_template_part('template-parts/single/entry-header'); ?>
	    <?php get_template_part('template-parts/single/entry-content'); ?>
	    <?php get_template_part('template-parts/single/author'); ?>
	    <?php get_template_part('template-parts/single/prev-next'); ?>
	</div>
</article>