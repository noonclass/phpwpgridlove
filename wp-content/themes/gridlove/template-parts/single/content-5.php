<article id="post-<?php the_ID(); ?>" <?php post_class( 'gridlove-box box-vm'); ?>>
<div class="box-single">
    
    <div class="entry-overlay-wrapper">
	    
        <?php get_template_part('template-parts/single/entry-media-standard' ); ?>

		<div class="entry-overlay box-inner-p-bigger">
	        <?php get_template_part('template-parts/single/entry-header'); ?>
	    </div>
    </div>
    
    <div class="box-inner-p-bigger">
        <?php get_template_part('template-parts/single/entry-content'); ?>
        <?php get_template_part('template-parts/single/author'); ?>
            <?php get_template_part('template-parts/single/prev-next'); ?>
    </div>
</div>
</article>
