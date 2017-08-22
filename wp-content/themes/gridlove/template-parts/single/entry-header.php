<div class="entry-header">
	<div class="entry-category">
	    <?php if( $icon = gridlove_get_option('single_icon') ): ?>
	        <?php echo gridlove_get_format_icon(); ?>
	    <?php endif; ?>

	    <?php if( gridlove_get_option('single_cat') ) : ?>
	        <?php echo gridlove_get_category(); ?>
	    <?php endif; ?>
	</div>

    <?php //gridlove_the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    <?php if( $meta = gridlove_get_meta_data('single') ) : ?>
    	<div class="entry-meta"><?php echo $meta; ?></div>
    <?php endif; ?>

</div>