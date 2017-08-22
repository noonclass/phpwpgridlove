<?php
/*!**************************************************************
Theme Name: MOE-PIX
Theme URI: http://moemob.com/moe-pix
Author: 萌える動 • 萌动网
Author URI: http://moemob.com
Description: 时尚自适应图片主题，集成了功能强大的前台用户中心
Version: 1.0
****************************************************************/
?>
<?php

class GRIDLOVE_Cover_Widget extends WP_Widget {
    var $defaults;
    
    function __construct(){
		$widget_ops = array( 'classname' => 'gridlove_cover_widget', 'description' => esc_html__('Display your post header with this widget', 'gridlove') );
		$control_ops = array( 'id_base' => 'gridlove_cover_widget' );
		parent::__construct( 'gridlove_cover_widget', esc_html__('Gridlove Cover', 'gridlove'), $widget_ops, $control_ops );

		$this->defaults = array( 
				'title' => ''
			);
	}
    function widget($args,$instance){
        extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
        
        echo $before_widget;
        
        if ( !empty($title) ) {
			echo $before_title . $title . $after_title;
		}
        
        echo $this->html($instance);
        
        echo $after_widget;
    }

	function update($new,$old){
		return $new;
	}

	function form($instance){
        $instance = wp_parse_args( (array) $instance, $this->defaults ); ?>
        	
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title', 'gridlove'); ?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
			<small class="howto"><?php esc_html_e('Leave empty for no title', 'gridlove'); ?></small>
		</p>
        			
	<?php
	}
    
    function html($instance){
        global $post;
        
        echo '<div class="entry-header">';
        
        gridlove_the_title( '<div class="entry-title">', '</div>' );
        
        $excerpt =  get_the_excerpt( $post->ID );
		$excerpt = json_decode($excerpt);
        if (isset($excerpt->location)){
            echo '<div class="entry-meta">';
            echo '<div class="meta-item meta-location"><span class="fa fa-location-arrow">'.$excerpt->location->name.'</span></div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}
?>