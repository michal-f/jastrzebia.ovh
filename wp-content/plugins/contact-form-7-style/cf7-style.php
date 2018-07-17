<?php
/*
Plugin Name: Contact Form 7 Style
Plugin URI: http://wordpress.reea.net/contact-form-7-style/
Description: Simple style customization and templating for Contact Form 7 forms. Requires Contact Form 7 plugin installed.
Version: 2.2.7
Author: Reea
Author URI: http://www.reea.net?ref="wordpress.org"
License: GPL2
@Author: mlehelsz, dorumarginean, Johnny, MirceaR
*/

/**
 *	Include the plugin options
 */
function set_styleversion(){
	return "2.2.7";
}

function get_predefined_cf7_style_template_data() {
	return array ( 
		array (
			'title'		=> 'Twenty Fifteen Pattern',
			'category'	=> 'simple pattern style',
			'image'		=> '/admin/images/cf7_simple_twentyfifteen_pattern.jpg'
		),
		array (
			'title'		=> 'Christmas Classic',
			'category'	=> 'christmas style',
			'image'		=> '/admin/images/cf7_xmas_classic.jpg'
		),
		array (
			'title'		=> 'Christmas Red',
			'category'	=> 'christmas style',
			'image'		=> '/admin/images/cf7_xmas_red.jpg',
		),
		array (
			'title'		=> 'Christmas Simple',
			'category'	=> 'christmas style',
			'image'		=> '/admin/images/cf7_xmas_simple.jpg'
		),
		array (
			'title'		=> "Valentine's Day Classic",
			'category'	=> "valentine's day style",
			'image'		=> '/admin/images/cf7_vday_classic.jpg'
		),
		array (
			'title'		=> "Valentine's Day Roses",
			'category'	=> "valentine's day style",
			'image'		=> '/admin/images/cf7_vday_roses.jpg'
		),
		array (
			'title'		=> "Valentine's Day Birds",
			'category'	=> "valentine's day style",
			'image'		=> '/admin/images/cf7_vday_birds.jpg'
		),
		array (
			'title'		=> "Valentine's Day Blue Birds",
			'category'	=> "valentine's day style",
			'image'		=> '/admin/images/cf7_vday_blue_birds.jpg'
		)
	);
}// end of get_predefined_cf7_style_template_data


/**
 * Get contact form 7 id
 * 
 * Back compat for CF7 3.9 
 * @see http://contactform7.com/2014/07/02/contact-form-7-39-beta/
 * 
 * @param $cf7 Contact Form 7 object
 * @since 0.1.0
 */		
function get_form_id( $cf7 ) {
	if ( version_compare( WPCF7_VERSION, '3.9-alpha', '>' ) ) {
	    if (!is_object($cf7)) {
	        return false;
	    }

	    return $cf7->id();
	}
}


/**
 * Add cf7skins classes to the CF7 HTML form class
 * 
 * Based on selected template & style
 * eg. class="wpcf7-form cf7t-fieldset cf7s-wild-west"
 * 
 * @uses 'wpcf7_form_class_attr' filter in WPCF7_ContactForm->form_html()
 * @uses wpcf7_get_current_contact_form()
 * @file wp-content\plugins\contact-form-7\includes\contact-form.php
 * 
 * @param $class is the CF7 HTML form class
 * @since 0.0.1
 */		
function form_class_attr( $class, $id ) {

	// Get the current CF7 form ID
	$cf7 = wpcf7_get_current_contact_form();  // Current contact form 7 object
	$form_id = get_form_id( $cf7 );

	$template_class = '';
	$cf7_style_id 	= get_post_meta( $form_id, 'cf7_style_id' );
	if ( isset( $cf7_style_id[0]) ) {
		$cf7_style_data = get_post( $cf7_style_id[0], ARRAY_A );
		$template_class = ( $id == "yes" ) ? $cf7_style_id[0] : $cf7_style_data['post_name'];
	}	

	
	// Return the modified class
	return $template_class;
}


/**
 * Get active styles
 */

function active_styles() {

	$args = array( 
		'post_type'			=> 'wpcf7_contact_form',
		'post_status'		=> 'publish',
		'posts_per_page' 	=> -1
	);
	$active_styles = array();
	$forms = new WP_Query( $args );

	if( $forms->have_posts() ) :
		while( $forms->have_posts() ) : $forms->the_post();
			$form_title = get_the_title();
			$id 		= get_the_ID();
			$style_id = get_post_meta( $id, 'cf7_style_id', true );

			if ( ! empty( $style_id ) || $style_id != 0 ) {
				$active_styles[] = $style_id;
			}
			
		endwhile;
		wp_reset_postdata();
	endif; 

	return $active_styles;
}


