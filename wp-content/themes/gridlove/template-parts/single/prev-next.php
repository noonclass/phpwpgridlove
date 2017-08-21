<?php if( gridlove_get_option( 'single_prev_next') ) : ?>

	<?php $prev_next = gridlove_get_prev_next_posts(); ?>

	<?php if( !empty($prev_next['prev']) || !empty( $prev_next['next']) ) : ?>

		<nav class="gridlove-prev-next-nav">
				<div class="gridlove-prev-link">
				<?php if ( !empty($prev_next['prev']) ): ?>

					
						<a href="<?php echo esc_url( get_permalink( $prev_next['prev'] ) );?>">
							<span class="gridlove-pn-ico"><i class="fa fa fa-chevron-left"></i></span>
							<span class="gridlove-pn-link"><?php echo get_the_title( $prev_next['prev'] );?></span>
						</a>

					

				<?php endif; ?>
				</div>
				<div class="gridlove-next-link">
				<?php if ( !empty( $prev_next['next']) ): ?>

					
						<a href="<?php echo esc_url( get_permalink( $prev_next['next'] ) ); ?>">
							<span class="gridlove-pn-ico"><i class="fa fa fa-chevron-right"></i></span>
							<span class="gridlove-pn-link"><?php echo get_the_title( $prev_next['next'] );?></span>
						</a>
					

				<?php endif; ?>
				</div>

		</nav>

	<?php endif; ?>

<?php endif; ?>