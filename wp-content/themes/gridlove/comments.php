<?php if ( post_password_required() ) { return; } ?>

<?php if ( comments_open() || get_comments_number() ) : ?>

	<div id="comments" class="gridlove-comments gridlove-box box-inner-p-bigger">

		<?php
			ob_start();
			comments_number( __gridlove( 'no_comments' ), __gridlove( 'one_comment' ), __gridlove( 'multiple_comments' ) );
			$comments_title = ob_get_contents();
			ob_end_clean();

			//echo '<h4 class="h2">'.$comments_title.'</h4>';
/*
            // 原主题导航，为了ias效果去掉了，使用下面的gridlove_get_comment_nav()
			echo gridlove_get_heading(
				array(
					'title' => '<h4 class="h2">'.$comments_title.'</h4>',
					'actions' => get_comment_pages_count() > 1 && get_option( 'page_comments' ) ? paginate_comments_links( array( 'echo' => false, 'prev_text' => '<i class="fa fa-chevron-left"></i>', 'next_text' => '<i class="fa fa-chevron-right"></i>', 'type' => 'list'  ) ) : ''
				)
			);
*/
			echo '<div class="module-header"><div class="module-title"><h4 class="h2">'.$comments_title.'</h4><div class="module-actions"><a class="respond-switch" href="javascript:void(0);">add a comment</a></div></div></div>';

			comment_form(
				array(
					'title_reply' => '',
					'label_submit' => __gridlove( 'comment_submit' ),
					'comment_notes_before' => '',
					'comment_notes_after' => '',
				)
			);
		?>

		<?php if ( have_comments() ) : ?>

			<ul class="comment-list">
			<?php $args = array(
				'avatar_size' => 50,
				'reply_text' => __gridlove( 'comment_reply' )
			); ?>
				<?php wp_list_comments( $args ); ?>

				<?php echo gridlove_get_comment_nav(); ?>

			</ul>
		<?php endif; ?>

	
	</div>

<?php endif; ?>