function count_element_settings( $elements, $checks ){
	$inner = 0;
	$arr = array();
	foreach ( $checks as $index => $check ) {
		$inner = 0;
		foreach ( $elements as $key => $element ) {
			 if ( strpos( $key, $check ) === 0 ) {
			 	$arr[ $index ] = $inner++;
			 }
		}
	}
	return $arr;
}
function cf7_style_custom_css_generator(){

	global $post;
	if( empty( $post ) ) {
		return false;
	}

	$args = array( 
		'post_type'			=> 'wpcf7_contact_form',
		'post_status'		=> 'publish',
		'posts_per_page' 	=> -1
	);
	$style_number = 0;
	$forms = new WP_Query( $args );
	$style = '';
	$active_styles = array();
	$total_num_posts = $forms->found_posts;

	if( $forms->have_posts() ) :
		while( $forms->have_posts() ) : $forms->the_post();
			$form_title = get_the_title();
			$id 		= get_the_ID();
			$cf7s_id = get_post_meta( $id, 'cf7_style_id', true );

			if ( ( ! empty( $cf7s_id ) || $cf7s_id !== 0 ) && ! in_array( $cf7s_id, $active_styles ) ) {
				if( empty( $active_styles ) ) {
					$style 				.= "\n<style class='cf7-style' media='screen' type='text/css'>\n";
				}				
				array_push( $active_styles, $cf7s_id );

				$cf7s_slug = sanitize_title( get_the_title( $cf7s_id ) );
				
				if( $cf7s_slug == "" ) {
					$cf7s_slug = "cf7-style-" . $cf7s_id;
				}

				$custom_cat 			= get_the_terms( $cf7s_id, "style_category" );
				$custom_cat_name 		= ( !empty( $custom_cat ) ) ? $custom_cat[ 0 ]->name : "";
				$cf7s_manual_style		= get_post_meta( $cf7s_id, 'cf7_style_manual_style', true );
				if (  $custom_cat_name == "custom style" ) {
					$cf7s_custom_settings = unserialize( get_post_meta( $cf7s_id, 'cf7_style_custom_styles', true ) );
					$temp       = 0; 
					$temp_1     = 0;
					$temp_2     = 0; 
					$temp_3     = 0; 
					$temp_4     = 0;
					
			        $form_set_nr = count_element_settings( $cf7s_custom_settings, array( "form", "input", "label", "submit", "textarea" ) );
			                    
					foreach( $cf7s_custom_settings as $setting_key => $setting ) {
						$setting_key_part 	= explode( "-", $setting_key );
						$second_part		= ( $setting_key_part[0] != "submit" ) ? $setting_key_part[1] : "";
						$third_part			= ( !empty( $setting_key_part[2] ) ) ? ( ( $setting_key_part[0] != "submit" ) ? "-" : "" ) . $setting_key_part[2] : "";
						$fourth_part 		= ( !empty( $setting_key_part[3] ) && $setting_key_part[0] == "submit" ) ? "-" . $setting_key_part[3] : "";

						$classelem = "body .cf7-style." . ( ( is_numeric( $cf7s_slug ) ) ? "cf7-style-".$cf7s_slug : $cf7s_slug );
						switch ( $setting_key_part[ 0 ]) {
							case 'form':
								$startelem 	= $temp;
								$allelem 	= $form_set_nr[ 0 ];
								$temp++;
								break;
							case 'input':
								$startelem 	= $temp_1;
								$allelem 	= $form_set_nr[ 1 ];
								$classelem 	.= " input,\n".$classelem." textarea,\n".$classelem." input:focus,\n".$classelem." textarea:focus,\n".$classelem." textarea:focus,\n" . $classelem . " input[type=\"submit\"]:hover,\n".$classelem." .wpcf7-submit:not([disabled]),\n".$classelem." .wpcf7-submit:not([disabled]):hover";
								$temp_1++;
								break;
							case 'label':
								$startelem 	= $temp_2;
								$allelem 	= $form_set_nr[ 2 ];	
								$classelem 	.= " label,\n".$classelem." > p";
								$temp_2++;            
								break;
							case 'submit':
								$startelem 	= $temp_3;
								$allelem 	= $form_set_nr[ 3 ];
								$classelem 	.= " .wpcf7-submit,\n".$classelem." .wpcf7-submit:focus,\n".$classelem." input[type=\"submit\"],\n".$classelem." input[type=\"submit\"]:hover,\n".$classelem." .wpcf7-submit:not([disabled]),\n".$classelem." .wpcf7-submit:not([disabled]):hover"; 
								$temp_3++;
								break;
							case 'textarea':
								$startelem 	= $temp_4;
								$allelem 	= $form_set_nr[ 4 ];
								$classelem 	.= " textarea,\n".$classelem." textarea:focus";
								$temp_4++;
								break;
							default:
								# code...
								break;
						}

						$style .= ( $startelem == 0 ) ? $classelem . " {\n" : "";
       
						/*$style .= ( $setting != "" && $setting != "Default" && ( $second_part != 'box' && $third_part != 'box' ) ) ? "\t" . $second_part . $third_part . $fourth_part . ": ". ( ( !is_numeric( $setting ) ) ? $setting : $setting . "px" ) . ";\n" : "";*/
						
						if ( $setting != "" && $setting != "Default" && ( $second_part != 'box' && $third_part != 'box'  ) && ( $second_part != 'line' || $third_part != 'line'  ) ) {

							$style .= "\t" . $second_part . $third_part . $fourth_part . ": ";

							if (   !is_numeric( $setting  ) && $setting !== '' ) {
								$style .= $setting;
							} else {
								$style .= $setting . "px"; 
							}
							$style .= ";\n";
						} 
			                        
						if( $second_part == 'line' && $setting == "" ) {
			                                
							$style .= "\t" . $second_part . $third_part . ": normal;\n"; 
						}

						if ( $third_part == "line" && $setting == "" ) {
							$style .= "\t" . $third_part . $fourth_part . ": normal;\n"; 
						}

						if( ( $second_part == 'box' || $third_part == 'box' ) && $setting != "Default" ) {
							$style .= "\t -moz-" . $second_part . $third_part . ": ". $setting . ";\n";
							$style .= "\t -webkit-" . $second_part . $third_part . ": ". $setting . ";\n";
							$style .= "\t" . $second_part . $third_part . ": ". $setting . ";\n"; 
						}

						$style .= ( $startelem == $allelem || $allelem == 1 ) ? "}\n" : "";
			                        
					}
				}
				$font_family = return_font_name( $cf7s_id );

				if( ! empty( $font_family ) && "none" !== $font_family ) {
					$style .= 'body .cf7-style.' . $cf7s_slug . ', body .cf7-style.'  . $cf7s_slug . " input[type='submit'] {\n\t font-family: '" . $font_family . "',sans-serif;\n} ";
				}
				if( !empty( $cf7s_manual_style ) ){
					$style.= "\n".$cf7s_manual_style."\n";
				}

				$style_number++;
			}


		endwhile;
		
		if( ( $style_number !== 0 ) && $style_number == count( $active_styles ) ) {
			$style .= "\n</style>\n";
		}		

		echo $style;

		wp_reset_postdata();
	endif; 				

}// end of cf7_style_custom_css_generator

