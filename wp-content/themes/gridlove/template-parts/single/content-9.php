<article id="post-<?php the_ID(); ?>" <?php post_class( 'gridlove-box box-vm'); ?>>
    <div class="box-inner-p-bigger box-single">
        <?php get_template_part('template-parts/single/entry-content'); ?>
        <?php get_template_part('template-parts/single/author'); ?>
        <?php get_template_part('template-parts/single/prev-next'); ?>
    </div>
</article>
