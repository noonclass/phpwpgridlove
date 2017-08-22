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
    //Check if is minified option active and load appropriate files
	if ( gridlove_get_option( 'minify_css' ) ) {
		wp_enqueue_style( 'gridlove-child', trailingslashit(get_stylesheet_directory_uri()).'assets/css/min.css', false, GRIDLOVE_THEME_VERSION );
        
    }else{
        wp_register_style('gridlove_child_load_scripts', trailingslashit(get_stylesheet_directory_uri()).'style.css', false, GRIDLOVE_THEME_VERSION, 'screen');
        wp_enqueue_style('gridlove_child_load_scripts');
        
        wp_register_style('jquery-mCustomScrollbar', trailingslashit(get_stylesheet_directory_uri()).'assets/css/jquery.mCustomScrollbar.min.css', false);
        wp_enqueue_style( 'jquery-mCustomScrollbar');
    }
    
    // load jquery 3.x, not 1.x in wordpress by default
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', trailingslashit(get_stylesheet_directory_uri()).'assets/js/jquery.min.js' , false, '3.2.1', false );
    wp_enqueue_script( 'jquery' );
    
    //Check if is minified option active and load appropriate files
	if ( gridlove_get_option( 'minify_js' ) ) {
        
		wp_enqueue_script( 'gridlove-child', trailingslashit(get_stylesheet_directory_uri()).'assets/js/min.js', array( 'jquery' ), GRIDLOVE_THEME_VERSION, true );
        
	}else{
    	gridlove_ality_scripts();
    }
    
    wp_localize_script( 'gridlove-child', 'child_js_settings', gridlove_ality_settings() );
}


/*
Add by moemob.com
*/
/* Add js/css - 加载功能性脚本
/* ------------------------------------- */
if ( !function_exists( 'gridlove_ality_scripts' ) ):
    function gridlove_ality_scripts() {
        // infinite-ajax-scroll, short for ias
        wp_register_script( 'jquery-ias', trailingslashit(get_stylesheet_directory_uri()).'assets/js/jquery-ias.js' , array('jquery'), '2.2.3', true );
        wp_enqueue_script( 'jquery-ias' );
        
        wp_register_script( 'jquery-mCustomScrollbar', trailingslashit(get_stylesheet_directory_uri()).'assets/js/jquery.mCustomScrollbar.concat.min.js' , array('jquery'), '3.1.5', true );
        wp_enqueue_script( 'jquery-mCustomScrollbar' );
        
        wp_register_script( 'gridlove-child', trailingslashit(get_stylesheet_directory_uri()).'assets/js/Profile.js' , array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'gridlove-child' );
        
        if( is_single()) {
            // NOTE:: 不支持wordpress原生的jquery.min.js?ver=1.12.4
            wp_register_script('jquery-comment', trailingslashit(get_stylesheet_directory_uri()).'assets/js/Comment.js', array('jquery'), '1.1.0', true);
            wp_enqueue_script('jquery-comment');
        }
    }
endif;

