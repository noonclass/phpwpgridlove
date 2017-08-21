<?php

/**
 * Wrapper function for __()
 *
 * It checks if specific text is translated via options panel
 * If option is set, it returns translated text from theme options
 * If option is not set, it returns default translation string (from language file)
 *
 * @param string  $string_key Key name (id) of translation option
 * @return string Returns translated string
 * @since  1.0
 */

if ( !function_exists( '__gridlove' ) ):
	function __gridlove( $string_key ) {
		if ( ( $translated_string = gridlove_get_option( 'tr_'.$string_key ) ) && gridlove_get_option( 'enable_translate' ) ) {

			if ( $translated_string == '-1' ) {
				return '';
			}

			return wp_kses_post( $translated_string );

		} else {

			$translate = gridlove_get_translate_options();
			return wp_kses_post( $translate[$string_key]['text'] );
		} 
	}
endif;



/**
 * Get featured image
 *
 * Function gets featured image depending on the size and post id.
 * If image is not set, it gets the default featured image placehloder from theme options.
 *
 * @param string  $size               Image size ID
 * @param bool    $ignore_default_img Wheter to apply default featured image if post doesn't have featured image
 * @param bool    $ignore_size_prefix Wheter to pass exact size or apply theme prefix
 * @return string Image HTML output
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_featured_image' ) ):
	function gridlove_get_featured_image( $size = 'large', $ignore_default_img = false, $ignore_size_prefix = false ) {
		
		$post_id = get_the_ID();

		if( !$ignore_size_prefix ) {
			$size = 'gridlove-'.$size;
		}

		if ( has_post_thumbnail( $post_id ) ) {

			return get_the_post_thumbnail( $post_id, $size );

		} else if ( !$ignore_default_img && ( $placeholder = gridlove_get_option( 'default_fimg' ) ) ) {

				//If there is no featured image, try to get default from theme options

				global $placeholder_img, $placeholder_imgs;

				if ( empty( $placeholder_img ) ) {
					$img_id = gridlove_get_image_id_by_url( $placeholder );
				} else {
					$img_id = $placeholder_img;
				}

				if ( !empty( $img_id ) ) {
					if ( !isset( $placeholder_imgs[$size] ) ) {
						$def_img = wp_get_attachment_image( $img_id, $size );
					} else {
						$def_img = $placeholder_imgs[$size];
					}

					if ( !empty( $def_img ) ) {
						$placeholder_imgs[$size] = $def_img;
						return wp_kses_post( $def_img );
					}
				}

				return wp_kses_post('<img src="'.esc_attr( $placeholder ).'" alt="'.esc_attr( get_the_title( $post_id ) ).'" />');
			}

		return false;
	}
endif;


/**
 * Get post categories
 *
 * Function gets categories for current post and displays and slightly modifies
 * HTML output of category list so we can have category id in class parameter
 *
 * @return string HTML output of category links
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_category' ) ):
	function gridlove_get_category() {

		$output = '';
		$cats = get_the_category();
		if ( !empty( $cats ) ) {
			foreach ( $cats as $k => $cat ) {
				$output.= '<a href="'.esc_url( get_category_link( $cat->term_id ) ).'" class="gridlove-cat gridlove-cat-'.$cat->term_id.'">'.$cat->name.'</a>';
			}
		}

		return wp_kses_post( $output );
	}
endif;


/**
 * Get meta data
 *
 * Function outputs meta data HTML based on theme options for specific layout
 *
 * @param string  $layout     Layout ID
 * @param array   $force_meta Force specific meta instead of using options
 * @return string HTML output of meta data
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_meta_data' ) ):
	function gridlove_get_meta_data( $layout = 'a', $force_meta = false ) {

		$meta_data = $force_meta !== false ? $force_meta : array_keys( array_filter( gridlove_get_option( 'lay_'.$layout .'_meta' ) ) );

		$output = '';

		if ( !empty( $meta_data ) ) {

			foreach ( $meta_data as $mkey ) {


				$meta = '';

				switch ( $mkey ) {

				case 'date':
					$meta = '<span class="updated">'.get_the_date().'</span>';
					break;

				case 'author':
					$author_id = get_post_field( 'post_author', get_the_ID() );

					$meta = '<span class="vcard author"><span class="fn"><a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ).'">'.get_avatar( get_the_author_meta('ID'), 24).' '.get_the_author_meta( 'display_name', $author_id ).'</a></span></span>';
					break;

				case 'views':
					global $wp_locale;
					$thousands_sep = isset( $wp_locale->number_format['thousands_sep'] ) ? $wp_locale->number_format['thousands_sep'] : ',';
					if ( strlen( $thousands_sep ) > 1 ) {
						$thousands_sep = trim( $thousands_sep );
					}
					$meta = function_exists( 'ev_get_post_view_count' ) ?  number_format_i18n( absint( str_replace( $thousands_sep, '', ev_get_post_view_count( get_the_ID() ) ) + gridlove_get_option( 'views_forgery' ) ) )  . ' '.__gridlove( 'views' ) : '';
					break;

				case 'rtime':
					$meta = gridlove_read_time( get_post_field( 'post_content', get_the_ID() ) );
					if ( !empty( $meta ) ) {
						$meta .= ' '.__gridlove( 'min_read' );
					}
					break;

				case 'comments':
					if ( comments_open() || get_comments_number() ) {
						ob_start();
						comments_popup_link( __gridlove( 'no_comments' ), __gridlove( 'one_comment' ), __gridlove( 'multiple_comments' ) );
						$meta = ob_get_contents();
						ob_end_clean();
					} else {
						$meta = '';
					}
					break;

				default:
					break;
				}

				if ( !empty( $meta ) ) {
					$output .= '<div class="meta-item meta-'.$mkey.'">'.$meta.'</div>';
				}
			}
		}


		return wp_kses_post( $output );

	}
endif;




/**
 * Get post excerpt
 *
 * Function outputs post excerpt for specific layout
 *
 * @param limit Number of characters to limit excerpt
 * @return string HTML output of category links
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_excerpt' ) ):
	function gridlove_get_excerpt( $limit = 250 ) {

		$manual_excerpt = false;

		if ( has_excerpt() ) {
			$content =  get_the_excerpt();
			$manual_excerpt = true;
		} else {
			$text = get_the_content( '' );
			$text = strip_shortcodes( $text );
			$text = apply_filters( 'the_content', $text );
			$content = str_replace( ']]>', ']]&gt;', $text );
		}

		if ( !empty( $content ) ) {
			if ( !empty( $limit ) || !$manual_excerpt ) {
				$more = gridlove_get_option( 'more_string' );
				$content = wp_strip_all_tags( $content );
				$content = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $content );
				$content = gridlove_trim_chars( $content, $limit, $more );
			}
			return wp_kses_post( wpautop( $content ) );
		}

		return '';

	}
endif;


/**
 * Module/archive heading
 *
 * Function that outputs the heading of a module based on passed arguments
 *
 * @param array   $args title => heading title, desc => heading description,  actions => action links
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_heading' ) ):
	function gridlove_get_heading( $args ) {

		$defaults = array(
			'title' => '',
			'desc' => '',
			'actions' => ''
		);

		$args = gridlove_parse_args( $args, $defaults );

		$output = '';

		if ( !empty( $args['title'] ) ||  !empty( $args['actions'] ) ) {
			
			$output.= '<div class="module-title">';
			
			if ( !empty( $args['title'] ) ) {
				$output.= $args['title'];
			}

			if ( !empty( $args['actions'] ) ) {
				$output.= '<div class="module-actions">'.$args['actions'].'</div>';
			}

			$output.= '</div>';

		}

		if ( !empty( $args['desc'] ) ) {
			$output.= '<div class="module-desc">'.$args['desc'].'</div>';
		}

		if ( !empty( $output ) ) {
			$output = '<div class="module-header">'.$output.'</div>';
		}

		return $output;
	}
endif;


/**
 * Get archive heading
 *
 * Function gets title and description for current template
 *
 * @param  $skip_empty Wheter to skip returning heading if archive is empty (has no posts)
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_archive_heading' ) ):
	function gridlove_get_archive_heading( $skip_empty = true ) {
		
		global $wp_query; 
         		
 		if( $skip_empty && !$wp_query->found_posts ){

 			return '';
 		}

		$title = '';
		$desc = '';
		$actions = '';
		$args = array();

		if ( is_category() ) {
			$obj = get_queried_object();

			$title = __gridlove( 'category' ).single_cat_title( '', false );
			$desc = category_description();

		} else if ( is_author() ) {
				$obj = get_queried_object();
				$title = __gridlove( 'author' ).$obj->display_name;

			} else if ( is_tax() ) {
				$title = single_term_title( '', false );
			} else if ( is_home() && ( $posts_page = get_option( 'page_for_posts' ) ) && !is_page_template( 'template-modules.php' ) ) {
				$title = get_the_title( $posts_page );
			} else if ( is_search() ) {
				
	 			$title = __gridlove( 'search_results_for' ).get_search_query();
				$actions = get_search_form( false );
				
			} else if ( is_tag() ) {
				$title = __gridlove( 'tag' ).single_tag_title( '', false );
				$desc = tag_description();
			} else if ( is_day() ) {
				$title = __gridlove( 'archive' ).get_the_date();
			} else if ( is_month() ) {
				$title = __gridlove( 'archive' ).get_the_date( 'F Y' );
			} else if ( is_year() ) {
				$title = __gridlove( 'archive' ).get_the_date( 'Y' );
			} else {
			$title = '';
			$desc = '';
		}

		if ( !empty( $title ) ) {
			$args['title'] = '<h1 class="h2">'.$title.'</h1>';
		}

		if ( !empty( $desc ) &&  $skip_empty) {
			$args['desc'] = wpautop( $desc );
		}

		if ( !empty( $actions ) && $skip_empty) {
			$args['actions'] = $actions;
		}

		return gridlove_get_heading( $args );
	}
endif;


/**
 * Get current post layout for archives
 *
 * It checks which posts layout to display based on current template options
 *
  * @return array Params for current layout
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_archive_layout' ) ):
	function gridlove_get_archive_layout() {

		$template = gridlove_detect_template();
		$combo = false;

		if( $template == 'category' ){

			$cat_id = get_queried_object_id();
			$meta = gridlove_get_category_meta( $cat_id, 'layout' );

			if(isset($meta['type']) && $meta['type'] != 'inherit'){
				$layout = $meta['main'];
				$combo = $meta['combo'] != 'none' ? $meta['combo'] : false;

			} else {
				$layout = gridlove_get_option( $template.'_layout' );
				if(gridlove_get_option('category_combo')){
					$combo = gridlove_get_option('category_combo_layout');
				}
			}
			
		} else {

			$layout = gridlove_get_option( $template.'_layout' );
		
		}
		

		if(!$layout){
			$layout = 1;
		}

		$params = gridlove_parse_layout_params( $layout, 'simple' );

		//Apply combo layout if exists
		if( !is_paged() && $combo ){
			$add_params = gridlove_parse_layout_params( $combo, 'combo' );
			$params = array_merge( $add_params, $params );
			$params[count($add_params)]['base'] = 1;
		}

		return $params;
	}
endif;

/**
 * Get post format icon
 *
 * Checks format of current post and returns its icon
 *
 * @param string  $size Icon size class
 * @return string Icon HTML output
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_format_icon' ) ):
	function gridlove_get_format_icon() {

		$format = gridlove_get_post_format();

		$icons = array(
			'video' => 'fa-video-camera',
			'audio' => 'fa-music',
			'gallery' => 'fa-camera',
            'image' => 'fa-image' 
		);

		//Allow plugins or child themes to modify icons
		$icons = apply_filters( 'gridlove_modify_post_format_icons', $icons );

		if ( $format && array_key_exists( $format, $icons ) ) {

			return wp_kses_post( '<span class="gridlove-format-icon"><i class="fa '.esc_attr( $icons[$format] ).'"></i></span>' );
		}

		return '';
	}
endif;


/**
 * Get social share
 *
 * Checks social share options to generate sharing buttons
 *
 * @return array List of HTML sharing links
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_social_share' ) ):
	function gridlove_get_social_share() {

		$share_options = array_keys( array_filter( gridlove_get_option( 'social_share' ) ) );

		if ( empty( $share_options ) ) {
			return false;
		}

		$share = array();
		$share['facebook'] = '<a href="javascript:void(0);" class="gridlove-facebook gridlove-share-item" data-url="http://www.facebook.com/sharer/sharer.php?u='.urlencode( esc_url( get_permalink() ) ).'&amp;t='.urlencode( esc_attr( get_the_title() ) ).'"><i class="fa fa-facebook"></i></a>';
		$share['twitter'] = '<a href="javascript:void(0);" class="gridlove-twitter gridlove-share-item" data-url="http://twitter.com/intent/tweet?url='.urlencode(esc_url( get_permalink() ) ).'&amp;text='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-twitter"></i></a>';
		$share['gplus'] = '<a href="javascript:void(0);"  class="gridlove-gplus gridlove-share-item" data-url="https://plus.google.com/share?url='.urlencode(esc_url( get_permalink() )).'"><i class="fa fa-google-plus"></i></a>';
		$pin_img = has_post_thumbnail() ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) : '';
		$pin_img = isset( $pin_img[0] ) ? $pin_img[0] : '';
		$share['pinterest'] = '<a href="javascript:void(0);"  class="gridlove-pinterest gridlove-share-item" data-url="http://pinterest.com/pin/create/button/?url='.urlencode(esc_url( get_permalink() )).'&amp;media='.urlencode(esc_attr( $pin_img )).'&amp;description='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-pinterest-p"></i></a>';
		$share['linkedin'] = '<a href="javascript:void(0);"  class="gridlove-linkedin gridlove-share-item" data-url="http://www.linkedin.com/shareArticle?mini=true&amp;url='.esc_url( get_permalink() ).'&amp;title='.esc_attr( get_the_title() ).'"><i class="fa fa-linkedin"></i></a>';
		$share['reddit'] = '<a href="javascript:void(0);"  class="gridlove-reddit gridlove-share-item" data-url="http://www.reddit.com/submit?url='.urlencode(esc_url( get_permalink() )).'&amp;title='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-reddit-alien"></i></a>';
		$share['email'] = '<a href="mailto:?subject='.urlencode(esc_attr( get_the_title() )).'&amp;body='.urlencode(esc_url( get_permalink() )).'" class="gridlove-mailto"><i class="fa fa-envelope-o"></i></a>';
		$share['stumbleupon'] = '<a href="javascript:void(0);"  class="gridlove-stumbleupon gridlove-share-item" data-url="http://www.stumbleupon.com/badge?url='.urlencode(esc_url( get_permalink() )).'&amp;title='.urlencode(esc_attr( get_the_title() )).'"><i class="fa fa-stumbleupon"></i></a>';


		$output = array();
		foreach ( $share_options as $social ) {
			$output[] = $share[$social];
		}

		return $output;


	}
endif;


/**
 * Numeric pagination
 *
 * @param string  $prev Previous link text
 * @param string  $next Next link text
 * @return string Pagination HTML output or empty string
 * @since  1.0
 */

