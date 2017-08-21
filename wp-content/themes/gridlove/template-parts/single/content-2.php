<article id="post-<?php the_ID(); ?>" <?php post_class( 'gridlove-box box-vm'); ?>>

    <?php get_template_part('template-parts/single/entry-media-'. gridlove_get_post_format() ); ?>
    
    <div class="box-inner-p-bigger box-single">
        <div class="text-center">
            <?php get_template_part('template-parts/single/entry-header'); ?>
        </div>
        <?php get_template_part('template-parts/single/entry-content'); ?>
        <?php get_template_part('template-parts/single/author'); ?>
        <?php get_template_part('template-parts/single/prev-next'); ?>
    </div>
</article>