if ( !function_exists( 'gridlove_ality_settings' ) ):
	function gridlove_ality_settings() {
		$js_settings = array();
        
        // comment submit
		$js_settings['ajax_url'] = admin_url('admin-ajax.php');
        $js_settings['comment_form'] = 'top'; //默认为top，如果你的表单在底部则设置为bottom。
        $js_settings['comment_order'] = get_option('comment_order');
        
        // ias
        if ( (is_home() || is_front_page() || is_category() || is_tag() || is_author() || is_search() || is_archive()) && !is_paged() ){// 是否为列表页(Posts)
            $js_settings['current'] = 'index';
        }
        if ( is_single() ){// 是否为内容页(Post)
            $js_settings['current'] = 'single';
        }

		return $js_settings;
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

        } else if ( has_excerpt( $post_id ) ) {
            
            $excerpt =  get_the_excerpt( $post_id );
			$excerpt = json_decode($excerpt);
            $url = $excerpt->save_domain.'/'.$excerpt->owner_id.'/'.$excerpt->save_name;
            return wp_kses_post('<img src="'.esc_attr( $url ).'" alt="'.esc_attr( get_the_title( $post_id ) ).'" />');
        
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
 * Get title with <br /> instead of \n
 *
 * Function outputs title HTML
 *
 * @return string HTML output of title
 * @since  1.0
 */

if ( !function_exists( 'gridlove_the_title' ) ):
	function gridlove_the_title( $before = '', $after = '', $echo = true ) {       
        $title = get_the_title();
        /* NOTE::所有的转移只存在双引号中，单引号在php中只做字符处理 */
        //$title = str_replace('\n', '<br />', $title);//ERR
        /* NOTE::也可以使用nl2br()/nl2p()函数进行转换 */
        $title = str_replace("\n", "<br />", $title);
        $title = $before . $title . $after;

        if ( $echo ) {
            echo wp_kses_post( $title );
        }
        
        return wp_kses_post( $title );
	}
endif;

/**
 * Get author meta data
 *
 * Function outputs meta data HTML
 *
 * @return string HTML output of meta data
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_author_meta_data' ) ):
	function gridlove_get_author_meta_data( $layout = 'a' ) {
        $output = '';
        
        $author_id = get_post_field( 'post_author', get_the_ID() );
    
        $avatar = '<img class="avatar avatar-24 photo" alt="" src="'.get_the_author_meta( 'si_avatar', $author_id ).'" width="24" height="24">';
        $meta = '<span class="vcard author"><a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ).'">'.$avatar.' <span class="fn">'.get_the_author_meta( 'display_name', $author_id ).'</span></a></span>';
        
        if ( !empty( $meta ) ) {
            $output = '<div class="meta-item meta-author">'.$meta.'</div>';
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
                    
                    $avatar = '<img class="avatar avatar-24 photo" alt="" src="'.get_the_author_meta( 'si_avatar', $author_id ).'" width="24" height="24">';
                    $meta = '<span class="vcard author"><a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ).'">'.$avatar.' <span class="fn">'.get_the_author_meta( 'display_name', $author_id ).'</span></a></span>';
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
                    $excerpt =  get_the_excerpt( get_the_ID() );
                    $excerpt = json_decode($excerpt);
                    $meta = '<a href="'.esc_url( get_permalink() ).'#comments">'.$excerpt->comment_count.'</a>';
					break;
                                
                case 'likes':
                    $excerpt =  get_the_excerpt( get_the_ID() );
                    $excerpt = json_decode($excerpt);
                    $meta = '<span class="fa fa-heart">'.$excerpt->like_count.'</span>';
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
 * Get comment avatar only on comments template. 
 *
 * Function outputs avatar HTML based on wp_commentmeta
 *
 * @param string $avatar   
 * @param (integer/string/object) $id_or_email with most comment templates you can use $comment here
 * @return string HTML output of avatar
 * @since  1.0
 */

function gridlove_get_comment_avatar($avatar, $comment, $size, $default, $alt)
{
    global $in_comment_loop;

    if(isset($in_comment_loop))
    {
        if($in_comment_loop == true)
        {
            $meta = get_comment_meta( $comment->comment_ID, 'si_avatar', true );
            if (!empty($meta)) {
                $avatar = "<img alt='{$alt}' class='avatar avatar-{$size} photo avatar-default' alt='' src='".$meta."' srcset='".$meta." 2x' width='{$size}' height='{$size}'>";
            }
            return $avatar;
        }
    }
    
    return $avatar;
}

add_filter("get_avatar" , "gridlove_get_comment_avatar" , 10, 5);

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
			'video' => 'sprite-video',
			'audio' => 'sprite-audio',
			'gallery' => 'sprite-gallery',
            'image' => 'sprite-image' 
		);

		//Allow plugins or child themes to modify icons
		$icons = apply_filters( 'gridlove_modify_post_format_icons', $icons );

		if ( $format && array_key_exists( $format, $icons ) ) {

			return wp_kses_post( '<span class="gridlove-format-icon '.esc_attr( $icons[$format] ).'"></span>' );
		}

		return '';
	}