//include_once( 'cf7-style-settings.php' );
function cf7_style_admin_scripts(){
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( "cf7_style_codemirror_js", plugin_dir_url( __FILE__ ) . "admin/js/codemirror.js", array( 'jquery' ), false, true );
	wp_enqueue_style( "cf7-style-codemirror-style", plugin_dir_url( __FILE__ ) . "admin/css/codemirror.css", false, set_styleversion(), "all" );
	wp_enqueue_script( "cf7-style-codemirror-mode", plugin_dir_url( __FILE__ ) . "admin/js/mode/css/css.js",  array( 'jquery' ), false, true );
 
	wp_enqueue_style( "cf7-style-admin-style", plugin_dir_url( __FILE__ ) . "admin/css/admin.css", false, "1.0", "all");  
	wp_enqueue_script( "cf7_style_admin_js", plugin_dir_url( __FILE__ ) . "admin/js/admin.js", array( 'wp-color-picker' ), false, true );
}
function cf7_style_add_class( $class ){
	global $post;
	$temp_slug = form_class_attr( $post, "no" );
	$class .= " cf7-style ". ( ( is_numeric( $temp_slug ) ) ? "cf7-style-".$temp_slug : $temp_slug );
	return $class;
}// end of cf7_style_add_class
/**
 *	Check if Contact Form 7 is activated
 */
