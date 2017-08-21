<?php 

/**
 * Load page metaboxes
 * 
 * Callback function for page metaboxes load
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_load_page_metaboxes' ) ) :
	function gridlove_load_page_metaboxes() {
		
		/* Layout metabox */
		add_meta_box(
			'gridlove_page_layout',
			esc_html__( 'Layout', 'gridlove' ),
			'gridlove_page_layout_metabox',
			'page',
			'side',
			'default'
		);

		/* Sidebar metabox */
		add_meta_box(
			'gridlove_page_sidebar',
			esc_html__( 'Sidebar', 'gridlove' ),
			'gridlove_page_sidebar_metabox',
			'page',
			'side',
			'default'
		);

		/* Cover area metabox */
		add_meta_box(
			'gridlove_cover',
			esc_html__( 'Cover Area', 'gridlove' ),
			'gridlove_cover_metabox',
			'page',
			'normal',
			'high'
		);

		/* Modules metabox */
		add_meta_box(
			'gridlove_modules',
			esc_html__( 'Modules', 'gridlove' ),
			'gridlove_modules_metabox',
			'page',
			'normal',
			'high'
		);

		/* Pagination metabox */
		add_meta_box(
			'gridlove_pagination',
			esc_html__( 'Pagination', 'gridlove' ),
			'gridlove_pagination_metabox',
			'page',
			'normal',
			'high'
		);

	}
endif;


