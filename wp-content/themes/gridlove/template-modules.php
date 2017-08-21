<?php
/**
 * Template Name: Modules
 */
?>

<?php get_header(); ?>

<?php if( $cover = gridlove_get_cover_layout() ) : ?>
    <?php get_template_part( 'template-parts/cover/layout-' . absint( $cover ) ); ?>
<?php endif; ?>

<?php get_template_part('template-parts/ads/below-header'); ?>

<div id="content" class="gridlove-site-content container">

    <?php $modules = gridlove_get_modules(); ?>

    <?php if( !empty( $modules ) ): ?>

        <?php foreach( $modules as $m_ind => $module ) : $module = gridlove_parse_args( $module, gridlove_get_module_defaults( $module['type'] ) ); ?>
                
               <?php include( locate_template('template-parts/modules/'.$module['type'].'.php') ); ?>

        <?php endforeach; ?>

    <?php else: ?>
    	
    	<?php include( locate_template('template-parts/modules/empty.php') ); ?>

    <?php endif; ?>

</div>

<?php get_footer(); ?>