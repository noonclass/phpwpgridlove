<?php $more_link = get_next_posts_link( __gridlove( 'load_more' ) ); ?>

<?php if ( !empty( $more_link ) ) : ?>

<nav class="gridlove-pagination gridlove-infinite-scroll">
    <?php echo wp_kses_post( $more_link ); ?>
    <div class="gridlove-loader">
	  <div class="double-bounce1"></div>
	  <div class="double-bounce2"></div>
    </div>
</nav>

<?php endif; ?>