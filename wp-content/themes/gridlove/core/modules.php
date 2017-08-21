<?php

/**
 * Get module defaults
 *
 * @param  string $type Module type
 * @return array Default arguments of a module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_defaults' ) ):
	function gridlove_get_module_defaults( $type = false ) {

		$defaults = array(
			'posts' => array(
				'type' => 'posts',
				'type_name' => esc_html__( 'Posts', 'gridlove'),
				'title' => '',
				'hide_title' => 0,
				'layout_type' => 'simple',
				'simple_layout' => '1',
				'combo_layout' => '1',
				'slider_layout' => '1',
				'limit' => 6,
				'cat' => array(),
				'cat_child' => 0,
				'tag' => array(),
				'manual' => array(),
				'time' => 0,
				'order' => 'date',
				'sort'	=> 'DESC',
				'format' => 0,
				'unique' => 0,
				'more_link' => 0,
				'more_text' => '',
				'more_url' => 'http://',
			),


			'text' => array(
				'type' => 'text',
				'type_name' => esc_html__( 'Text', 'gridlove'),
				'title' => '',
				'hide_title' => 0,
				'content' => '',
				'autop' => 0,
				'center' => 0,
				'style' => 'boxed'
			)
		);


		if( !empty( $type ) && array_key_exists( $type, $defaults ) ){
			return $defaults[$type];
		}

		return $defaults;
		
	}
endif;

/**
 * Get module options
 *
 * @param  string $type Module type
 * @return array Options for sepcific module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_options' ) ):
	function gridlove_get_module_options( $type = false ) {

		$options = array(
			'posts' => array(
				'simple_layouts' => gridlove_get_simple_layouts(),
				'combo_layouts' => gridlove_get_combo_layouts(),
				'slider_layouts' => gridlove_get_slider_layouts(),
				'layout_types' => array( 'simple' => esc_html__( 'Simple', 'gridlove'), 'combo' => esc_html__( 'Combo', 'gridlove'), 'slider' => esc_html__( 'Slider', 'gridlove') ,  ),
				'cats' => get_categories( array( 'hide_empty' => false, 'number' => 0 ) ),
				'time' => gridlove_get_time_diff_opts(),
				'order' => gridlove_get_post_order_opts(),
				'formats' => gridlove_get_post_format_opts(),
			),
			
			'text' => array(
			)
		);


		if( !empty( $type ) && array_key_exists( $type, $options ) ){
			return $options[$type];
		}

		return $options;
		
	}
endif;


/**
 * Get modules
 *
 * Functions parses module page template data and sets current module array
 *
 * @return array Modules data
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_modules' ) ):
	function gridlove_get_modules( ) {

		$meta = gridlove_get_page_meta( get_the_ID() );

		if(empty($meta['modules'])){
			return false;
		}

		$modules = $meta['modules'];

		if( $meta['pagination'] != 'none' ){
            
            $modules = gridlove_set_paginated_module( $modules, $meta['pagination'] );
            
        }

        return $modules;

	}
endif;
				

/**
 * Get module layout
 *
 * Functions gets current post layout for specific module
 *
 * @param array   $module Module data
 * @return array Params for current layout
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_layout' ) ):
	function gridlove_get_module_layout( $module ) {

		return gridlove_parse_layout_params( $module[ $module['layout_type'].'_layout' ] , $module['layout_type']);
	}
endif;

/**
 * Is module slider
 *
 * Check if slider is applied to module
 *
 * @param array   $module Module data
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_is_slider' ) ):
	function gridlove_module_is_slider( $module ) {

		if ( $module['type'] == 'posts' && $module['layout_type'] == 'slider' ) {
			return true;
		}

		return false;
	}
endif;



/**
 * Is module paginated
 *
 * Check if current module has a pagination
 * 
 * @param   $m_ind current module index
 * @return pagination string or false
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_is_paginated' ) ):
	function gridlove_module_is_paginated( $module ) {
		
		if( isset($module['paginated']) && !empty( $module['paginated'] ) ){
			return $module['paginated'];
		}

		return false;
	}
endif;


/**
 * Set paginated module
 *
 * Get last posts module index so we know which module we should apply pagination to
 *
 * @param array   $modules Modules data
 * @param string   $pagination Pagination type
 * @return array Modules data with paginated argument set
 * @since  1.0
 */

