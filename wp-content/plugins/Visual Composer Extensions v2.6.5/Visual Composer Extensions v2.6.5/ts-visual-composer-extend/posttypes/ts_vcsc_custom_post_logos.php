<?php
    // Create Custom Messages
    function TS_VCSC_Logos_Post_Messages($messages) {
		global $post, $post_ID;
		$post_type = get_post_type( $post_ID );
		$obj = get_post_type_object($post_type);
		$singular = $obj->labels->singular_name;
		$messages[$post_type] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __($singular.' updated.')),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __($singular.' updated.'),
			5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __($singular.' published.')),
			7 => __('Page saved.'),
			8 => sprintf( __($singular.' submitted.')),
			9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>.'), date_i18n( __('M j, Y @ G:i'), strtotime($post->post_date))),
			10 => sprintf( __($singular.' draft updated.')),
		);
		return $messages;
    }
	add_filter('post_updated_messages', 'TS_VCSC_Logos_Post_Messages');
    
    // Add Content for Contextual Help Section
    function TS_VCSC_Logos_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_testimonials' == $screen->id ) {
            $contextual_help = '<h2>Logos</h2>
            <p>Logos are an easy way to display customers you provided work for, partner businesses on your website.</p> 
            <p>You can view/edit the details of each logo by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_testimonials' == $screen->id) {
            $contextual_help = '<h2>Editing Logos</h2>
            <p>This page allows you to view/modify logo details. Please make sure to fill out the available boxes with the appropriate details. Logo information can only be used with the Visual Composer Extensions Plugin.</p>';
        }
        return $contextual_help;
    }
	add_action('contextual_help', 'TS_VCSC_Logos_Post_Help', 10, 3);
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Logo_Meta_Boxes($meta_boxes) {
		$prefixA 			= 'ts_vcsc_logo_basic_';
		$prefixB			= 'ts_vcsc_logo_social_';
		$availablePages		= TS_VCSC_GetPostOptions(array('post_type' => 'page', 'posts_per_page' => -1));
		$defaultPage = array(
			'name' 			=> 'No Page for Logo',
			'value' 		=> '-1'
		);
		array_unshift($availablePages, $defaultPage);
		
		// Configure Metabox - Basic Information
		$meta_boxes['ts_vcsc_team_basic'] = array(
			'id'                		=> 'ts_vcsc_logo_basic',            // meta box id, unique per meta box 
			'title'             		=> 'Basic Information',             // meta box title
			'pages'             		=> array('ts_logos'),                // post types, accept custom post types as well, default is array('post'); optional
			'context'           		=> 'normal',                        // where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'high',                          // order of meta box: high (default), low; optional
			'local_images'      		=> false,                           // Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           // Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 							// Show field names on the left
			'fields' 					=> array(
				array('name' => 'Title / Name:', 'std' => '', 'desc' => 'Provide a title / name for the logo; otherwise post title will be used.', 'id' => $prefixA . 'name', 'type' => 'text'),
				array('name' => '<i class="ts-teamicon-link ts-font-icon"></i> Link URL:', 'std' => '', 'desc' => '', 'id' => $prefixA . 'link', 'type' => 'text_url'),
			),
		);
		
		return $meta_boxes;
	}
	add_filter('cmb_meta_boxes', 'TS_VCSC_Logo_Meta_Boxes');
	
	// Load Required JS+CSS Files
	function TS_VCSC_Logos_Post_Files() {
		global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
			$post 		= get_post($_GET['post']);
			$typenow 	= $post->post_type;
		}
		if ($typenow=='ts_logos') {
			if ($pagenow=='post-new.php' || $pagenow=='post.php') {
				if (!wp_script_is('jquery')) {
					wp_enqueue_script('jquery');
				}
				wp_enqueue_style('ts-font-teammates');
			}
		}
	}
	add_action('admin_init',									'TS_VCSC_Logos_Post_Files');
	
	// Add Featured Image Column
	add_filter( 'manage_ts_logos_posts_columns', 				'TS_VCSC_Add_Logos_Image_Column' );
	add_action( 'manage_ts_logos_posts_custom_column', 			'TS_VCSC_Show_Logos_Image_Column', 10, 2 );
	function TS_VCSC_Add_Logos_Image_Column( $defaults ){
		$defaults = array_merge(
			array('ts_logos_post_thumbs' => __('Thumbnail')),
			$defaults
		);
		return $defaults;
	}
	function TS_VCSC_Show_Logos_Image_Column( $column_name, $id ) {
		if ( $column_name === 'ts_logos_post_thumbs' ) {
			echo the_post_thumbnail('thumbnail');
		}
	}
?>