if ( !function_exists( 'gridlove_numeric_pagination' ) ):
	function gridlove_numeric_pagination( $prev = '&lsaquo;', $next = '&rsaquo;' ) {
		global $wp_query, $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
		$pagination = array(
			'base' => @add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => $wp_query->max_num_pages,
			'current' => $current,
			'prev_text' => $prev,
			'next_text' => $next,
			'type' => 'plain'
		);
		if ( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

		if ( !empty( $wp_query->query_vars['s'] ) )
			$pagination['add_args'] = array( 's' => str_replace( ' ', '+', get_query_var( 's' ) ) );

		$links = paginate_links( $pagination );

		return empty( $links ) ? '' : $links;
	}
endif;



/**
 * Get single post layout
 *
 * @return array IDs of cover layout and content layout
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_single_layout' ) ):
	function gridlove_get_single_layout() {

		$layout = gridlove_get_post_meta( get_the_ID(), 'layout' );
	
		if ( $layout == 'inherit' ) {
			$layout = gridlove_get_option( 'single_layout' );
		}

		$temp = explode('_', $layout);

		return array( 'content' => $temp[0], 'cover' => $temp[1] );
	}
endif;



/**
 * Get page layout
 *
 * @return array IDs of cover layout and content layout
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_page_layout' ) ):
	function gridlove_get_page_layout() {

		$layout = gridlove_get_page_meta( get_the_ID(), 'layout' );
		
		if ( $layout == 'inherit' ) {
			$layout = gridlove_get_option( 'page_layout' );
		}

		$temp = explode('_', $layout);

		return array( 'content' => $temp[0], 'cover' => $temp[1] );
	}
endif;



/**
 * Get author social links
 *
 * @param int     $author_id ID of an author/user
 * @return string HTML output of social links
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_author_links' ) ):
	function gridlove_get_author_links( $author_id ) {

		$output = '';

		if ( is_single() ) {

			$output .= '<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ).'" class="gridlove-pill pill-large">'.__gridlove( 'view_all' ).'</a>';
		}


		if ( $url = get_the_author_meta( 'url', $author_id ) ) {
			$output .= '<a href="'.esc_url( $url ).'" target="_blank" class="gridlove-sl-item fa fa-link"></a>';
		}

		$social = gridlove_get_social();

		if ( !empty( $social ) ) {
			foreach ( $social as $id => $name ) {
				if ( $social_url = get_the_author_meta( $id,  $author_id ) ) {

					if ( $id == 'twitter' ) {
						$pos = strpos( $social_url, '@' );
						$social_url = 'https://twitter.com/'.substr( $social_url, $pos, strlen( $social_url ) );
					}

					$output .=  '<a href="'.esc_url( $social_url ).'" target="_blank" class="gridlove-sl-item fa fa-'.$id.'"></a>';
				}
			}
		}

		return wp_kses_post( $output );
	}
endif;


/**
 * Get current sidebar
 *
 * It checks which sidebar to display based on current template options
 *
 * @return array Sidebar position and values
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_current_sidebar' ) ):
	function gridlove_get_current_sidebar() {
		
		/* Default */
		$position = 'none';
		$standard = 'gridlove_default_sidebar';
		$sticky = 'gridlove_default_sticky_sidebar';

		$gridlove_template = gridlove_detect_template();

		if ( $gridlove_template == 'single' ) {

			$sidebar = gridlove_get_post_meta( get_the_ID(), 'sidebar' );
			$position = ( $sidebar['position'] == 'inherit' ) ? gridlove_get_option( $gridlove_template.'_sidebar_position' ) : $sidebar['position'];
			if ( $position != 'none' ) {
				$standard = ( $sidebar['standard'] == 'inherit' ) ?  gridlove_get_option( $gridlove_template.'_sidebar_standard' ) : $sidebar['standard'];
				$sticky = ( $sidebar['sticky'] == 'inherit' ) ?  gridlove_get_option( $gridlove_template.'_sidebar_sticky' ) : $sidebar['sticky'];
			}

		} else if ($gridlove_template == 'page' ) {
				
			$sidebar = gridlove_get_post_meta( get_the_ID(), 'sidebar' );
			$position = ( $sidebar['position'] == 'inherit' ) ? gridlove_get_option( $gridlove_template.'_sidebar_position' ) : $sidebar['position'];
			if ( $position != 'none' ) {
				$standard = ( $sidebar['standard'] == 'inherit' ) ?  gridlove_get_option( $gridlove_template.'_sidebar_standard' ) : $sidebar['standard'];
				$sticky = ( $sidebar['sticky'] == 'inherit' ) ?  gridlove_get_option( $gridlove_template.'_sidebar_sticky' ) : $sidebar['sticky'];
			}
		}

		$output = array(
			'position' => $position,
			'standard' => $standard,
			'sticky' => $sticky
		);

		return $output;

	}
