<div class="gridlove-content gridlove-not-found">

	  <div class="box-inner-p-bigger box-single gridlove-box">

    		<?php echo gridlove_get_archive_heading( false ); ?>

			<div class="entry-content">
				<?php if(is_search()) : ?>
					<p><?php echo __gridlove( 'content_none_search' ); ?></p>
					<?php get_search_form(); ?>
				<?php else: ?>
	  				<p><?php echo __gridlove( 'content_none' ); ?></p>
	  			<?php endif; ?>
			</div>

	</div> 
		
</div>