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
    
    gridlove_ality_scripts();
    wp_localize_script( 'jquery-mCommentSubmit', 'wp_js_settings', gridlove_ality_settings() );
}


/*
Add by moemob.com
*/
/* Add js/css - 加载功能性脚本
/* ------------------------------------- */
if ( !function_exists( 'gridlove_ality_scripts' ) ):
    function gridlove_ality_scripts() {
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', trailingslashit(get_stylesheet_directory_uri()).'jquery.min.js' , false, '3.2.1', false );
        wp_enqueue_script( 'jquery' );
        
        if( is_single()) {
            // NOTE:: 不支持wordpress原生的jquery.min.js?ver=1.12.4
            wp_register_script('jquery-mCommentSubmit', trailingslashit(get_stylesheet_directory_uri()).'jquery.mCommentSubmit.js', array('jquery'), '1.1.0', true);
            wp_enqueue_script('jquery-mCommentSubmit');
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
        error_log(print_r($title,1));
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

?>