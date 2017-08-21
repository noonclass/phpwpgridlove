<div class="gridlove-content gridlove-not-found">

    <div class="box-inner-p-bigger box-single gridlove-box">

    <?php

        $args = array(
            'title' => '<h2>'. esc_html__( 'Oooops!', 'gridlove' ).'</h2>',
        );

        echo gridlove_get_heading( $args );
    ?>

    <div class="entry-content">
        <p><?php echo wp_kses( sprintf( __( 'You don\'t have any modules yet. Hurry up and <a href="%s">create your first module</a>.', 'gridlove' ), admin_url( 'post.php?post='.get_the_ID().'&action=edit#gridlove_modules' ) ), wp_kses_allowed_html( 'post' )) ?></p>
    </div>

    </div> 

</div>