endif;

/**
 * comments navigation
 */
if(!function_exists('gridlove_get_comment_nav')) :
    function gridlove_get_comment_nav(){
        $output = '';
        $output .= '<nav class="navigation comment-navigation" role="navigation">';
        $output .= '<div class="nav-links">';
		
        if ( $prev_link = get_previous_comments_link( '<i class="fa fa-chevron-left"></i>' ) ) :
            $output .= sprintf( '<div class="nav-previous">%s</div>', $prev_link );
        endif;

        if ( $next_link = get_next_comments_link( '<i class="fa fa-chevron-right"></i>' ) ) :
            $output .= sprintf( '<div class="nav-next">%s</div>', $next_link );
        endif;
		
		$output .= '</div><!-- .nav-links -->';
        $output .= '</nav><!-- .comment-navigation -->';
        
        return $output;
    }
endif;

/**
 * AJAX comment
 * 
 * AJAX评论需4.4以上版本
 *
 * @since  1.0
 */

function gridlove_set_comment_form_defaults( $defaults ) {
	$defaults['action'] = '';
	return $defaults;
}

add_filter( 'comment_form_defaults', 'gridlove_set_comment_form_defaults' );

if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	wp_die('Please upgrade wordpress to version 4.4 or above.');
}

if(!function_exists('gridlove_ajax_comment_err')) :

    function gridlove_ajax_comment_err($a) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }

endif;

if(!function_exists('gridlove_ajax_comment_callback')) :

    function gridlove_ajax_comment_callback(){
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
            	gridlove_ajax_comment_err($comment->get_error_message());
            } else {
                exit;
            }
        }
        $user = wp_get_current_user();
        do_action('set_comment_cookies', $comment, $user);
        $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
          <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
              <div class="comment-author vcard">
                <?php echo get_avatar( $comment, 50 ); ?>
                <b class="fn"><?php comment_author();?></b>
                <span class="says">says:</span>
              </div>
              <div class="comment-metadata">
              <?php $cpage = get_page_of_comment(); $url=''; if(isset($cpage)){$url .= 'comment-page-'.$cpage.'/';} $url .= '#comment-'.get_comment_ID(); ?>
                <a href="<?php echo $url; ?>">
                    <time datetime="<?php comment_time(); ?>"><?php printf( __('%1$s at %2$s'), get_comment_date( 'F j, Y' ),  get_comment_time() ); ?></time>
                </a>
                <span class="edit-link">
                    <?php edit_comment_link( 'Edit' , '&nbsp;', '' ); ?>
                </span>
              </div>
            </footer>
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <div class="comment-awaiting-moderation">Your comment is awaiting moderation.</div>
            <?php endif; ?>
          </article>
        </li>
        <?php die();
    }

endif;