function contact_form_7_check() {
	
	// WordPress active plugins
	$active_plugins = get_option( 'active_plugins' );
	
	if ( $active_plugins ) {
		// plugins to active
		$required_plugin = 'contact-form-7/wp-contact-form-7.php';

		if ( ! in_array( $required_plugin, $active_plugins ) ) {

			$html = '<div class="updated">';
			$html .= '<p>';
			$html .= 'Contact form 7 - Style is an addon. Please install <a href="http://wordpress.org/plugins/contact-form-7/" target="_blank">Contact form 7</a>.';
			$html .= '</p>';
			$html .= '</div><!-- /.updated -->';
			echo $html;
		} else {
			// Get the cf7_style_cookie 
			$cf7_style_cookie = get_option( 'cf7_style_cookie' );
			if( $cf7_style_cookie != true ) {

				$html = '<div class="updated">';
				$html .= '<p>';
				$html .= 'Contact Form 7 - Style addon is now activated. Navigate to <a href="' . get_bloginfo( "url" ) . '/wp-admin/edit.php?post_type=cf7_style">Contact Style</a> to get started.';
				$html .= '</p>';
				$html .= '</div><!-- /.updated -->';
				echo $html; 
				update_option( 'cf7_style_cookie', true );
			} // end if !$cf7_style_cookie		
		}		
	} // end if $active_plugins	
}
add_action( 'admin_notices', 'contact_form_7_check' );

function cf7_style_create_post( $slug, $title, $image) {
	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id = -1;
	// If the page doesn't already exist, then create it
	if( null == get_page_by_title( $title, "OBJECT", "cf7_style" ) ) {
	// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'  	=> 'closed',
				'ping_status'   		=> 'closed',
				'post_name'   		=> $slug,
				'post_title'    		=> $title,
				'post_status'   		=> 'publish',
				'post_type'   		=> 'cf7_style'
			)
		);
		//if is_wp_error doesn't trigger, then we add the image
		if ( is_wp_error( $post_id ) ) {
			$errors = $post_id->get_error_messages();
			foreach ($errors as $error) {
				echo $error . '<br>'; 
			}
		} else {
			//wp_set_object_terms( $post_id, $category, 'style_category', false );
			update_post_meta( $post_id, 'cf7_style_image_preview', $image );
		}
	// Otherwise, we'll stop
	} else {
	// Arbitrarily use -2 to indicate that the page with the title already exists
		$post_id = -2;
	} // end if
} // end cf7_style_create_post
function cf7_style_add_taxonomy_filters() {
	global $typenow;
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array( 'style_category' );
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'cf7_style' ){
		foreach ( $taxonomies as $tax_slug ) {
			$tax_obj = get_taxonomy( $tax_slug );
			
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms( $tax_slug );
			if( count( $terms ) > 0 ) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";
				foreach ( $terms as $term ) {
					$resultA = "<option value='".$term->slug."' selected='selected'>".$term->name .' (' . $term->count .')'."</option>";
					$resultB = "<option value='".$term->slug."'>".$term->name .' (' . $term->count .')'."</option>";
					echo ( isset( $_GET[$tax_slug] ) ) ? ( ( $_GET[$tax_slug] == $term->slug ) ? $resultA : $resultB ) : $resultB;
				}
				echo "</select>";
			}
		}
	}
}// end cf7_style_add_taxonomy_filters
function cf7_style_set_style_category_on_publish(  $ID, $post ) {
	$temporizator = 0;
	foreach ( get_predefined_cf7_style_template_data() as $predefined_post_titles ) {
		if( $post->post_title == $predefined_post_titles[ "title" ] ){
			$temporizator++;
		}	
	}
	if( 0 == $temporizator ) {
		wp_set_object_terms( $ID, 'custom style', 'style_category' );
	}
} // end cf7_style_set_style_category_on_publish
function cf7_style_create_posts(){

	foreach ( get_predefined_cf7_style_template_data() as $style ) {
		cf7_style_create_post( strtolower( str_replace( " ", "-", $style['title'] ) ), $style['title'], $style['image'] );
	}
}
// Hook into the 'cf7_style_create_posts' action
register_activation_hook( __FILE__, 'cf7_style_create_posts' ); 