endif;


/**
 * Get current pagination
 *
 * It checks which pagination type to display based on current template options
 *
 * @return string|bool Pagination layout or false if there is no pagination
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_current_pagination' ) ):
	function gridlove_get_current_pagination() {

		global $wp_query;

		if ( $wp_query->max_num_pages <= 1 ) {
			return false;
		}

		$layout = 'numeric'; //layout numeric as default

		$template = gridlove_detect_template();

		if( $template == 'category'){

			$cat_id = get_queried_object_id();
			$meta = gridlove_get_category_meta( $cat_id, 'layout' );

			if( isset($meta['type']) && $meta['type'] != 'inherit'){
				$layout = $meta['pagination'];
			} else {
				$layout = gridlove_get_option( $template.'_pag' );
			}
		
		} else {
			
			if ( in_array( $template, array( 'search', 'tag', 'author', 'archive' ) ) ) {

				$layout = gridlove_get_option( $template.'_pag' );

			}
		}


		return $layout;
	}
endif;


/**
 * Get bootstrap column classes based on a current layout
 *
 * @param $post_lg_col value of lg col class
 * @return string Set of bootsrap column classes
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_bootstrap_columns' ) ):
	function gridlove_get_bootstrap_columns( $post_lg_col) {

		$classes = array();
		$classes['lg'] = 'col-lg-'.$post_lg_col;
		$classes['md'] = $post_lg_col > 5 ? 'col-md-12' : 'col-md-6';
		$classes['sm'] = 'col-sm-12';

		return implode(' ', $classes);
	}
endif;



?>