if ( !function_exists( 'gridlove_set_paginated_module' ) ):
	function gridlove_set_paginated_module( $modules, $pagination ) {
		
	$last_module_index = false;

	if( !empty($modules) ){

		foreach( $modules as $n => $module ){
			if($module['type'] == 'posts' && !gridlove_module_is_slider( $module ) ){
				$last_module_index = $n;
			}
		}

		if( $last_module_index !== false ){
			
			$modules[$last_module_index]['paginated'] = $pagination;

			if( gridlove_module_template_is_paged() ){
            	$modules = gridlove_parse_paged_module_template( $modules );
        	}
		}

	}

	return $modules;

	}
endif;

/**
 * Module template is paged
 *
 * Check if we are on paginated modules page
 *
 * @return int|false
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_template_is_paged' ) ):
	function gridlove_module_template_is_paged() {
		$current_page = is_front_page() ? absint( get_query_var('page') ) : absint( get_query_var('paged') );
		return $current_page > 1 ? $current_page : false;
	}
endif;


/**
 * Parse paged module template
 *
 * When we are on paginated module page
 * pull only the last posts module and its section 
 * but check queries for other modules
 *
 * @param  array $modules existing modules data
 * @return array Paginated module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_parse_paged_module_template' ) ):
	function gridlove_parse_paged_module_template( $modules ) {
			
			if( !empty($modules) ){

				foreach( $modules as $m_ind => $module ){
			
					if( gridlove_module_is_paginated( $module ) ) {
					
						$cut_modules = array( 0 => $module );
						
						return $cut_modules;
					
					} else {
					
						if( isset( $module['unique'] ) && !empty( $module['unique'] ) ){
							gridlove_get_module_query( $module );
						}
					}
				}
			}

	}
endif;




/**
 * Get module heading
 *
 * Function gets heading/title html for current module
 *
 * @param array   $module Module data
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_heading' ) ):
	function gridlove_get_module_heading( $module ) {

		$args = array();

		if ( !empty( $module['title'] ) && empty( $module['hide_title'] ) ) {

			$args['title'] = '<h2>'.$module['title'].'</h2>';
		}

		$args['actions'] = '';

		if ( gridlove_module_is_slider( $module ) ) {
			$args['actions'].= '<div class="gridlove-slider-controls" data-items="'.esc_attr(count(gridlove_get_module_layout( $module ))).'"></div>';
		}

		if ( isset($module['more_link']) && !empty($module['more_link']) && !empty( $module['more_text'] ) && !empty( $module['more_url'] ) ) {
			$args['actions'].= '<a class="gridlove-action-link" href="'.esc_url( $module['more_url'] ).'">'.$module['more_text'].'</a>';
		}

		return !empty( $args ) ? gridlove_get_heading( $args ) : '';

	}
endif;


/**
 * Get module query
 *
 * @param array   $module Module data
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_query' ) ):
	function gridlove_get_module_query( $module, $paged = false ) {
		
		global $gridlove_unique_module_posts;

		$module = wp_parse_args( $module, gridlove_get_module_defaults( $module['type']) );

		$args['ignore_sticky_posts'] = 1;

		if ( !empty( $module['manual'] ) ) {

			$args['posts_per_page'] = absint( count( $module['manual'] ) );
			$args['orderby'] =  'post__in';
			$args['post__in'] =  $module['manual'];
			$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

		} else {

			$args['post_type'] = 'post';
			$args['posts_per_page'] = absint( $module['limit'] );

			if ( !empty( $module['cat'] ) ) {

				if ( $module['cat_child'] ) {
					$child_cat_ids = array();
					foreach ( $module['cat'] as $parent ) {
						$child_cats = get_categories( array( 'child_of' => $parent ) );
						if ( !empty( $child_cats ) ) {
							foreach ( $child_cats as $child ) {
								$child_cat_ids[] = $child->term_id;
							}
						}
					}
					$module['cat'] = array_merge( $module['cat'], $child_cat_ids );
				}

				$args['category__in'] = $module['cat'];
			}

			if ( !empty( $module['tag'] ) ) {
				$args['tag_slug__in'] = $module['tag'];
			}

			if ( !empty( $module['format'] ) ) {
				
				if( $module['format'] == 'standard'){
					
					$terms = array();
					$formats = get_theme_support('post-formats');
					if(!empty($formats) && is_array($formats[0])){
						foreach($formats[0] as $format){
							$terms[] = 'post-format-'.$format;
						}
					}
					$operator = 'NOT IN';

				} else {
					$terms = array('post-format-'.$module['format']);
					$operator = 'IN';
				}
				
				$args['tax_query'] = array(
					array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms,
					'operator' => $operator
					)
				);
			}
			

			$args['orderby'] = $module['order'];
			$args['order'] = $module['sort'];

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {

				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();

			}

			if ( $time_diff = $module['time'] ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $time_diff ) ) );
			}

			if( !empty( $gridlove_unique_module_posts ) ){
				$args['post__not_in'] = $gridlove_unique_module_posts;
			}
		}

		if( $paged ){
			$args['paged'] = $paged;
		}

		$query = new WP_Query( $args );

		if ( $module['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

			foreach ( $query->posts as $p ) {
				$gridlove_unique_module_posts[] = $p->ID;
			}
		}

		return $query;

	}
endif;


/**
 * Get cover area query fro modules template
 *
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_modules_cover_query' ) ):
	function gridlove_get_modules_cover_query() {
		
			$cover = gridlove_get_page_meta( get_the_ID(), 'cover' );

			global $gridlove_unique_module_posts;

			$args['ignore_sticky_posts'] = 1;

			if ( !empty( $cover['manual'] ) ) {

				$args['orderby'] =  'post__in';
				$args['post__in'] =  $cover['manual'];
				$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

			} else {

				$args['post_type'] = 'post';
				$args['posts_per_page'] = absint( $cover['limit'] ) ;


				if ( !empty( $cover['cat'] ) ) {

					if ( $cover['cat_child'] ) {
						$child_cat_ids = array();
						foreach ( $cover['cat'] as $parent ) {
							$child_cats = get_categories( array( 'child_of' => $parent ) );
							if ( !empty( $child_cats ) ) {
								foreach ( $child_cats as $child ) {
									$child_cat_ids[] = $child->term_id;
								}
							}
						}
						$cover['cat'] = array_merge( $cover['cat'], $child_cat_ids );
					}

					$args['category__in'] = $cover['cat'];
				}

				if ( !empty( $cover['tag'] ) ) {
					$args['tag_slug__in'] = $cover['tag'];
				}

				if ( !empty( $cover['format'] ) ) {

					if ( $cover['format'] == 'standard' ) {

						$terms = array();
						$formats = get_theme_support( 'post-formats' );
						if ( !empty( $formats ) && is_array( $formats[0] ) ) {
							foreach ( $formats[0] as $format ) {
								$terms[] = 'post-format-'.$format;
							}
						}
						$operator = 'NOT IN';

					} else {
						$terms = array( 'post-format-'.$cover['format'] );
						$operator = 'IN';
					}

					$args['tax_query'] = array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => $terms,
							'operator' => $operator
						)
					);
				}

				$args['orderby'] = $cover['order'];
				$args['order'] = $cover['sort'];

				if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
					$args['orderby'] = 'meta_value_num';
					$args['meta_key'] = ev_get_meta_key();
				}

				if ( $args['orderby'] == 'title' ) {
					$args['order'] = 'ASC';
				}

				if ( $time_diff = $cover['time'] ) {
					$args['date_query'] = array( 'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $time_diff ) ) );
				}

			}


			$query = new WP_Query( $args );

			if ( $cover['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

				foreach ( $query->posts as $p ) {
					$gridlove_unique_module_posts[] = $p->ID;
				}
			}

			return $query;

	}
endif;

?>