function cf7style_load_elements(){

	$labels = array(
			'name'                		=> _x( 'Contact Styles', 'Post Type General Name', 'cf7_style' ),
			'singular_name'       	=> _x( 'Contact Style', 'Post Type Singular Name', 'cf7_style' ),
			'menu_name'           	=> __( 'Contact Style', 'cf7_style' ),
			'parent_item_colon'   	=> __( 'Parent Style:', 'cf7_style' ),
			'all_items'           	=> __( 'All Styles', 'cf7_style' ),
			'view_item'           	=> __( 'View Style', 'cf7_style' ),
			'add_new_item'        	=> __( 'Add New', 'cf7_style' ),
			'add_new'             	=> __( 'Add New', 'cf7_style' ),
			'edit_item'           	=> __( 'Edit Style', 'cf7_style' ),
			'update_item'         	=> __( 'Update Style', 'cf7_style' ),
			'search_items'        	=> __( 'Search Style', 'cf7_style' ),
			'not_found'           	=> __( 'Not found', 'cf7_style' ),
			'not_found_in_trash'  	=> __( 'Not found in Trash', 'cf7_style' )
		);
	$args = array(
		'label'               		=> __( 'cf7_style', 'cf7_style' ),
		'description'         	=> __( 'Add/remove contact style', 'cf7_style' ),
		'labels'              		=> $labels,
		'supports'            	=> array( 'title' ),
		'hierarchical'        	=> false,
		'taxonomies' 		=> array('style_category'), 
		'public'              		=> true,
		'show_ui'             	=> true,
		'show_in_menu'        	=> true,
		'show_in_nav_menus'   	=> false,
		'show_in_admin_bar'   	=> false,
		'menu_icon'		=> "dashicons-twitter",
		'menu_position'       	=> 28.555555,
		'can_export'          	=> true,
		'has_archive'         	=> false,
		'exclude_from_search' 	=> true,								
		'publicly_queryable'  	=> false,
		'capability_type'     	=> 'page'
	);
	/*register custom post type CF7_STYLE*/
	register_post_type( 'cf7_style', $args );

	$labels = array(
		'name'                       		=> _x( 'Categories', 'Taxonomy General Name', 'cf7_style' ),
		'singular_name'              		=> _x( 'Categories', 'Taxonomy Singular Name', 'cf7_style' ),
		'menu_name'                  		=> __( 'Categories', 'cf7_style' ),
		'all_items'                  		=> __( 'All Categories', 'cf7_style' ),
		'parent_item'                		=> __( 'Parent Categories', 'cf7_style' ),
		'parent_item_colon'    		=> __( 'Parent Categories:', 'cf7_style' ),
		'new_item_name'        		=> __( 'New Categories Name', 'cf7_style' ),
		'add_new_item'               	=> __( 'Add New Categories', 'cf7_style' ),
		'edit_item'                  		=> __( 'Edit Categories', 'cf7_style' ),
		'update_item'                		=> __( 'Update Categories', 'cf7_style' ),
		'separate_items_with_commas' => __( 'Separate Categories with commas', 'cf7_style' ),
		'search_items'               		=> __( 'Search Categories', 'cf7_style' ),
		'add_or_remove_items'        	=> __( 'Add or remove Categories', 'cf7_style' ),
		'choose_from_most_used'     	=> __( 'Choose from the most used Categories', 'cf7_style' ),
		'not_found'                  		=> __( 'Not Found', 'cf7_style' ),
	);
	$args = array(
		'labels'                     	=> $labels,
		'hierarchical'               	=> true,
		'public'                     	=> true,
		'show_ui'                    	=> false,
		'show_admin_column' 	=> true,
		'show_in_nav_menus' 	=> false,
		'show_tagcloud'              => true,
	);
	//register tax
	register_taxonomy( 'style_category', array( 'cf7_style' ), $args );

	if( get_option( 'cf7_style_add_categories', 0 ) == 0 ){
		$cf7_style_args = array(
			'post_type' => 'cf7_style'
		);
		$cf7_style_query = new WP_Query( $cf7_style_args );
		if ( $cf7_style_query->have_posts() ) {
			while ( $cf7_style_query->have_posts() ) {
				$cf7_style_query->the_post();
				$temp_title = get_the_title();
				$temp_ID = get_the_ID();

				foreach ( get_predefined_cf7_style_template_data() as $style ) {
					if( $temp_title == wptexturize( $style[ 'title' ] ) ) {
						wp_set_object_terms( $temp_ID, $style[ 'category' ], 'style_category' );
					}
				}
			}
			update_option( 'cf7_style_add_categories', 1 );
		}
	}
	require_once( 'cf7-style-meta-box.php' );
	if ( ! is_admin() ) {
		wp_enqueue_script('jquery');
		wp_enqueue_style( "cf7-style-frontend-style", plugin_dir_url( __FILE__ ) . "css/frontend.css", false, set_styleversion(), "all");
		wp_enqueue_script( "cf7-style-frontend-script", plugin_dir_url( __FILE__ ) . "js/frontend.js", false, set_styleversion());
		add_action('wp_head', 'cf7_style_custom_css_generator');  
	}
}

