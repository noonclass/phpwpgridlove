<?php

/**
 * Hide update notification and update theme version
 *
 * @since  1.0
 */

add_action('wp_ajax_gridlove_update_version', 'gridlove_update_version');

if(!function_exists('gridlove_update_version')):
function gridlove_update_version(){
	update_option('gridlove_theme_version', GRIDLOVE_THEME_VERSION);
	die();
}
endif;


/**
 * Hide welcome notification
 *
 * @since  1.0
 */

add_action('wp_ajax_gridlove_hide_welcome', 'gridlove_hide_welcome');

if(!function_exists('gridlove_hide_welcome')):
function gridlove_hide_welcome(){
	update_option('gridlove_welcome_box_displayed', true);
	die();
}
endif;


?>