add_action('wp_ajax_nopriv_ajax_comment', 'gridlove_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'gridlove_ajax_comment_callback');

/* Remove comment-reply.min.js (header hook)
/* ------------------------------------- */
function gridlove_del_comment_reply(){
    wp_deregister_script( 'comment-reply' );
}
add_action('init','gridlove_del_comment_reply');


/* Description: Customized configuration for wordpress
 * Description: 定制WP的核心配置
 * Author: 萌える動 • 萌动网
 * Author URI: http://moemob.com
/* -------------------------------------------------------------------------- */
// Say goodbye to autosave feature!
add_action('wp_print_scripts','disable_autosave');
function disable_autosave(){  
    wp_deregister_script('autosave'); 
}

// Disable revisioning feature!
add_filter( 'wp_revisions_to_keep', 'disable_revisions', 10, 2 );
function disable_revisions( $num, $post ) {
    return 0;
}

// Disable Feed/RSS feature!
function disable_feed() {
	wp_die(__('<h1>Oops! That page can\'t be found.</h1>'));
}
add_action('do_feed',      'disable_feed', 1);
add_action('do_feed_rdf',  'disable_feed', 1);
add_action('do_feed_rss',  'disable_feed', 1);
add_action('do_feed_rss2', 'disable_feed', 1);
add_action('do_feed_atom', 'disable_feed', 1);

// Remove admin bar at front
add_filter( 'show_admin_bar', '__return_false' );

// Optmize header metas feature!
head_optmizer();
function head_optmizer() {
    $wpho_option_values = array(
        '_emoji' => 1,
        '_canonical' => 1,
        '_wp_version' => 1,
        '_shortlink' => 1,
        '_rss_feed' => 1,
        '_edituri' => 1,
        '_jsonapi' => 1,
        '_ss_vesions' => 1,
        '_wlwmanifest' => 1,
        '_np_urls' => 1,//Next/Previous Post URLs Links
        '_restapi_link' => 1,
        '_oembed_desc_link' => 1,
    );
	
    //Disable WP Emoji
    if($wpho_option_values['_emoji'] == 1){
        
        // remove actions / filters related to emojis
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        
        // added filter to remove TinyMCE emojis as well
        add_filter( 'tiny_mce_plugins', 'disable_wp_emojicons_tinymce' );	
    }
    
    //Remove Canonical URL
    if($wpho_option_values['_canonical'] == 1){
        remove_action('wp_head', 'rel_canonical');
    }
    
    
    //Remove WordPress Version			
    if($wpho_option_values['_wp_version'] == 1){
        remove_action('wp_head', 'wp_generator');
    }
    
    //Remove Shortlink		
    if($wpho_option_values['_shortlink'] == 1){
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
    
    //Remove RSS Feed URL		
    if($wpho_option_values['_rss_feed'] == 1){
        remove_action( 'wp_head', 'feed_links_extra', 3 ); //Extra feeds such as category feeds
        remove_action( 'wp_head', 'feed_links', 2 ); // General feeds: Post and Comment Feed
    }
    
    //Remove EditURI		
    if($wpho_option_values['_edituri'] == 1){
        remove_action ('wp_head', 'rsd_link');
    }
    
    //Disable JSON API		
    if($wpho_option_values['_jsonapi'] == 1){
        add_filter('json_enabled', '__return_false');
        add_filter('json_jsonp_enabled', '__return_false');
    }
    
    
    //Remove Style and Script Versions
    if($wpho_option_values['_ss_vesions'] == 1){
        add_filter( 'style_loader_src', 'remove_ver_css_js', 9999 );
        add_filter( 'script_loader_src', 'remove_ver_css_js', 9999 );
    }
    
    //Remove WLW Manifest
    if($wpho_option_values['_wlwmanifest'] == 1){
        remove_action( 'wp_head', 'wlwmanifest_link');
    }
    
    //Remove Next/Previous Post URLs Links
    if($wpho_option_values['_np_urls'] == 1){
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    }
    
    //Remove REST API link tags
    if($wpho_option_values['_restapi_link'] == 1){
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
    }
    
    // Remove oEmbed Discovery Links
    if($wpho_option_values['_oembed_desc_link'] == 1){
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        remove_action('wp_head', 'wp_oembed_add_host_js');//remove wp-embed.min.js
    }
}

function remove_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// Custom uploads
function custom_upload_directory( $uploads ) {
    date_default_timezone_set("Asia/Shanghai");//Time Zone
    $subdir = date("Y");//Format Set
    $uploads['subdir'] = $subdir;
    $uploads['path'] = $uploads['basedir'].DIRECTORY_SEPARATOR.$subdir;
    $uploads['url'] = $uploads['baseurl'].'/'.$subdir;
    return $uploads;
}
add_filter( 'upload_dir', 'custom_upload_directory' );

?>