/**
 * Save page meta
 * 
 * Callback function to save page meta data
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_save_page_metaboxes' ) ) :
	function gridlove_save_page_metaboxes( $post_id, $post ) {
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}
			
		if ( ! isset( $_POST['gridlove_page_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['gridlove_page_metabox_nonce'], 'gridlove_page_metabox_save' ) ) {
   			return;
		}

		if ( $post->post_type == 'page' && isset( $_POST['gridlove'] ) ) {
			$post_type = get_post_type_object( $post->post_type );
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;

			$gridlove_meta = array();

			if( isset( $_POST['gridlove']['sidebar'] ) &&  !empty($_POST['gridlove']['sidebar'])){
				foreach( $_POST['gridlove']['sidebar'] as $key => $value ){
					if(  $value != 'inherit' ){
						$gridlove_meta['sidebar'][$key] = $value; 
					}			
				}
			}
			
			if( isset( $_POST['gridlove']['layout'] ) &&  $_POST['gridlove']['layout'] != 'inherit' ){
				$gridlove_meta['layout'] = $_POST['gridlove']['layout'];
			}


			if( isset( $_POST['gridlove']['cover'] ) &&  !empty($_POST['gridlove']['cover']) ){
				
				foreach( $_POST['gridlove']['cover'] as $key => $value ){
					
					$gridlove_meta['cover'][$key] = $value; 					
				}

				if ( isset( $_POST['gridlove']['cover']['manual'] ) && !empty( $_POST['gridlove']['cover']['manual'] ) ) {
							$gridlove_meta['cover']['manual'] = array_map( 'absint', explode( ",", $_POST['gridlove']['cover']['manual'] ) );
				}

					if ( isset(  $_POST['gridlove']['cover']['tag'] ) && !empty(  $_POST['gridlove']['cover']['tag'] ) ) {
							$gridlove_meta['cover']['tag'] = gridlove_get_tax_term_slug_by_name( $_POST['gridlove']['cover'], 'post_tag');
				}
			}

			if(isset( $_POST['gridlove']['modules']) && !empty($_POST['gridlove']['modules']) ){
				
				foreach($_POST['gridlove']['modules'] as $i => $module ){
					if ( isset( $module['manual'] ) && !empty( $module['manual'] ) ) {
						$_POST['gridlove']['modules'][$i]['manual'] = array_map( 'absint', explode( ",", $module['manual'] ) );
					}

					if ( isset( $module['tag'] ) && !empty( $module['tag'] ) ) {
						$_POST['gridlove']['modules'][$i]['tag'] = gridlove_get_tax_term_slug_by_name( $module['tag'], 'post_tag');
					}

				}

				$gridlove_meta['modules'] = array_values($_POST['gridlove']['modules']);
			}

			if( isset( $_POST['gridlove']['pagination'] ) &&  $_POST['gridlove']['pagination'] != 'none' ){
				$gridlove_meta['pagination'] = $_POST['gridlove']['pagination'];
			}
			
			if(!empty($gridlove_meta)){
				update_post_meta( $post_id, '_gridlove_meta', $gridlove_meta );
			} else {
				delete_post_meta( $post_id, '_gridlove_meta');
			}

		}
	}
endif;


/**
 * Module generator metabox
 * 
 * Callback function to create modules metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_modules_metabox' ) ) :
	function gridlove_modules_metabox( $object, $box ) {

		wp_nonce_field( 'gridlove_page_metabox_save', 'gridlove_page_metabox_nonce' );

		$meta = gridlove_get_page_meta( $object->ID );
		$module_defaults = gridlove_get_module_defaults();
		$module_options = gridlove_get_module_options();

?>
		
		<?php if( empty( $meta['modules'] ) ) : ?>
			<p class="gridlove-empty-modules howto"><?php esc_html_e( 'You don\'t have any modules on this page yet. Click the button below to create your first module.', 'gridlove' ); ?></p>
		<?php endif; ?>

		<div class="gridlove-modules">
				<?php if(!empty( $meta['modules'] ) ): ?>
					<?php foreach($meta['modules'] as $i => $module ) : $module = gridlove_parse_args( $module, $module_defaults[$module['type']]); ?>
						<?php gridlove_generate_module( $module, $module_options[$module['type']], $i ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
		</div>

		<div class="gridlove-modules-bottom">
			<?php foreach( $module_defaults as $type => $module ): ?>
				<a href="javascript:void(0);" class="gridlove-add-module button-secondary" data-type="<?php echo esc_attr($type); ?>"><?php echo '+ '.$module['type_name']. ' ' .esc_html__( 'Module', 'gridlove'); ?></a>
			<?php endforeach; ?>
		</div>

		<div id="gridlove-module-clone">
			<?php foreach( $module_defaults as $type => $module ): ?>
				<div class="<?php echo esc_attr($type); ?>">
					<?php gridlove_generate_module( $module, $module_options[$type]); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="gridlove-modules-count" data-count="<?php echo esc_attr(count($meta['modules'])); ?>"></div>
				  	
	<?php
	}
endif;



/**
 * Generate module field
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $i id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module' ) ) :
	function gridlove_generate_module( $module, $options, $i = false ) {
		
		$name_prefix = ( $i === false ) ? '' :  'gridlove[modules]['.$i.']';
		$edit = ( $i === false ) ? '' :  'edit';
		$module_class = ( $i === false ) ? '' :  'gridlove-module-'.$i;
		$module_num = ( $i === false ) ? '' : $i;
?>
		<div class="gridlove-module <?php echo esc_attr($module_class); ?>" data-module="<?php echo esc_attr($module_num); ?>">
			
			<div class="left">
				<span class="gridlove-module-type">
					<?php echo ($module['type_name']); ?>
				</span>
				<span class="gridlove-module-title"><?php echo esc_html($module['title']); ?></span>
			</div>

			<div class="right">
				<a href="javascript:void(0);" class="gridlove-edit-module"><?php esc_html_e( 'Edit', 'gridlove' ); ?></a> | 
				<a href="javascript:void(0);" class="gridlove-remove-module"><?php esc_html_e( 'Remove', 'gridlove' ); ?></a>
			</div>

			<div class="gridlove-module-form <?php echo esc_attr($edit); ?>">
				
				<input class="gridlove-count-me" type="hidden" name="<?php echo esc_attr($name_prefix); ?>[type]" value="<?php echo esc_attr($module['type']); ?>"/>
				<?php call_user_func( 'gridlove_generate_module_'.$module['type'], $module, $options, $name_prefix ); ?>

		   	</div>

		</div>
		
	<?php
	}
endif;


/**
 * Generate posts module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module_posts' ) ) :
function gridlove_generate_module_posts( $module, $options, $name_prefix ){
	
	extract( $options ); ?>

	<div class="gridlove-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'gridlove' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'gridlove' ); ?></a>
	</div>

	<div class="gridlove-tab first">

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Title', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="gridlove-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'gridlove' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'gridlove' ); ?></small>

			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Layout', 'gridlove' ); ?>:
			</div>

		    <div class="gridlove-opt-content">

		    	<?php foreach ( $layout_types as $layout_type => $title ): ?>
		    		<label><input type="radio" class="gridlove-count-me gridlove-module-layout-switch" name="<?php echo esc_attr($name_prefix); ?>[layout_type]" value="<?php echo esc_attr($layout_type); ?>" <?php checked( $layout_type, $module['layout_type'] );?>/> <?php echo esc_html( $title ); ?></label>
		    	<?php endforeach; ?>

		    	<div class="gridlove-module-layouts">
		    		
		    		<?php $active = $module['layout_type'] == 'simple' ? 'active' : ''; ?>

					<div class="gridlove-module-layout simple <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $simple_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['simple_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step'] * 2); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[simple_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['simple_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>


		    		<?php $active = $module['layout_type'] == 'combo' ? 'active' : ''; ?>

		    		<div class="gridlove-module-layout combo <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $combo_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['combo_layout'] ) ? ' selected': ''; ?>
					  			
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step']); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[combo_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['combo_layout'] );?> />
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>

					<?php $active = $module['layout_type'] == 'slider' ? 'active' : ''; ?>

					<div class="gridlove-module-layout slider <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $slider_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['slider_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>"  data-min="<?php echo esc_attr($layout['step'] + 1); ?>" data-step="1" data-default="<?php echo esc_attr($layout['step'] + 1); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[slider_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['slider_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>

					</div>

					

				</div>

		    	
		    </div>

	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Number of posts', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<?php 
					switch($module['layout_type']){
						case 'combo': $layouts = gridlove_get_combo_layouts(); break;
						case 'slider':  $layouts = gridlove_get_slider_layouts(); break;
						default: $layouts = gridlove_get_simple_layouts(); break;
					}

					foreach($layouts as $id => $layout ){
						if( $id == $module[$module['layout_type'].'_layout']){
							$selected_step = $layout['step'];
							$selected_min = $layout['step'];
							if( $module['layout_type'] == 'slider'){
								$selected_step = 1;
								$selected_min++; 
							}
							break;
						}
					}
				?>
				<input class="gridlove-count-me gridlove-input-slider" type="range" min="<?php echo esc_attr($selected_min); ?>" step="<?php echo esc_attr($selected_step); ?>" max="30" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($module['limit']);?>"/> <span class="gridlove-slider-opt-count"><?php echo esc_attr($module['limit']);?></span><br/>
			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Actions:', 'gridlove' ); ?>
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[more_link]" value="1" <?php checked( $module['more_link'], 1 ); ?> class="gridlove-count-me gridlove-more-button-switch"/> <?php esc_html_e( 'Display "view all" button', 'gridlove' ); ?> </label>
		   		<?php $hidden_class = $module['more_link'] ? '' : 'gridlove-hidden'; ?>
		   		<div class="gridlove-more-button-opt <?php echo esc_attr( $hidden_class ); ?>">
			   		<label><?php esc_html_e( 'Text', 'gridlove' ); ?>:</label><input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_text]" value="<?php echo esc_attr($module['more_text']);?>" class="gridlove-count-me" />
			   		<br/><label><?php esc_html_e( 'URL', 'gridlove' ); ?>:</label><input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_url]" value="<?php echo esc_attr($module['more_url']);?>" class="gridlove-count-me" /><br/>
			   		<small class="howto"><?php esc_html_e( 'Specify text and URL for "view all" button', 'gridlove' ); ?></small>
		   		</div>

		   	</div>
	    </div>


	</div>

	<div class="gridlove-tab">
		
		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Order by', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['order'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
					<br/><?php esc_html_e( 'Or choose manually', 'gridlove' ); ?>:<br/>
		   		<?php $manual = !empty( $module['manual'] ) ? implode( ",", $module['manual'] ) : ''; ?>
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" class="gridlove-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify post ids separated by comma if you want to select only those posts. i.e. 213,32,12,45', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	     <div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Sort', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $module['sort'], 'DESC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Descending', 'gridlove') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $module['sort'], 'ASC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Ascending', 'gridlove') ?></label><br/>
		   	</div>
	    </div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'In category', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<div class="gridlove-fit-height">
		   		<?php foreach ( $cats as $cat ) : ?>
		   			<?php $checked = in_array( $cat->term_id, $module['cat'] ) ? 'checked' : ''; ?>
		   			<label><input class="gridlove-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr( $checked ); ?> /><?php echo esc_html( $cat->name );?></label><br/>
		   		<?php endforeach; ?>
		   		</div>
		   		<small class="howto"><?php esc_html_e( 'Check whether you want to display posts from specific categories only', 'gridlove' ); ?></small>
		   		<br/>
		   		<label><input type="checkbox" name="<?php echo esc_attr( $name_prefix ); ?>[cat_child]" value="1" class="gridlove-count-me" <?php checked( $module['cat_child'], 1 );?>/><?php esc_html_e( 'Apply child categories', 'gridlove' ); ?></label><br/>
		    	<small class="howto"><?php esc_html_e( 'If parent category is selected, posts from child categories will be included automatically', 'gridlove' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Tagged with', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[tag]" value="<?php echo esc_attr(gridlove_get_tax_term_name_by_slug($module['tag'])); ?>" class="gridlove-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify one or more tags separated by comma. i.e. life, cooking, funny moments', 'gridlove' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Format', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $formats as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[format]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['format'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that have a specific format', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Not older than', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['time'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $module['unique'], 1 ); ?> class="gridlove-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, posts in this module will be excluded from other modules below.', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	</div>

<?php }
endif;


/**
 * Generate text module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module_text' ) ) :
	function gridlove_generate_module_text( $module, $options, $name_prefix ){
		
		extract( $options ); ?>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Title', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="gridlove-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'gridlove' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'gridlove' ); ?></small>				
			</div>
		</div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Content', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<textarea class="gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[content]"><?php echo wp_kses_post( $module['content'] ); ?></textarea>
				<small class="howto"><?php esc_html_e( 'Paste any text, HTML, script or shortcodes here', 'gridlove' ); ?></small>
				<br/>
				<label>
					<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autop]" value="1" <?php checked( $module['autop'], 1 ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Automatically add paragraphs', 'gridlove' ); ?>
				</label> <br/>

				<label>
					<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[center]" value="1" <?php checked( $module['center'], 1 ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Center align content', 'gridlove' ); ?>
				</label>
			</div>
		</div>

		 <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Style', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				
				<label>
					<input type="radio" name="<?php echo esc_attr($name_prefix); ?>[style]" value="boxed" <?php checked( $module['style'], 'boxed' ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Boxed (the same as posts module)', 'gridlove' ); ?>
				</label> <br/>

				<label>
					<input type="radio" name="<?php echo esc_attr($name_prefix); ?>[style]" value="transparent" <?php checked( $module['style'], 'transparent' ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Transparent (without box and background)', 'gridlove' ); ?>
				</label>
				<small class="howto"><?php esc_html_e( 'Choose how to display text module', 'gridlove' ); ?></small>
			</div>
		</div>

	<?php }
endif;

/**
 * Cover area metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_cover_metabox' ) ) :
function gridlove_cover_metabox( $object, $box ){
	
	$meta = gridlove_get_page_meta( $object->ID, 'cover' );

	$layouts = gridlove_get_cover_layouts( false, true );
	$order = gridlove_get_post_order_opts();
	$cats = get_categories( array( 'hide_empty' => false, 'number' => 0 ) );
	$time = gridlove_get_time_diff_opts();
	$formats = gridlove_get_post_format_opts();

	$name_prefix = 'gridlove[cover]';

	?>

	<div class="gridlove-opt-box">

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Layout', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
			    <ul class="gridlove-img-select-wrap">
			  	<?php foreach ( $layouts as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = gridlove_compare( $id, $meta['layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose your cover area layout', 'gridlove' ); ?></small>
		    </div>
	    </div>

	    <div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Number of posts', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($meta['limit']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Max number of posts to display', 'gridlove' ); ?></small>
			</div>
		</div>

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Order by', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['order'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
					<br/><?php esc_html_e( 'Or choose manually', 'gridlove' ); ?>:<br/>
		   		<?php $manual = !empty( $meta['manual'] ) ? implode( ",", $meta['manual'] ) : ''; ?>
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" class="gridlove-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify post ids separated by comma if you want to select only those posts. i.e. 213,32,12,45', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	    <div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Sort', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $meta['sort'], 'DESC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Descending', 'gridlove') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $meta['sort'], 'ASC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Ascending', 'gridlove') ?></label><br/>
		   	</div>
	    </div>

	    <div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $meta['unique'], 1 ); ?> class="gridlove-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, selected posts will be excluded from modules.', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	</div>

	<div class="gridlove-opt-box">

		

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'In category', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<div class="gridlove-fit-height">
		   		<?php foreach ( $cats as $cat ) : ?>
		   			<?php $checked = in_array( $cat->term_id, $meta['cat'] ) ? 'checked' : ''; ?>
		   			<label><input class="gridlove-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr( $checked ); ?> /><?php echo esc_html( $cat->name );?></label><br/>
		   		<?php endforeach; ?>
		   		</div>
		   		<small class="howto"><?php esc_html_e( 'Check whether you want to display posts from specific categories only', 'gridlove' ); ?></small>
		   		<br/>
		   		<label><input type="checkbox" name="<?php echo esc_attr( $name_prefix ); ?>[cat_child]" value="1" class="gridlove-count-me" <?php checked( $meta['cat_child'], 1 );?>/><?php esc_html_e( 'Apply child categories', 'gridlove' ); ?></label><br/>
		    	<small class="howto"><?php esc_html_e( 'If parent category is selected, posts from child categories will be included automatically', 'gridlove' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Tagged with', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[tag]" value="<?php echo esc_attr(gridlove_get_tax_term_name_by_slug($meta['tag'])); ?>" class="gridlove-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify one or more tags separated by comma. i.e. life, cooking, funny moments', 'gridlove' ); ?></small>
		   	</div>
	   	</div>

	   	<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Format', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $formats as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[format]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['format'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that have a specific format', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Not older than', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['time'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

	   	

	</div>



<?php }
endif;


/**
 * Pagination metabox
 * 
 * Callback function to create pagination metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_pagination_metabox' ) ) :
	function gridlove_pagination_metabox( $object, $box ) {
		
		$meta = gridlove_get_page_meta( $object->ID );
		$layouts = gridlove_get_pagination_layouts( false, true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $layouts as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $meta['pagination'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html( $layout['title'] ); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[pagination]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['pagination'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Note: Pagination will be applied to the last post module on the page', 'gridlove' ); ?></p>

	  <?php
	}
endif;


/**
 * Layout metabox
 * 
 * Callback function to create layout metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_page_layout_metabox' ) ) :
	function gridlove_page_layout_metabox( $object, $box ) {
		
		wp_nonce_field( 'gridlove_page_metabox_save', 'gridlove_page_metabox_nonce' );

		$gridlove_meta = gridlove_get_page_meta( $object->ID );
		$layouts = gridlove_get_page_layouts( true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $layouts as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $gridlove_meta['layout'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html( $layout['title'] ); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $gridlove_meta['layout'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Choose a layout', 'gridlove' ); ?></p>

	  <?php
	}
endif;

/**
 * Sidebar metabox
 * 
 * Callback function to create sidebar metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_page_sidebar_metabox' ) ) :
	function gridlove_page_sidebar_metabox( $object, $box ) {
		

		$sidebar = gridlove_get_page_meta( $object->ID, 'sidebar' );
		$sidebars_lay = gridlove_get_sidebar_layouts( true );
		$sidebars = gridlove_get_sidebars_list( true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $sidebars_lay as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $sidebar['position'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html( $layout['title'] ); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[sidebar][position]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $sidebar['position'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Display sidebar', 'gridlove' ); ?></p>

	  <?php if ( !empty( $sidebars ) ): ?>

	  	<p><select name="gridlove[sidebar][standard]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $sidebar['standard'] );?>><?php echo esc_html( $name ); ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose standard sidebar to display', 'gridlove' ); ?></p>

	  	<p><select name="gridlove[sidebar][sticky]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $sidebar['sticky'] );?>><?php echo esc_html( $name ); ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose sticky sidebar to display', 'gridlove' ); ?></p>

	  <?php endif; ?>
	  <?php
	}
endif;

?>