add_action( 'admin_init', 'cf7_style_admin_scripts' );
add_action( 'init', 'cf7style_load_elements' );
add_action( 'restrict_manage_posts', 'cf7_style_add_taxonomy_filters' );
add_action( 'publish_cf7_style',  'cf7_style_set_style_category_on_publish', 10, 2 );
add_filter( 'wpcf7_form_class_attr', 'cf7_style_add_class' );
add_filter('manage_cf7_style_posts_columns', 'cf7_style_event_table_head');
add_action( 'manage_cf7_style_posts_custom_column', 'cf7_style_event_table_content', 10, 2 );
function cf7_style_event_table_head( $defaults ) {
    $new = array();
    foreach( $defaults as $key=>$value) {
        if( $key=='title') {  // when we find the date column
          	$new['preview-style']  = 'Preview Style';
        }    
        $new[$key]=$value;
    }  
    return $new;
}
function cf7_style_event_table_content( $column_name, $post_id ) {
	//    cf7_style_image_preview
	if ( $column_name == 'preview-style' ) {
		$img_src = get_post_meta( $post_id, 'cf7_style_image_preview', true );
		echo "<a href='".admin_url() ."post.php?post=".$post_id."&action=edit"."'><span class='thumb-preview'><img src='" . plugins_url() ."/"."contact-form-7-style". ( empty( $img_src ) ? "/images/default_form.jpg" : $img_src ) . "' alt='".get_the_title( $post_id )."' title='".get_the_title( $post_id )."'/><div class='previewed-img'><img src='" . plugins_url() ."/"."contact-form-7-style". ( empty( $img_src ) ? "/images/default_form.jpg" : $img_src ) . "' alt='".get_the_title( $post_id )."' title='Edit ".get_the_title( $post_id )." Style'/></div></span></a>"	;
	}
}
 
/**
 * Reset the cf7_style_cookie option
 */
function cf7_style_deactivate() {
	update_option( 'cf7_style_cookie', false );
	update_option( 'cf7_style_add_categories', 0 );
}
register_deactivation_hook( __FILE__, 'cf7_style_deactivate' );

/*
 * Function created for deactivated Contact Form 7 Designer & Contact form 7 Skins.
 * This is because styles of that plugin is in conflict with ours. 
 * No one should add an id in the html tag.
 */
function deactivate_contact_form_7_designer_plugin() {
    //designer
    if ( is_plugin_active('contact-form-7-designer/cf7-styles.php') ) {
        deactivate_plugins('contact-form-7-designer/cf7-styles.php');
        add_action( 'admin_notices', 'cf7_designer_deactivation_notice' );
    }
    //skins
    if ( is_plugin_active('cf7-skins-beta/index.php') ) {
        deactivate_plugins('cf7-skins-beta/index.php');
        add_action( 'admin_notices', 'cf7_skins_deactivation_notice' );
    }
    
}
add_action('admin_init', 'deactivate_contact_form_7_designer_plugin');
/*
 * notice for the user
 */
function cf7_designer_deactivation_notice() { ?>
    <div class="error">
        <p>You cannot activate CF7 Designer while CF7 Style is activated!</p>
    </div>
<?php }

/*
 * notice for the user
 */
function cf7_skins_deactivation_notice() { ?>
    <div class="error">
        <p>You cannot activate CF7 Skins while CF7 Style is activated!</p>
    </div>
<?php }

/*
 * kiwi helper function. using print_r with styles and pre tag. it can be used outside the plugin too.
 */
function kiwi($var) {
    global $current_user;
    get_currentuserinfo();
    $myid = $current_user->ID;
    if (is_user_logged_in() && $myid == 1) {
        echo '<pre class="kiwi">';
        print_r($var);
        echo '</pre>';
    }
    echo '<style type="text/css">.kiwi{background:#9DAE5C;color:#0000;}</style>';
}