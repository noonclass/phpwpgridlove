<?php 

/* 
	This is Gridlove Child Theme functions file
	You can use it to modify specific features and styling of Gridlove Theme
*/	

add_action( 'after_setup_theme', 'gridlove_child_theme_setup', 99 );

function gridlove_child_theme_setup(){
	add_action('wp_enqueue_scripts', 'gridlove_child_load_scripts');
}

function gridlove_child_load_scripts() {	
	wp_register_style('gridlove_child_load_scripts', trailingslashit(get_stylesheet_directory_uri()).'style.css', false, GRIDLOVE_THEME_VERSION, 'screen');
	wp_enqueue_style('gridlove_child_load_scripts');
}


?>