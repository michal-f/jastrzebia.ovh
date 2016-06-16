<?php
    // Create Custom Messages
    function TS_VCSC_Team_Post_Messages($messages) {
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
	add_filter('post_updated_messages', 'TS_VCSC_Team_Post_Messages');
	
    // Add Content for Contextual Help Section
    function TS_VCSC_Team_Post_Help( $contextual_help, $screen_id, $screen ) { 
        if ( 'edit-ts_team' == $screen->id ) {
            $contextual_help = '<h2>Team Members</h2>
            <p>Team Members show the details and contact information for staff or group members that you want to provide to your visitors.</p> 
            <p>You can view/edit the details of each team member by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';
        } else if ('ts_team' == $screen->id) {
            $contextual_help = '<h2>Editing Team Members</h2>
            <p>This page allows you to view/modify team member details. Please make sure to fill out the available boxes with the appropriate details. Team Member information can only be used with the Visual Composer Extensions Plugin.</p>';
        }
        return $contextual_help;
    }
	add_action('contextual_help', 'TS_VCSC_Team_Post_Help', 10, 3);
	
	// Add Custom Metaboxes to Post Type
	function TS_VCSC_Team_Meta_Boxes($meta_boxes) {
		// Define Available Button Types
		$TS_VCSC_Button_Types = array(
			// Default Color Buttons
			'ts-button-3d'													=> 'Standard / 3D - Square',
			'ts-button-3d ts-button-rounded'								=> 'Standard / 3D - Rounded',
			'ts-button-3d ts-button-pill'									=> 'Standard / 3D - Pill',
			'ts-button-default'												=> 'Standard / Default - Square',
			'ts-button-default glow'										=> 'Standard / Default - Square (Glow)',
			'ts-button-rounded ts-button-default'							=> 'Standard / Default - Rounded',
			'ts-button-rounded ts-button-default glow'						=> 'Standard / Default - Rounded (Glow)',
			'ts-button-pill ts-button-default'								=> 'Standard / Default - Pill',
			'ts-button-pill ts-button-default glow'							=> 'Standard / Default - Pill (Glow)',
			'ts-button-flat'												=> 'Standard / Flat - Square',
			'ts-button-flat glow'											=> 'Standard / Flat - Square (Glow)',
			'ts-button-rounded ts-button-flat'								=> 'Standard / Flat - Rounded',
			'ts-button-rounded ts-button-flat glow'							=> 'Standard / Flat - Rounded (Glow)',
			'ts-button-pill ts-button-flat'									=> 'Standard / Flat - Pill',
			'ts-button-pill ts-button-flat glow'							=> 'Standard / Flat - Pill (Glow)',
			// Primary Color Buttons
			'ts-button-3d-primary'											=> 'Primary / 3D - Square',
			'ts-button-3d-primary ts-button-rounded'						=> 'Primary / 3D - Rounded',
			'ts-button-3d-primary ts-button-pill'							=> 'Primary / 3D - Pill',
			'ts-button-default ts-button-primary'							=> 'Primary / Default - Square',
			'ts-button-default glow ts-button-primary'						=> 'Primary / Default - Square (Glow)',
			'ts-button-rounded-primary ts-button-default'					=> 'Primary / Default - Rounded',
			'ts-button-rounded-primary ts-button-default'					=> 'Primary / Default - Rounded (Glow)',
			'ts-button-pill ts-button-primary'								=> 'Primary / Default - Pill',
			'ts-button-pill ts-button-primary glow'							=> 'Primary / Default - Pill (Glow)',
			'ts-button-flat-primary'										=> 'Primary / Flat - Square',
			'ts-button-flat-primary glow'									=> 'Primary / Flat - Square (Glow)',
			'ts-button-rounded ts-button-flat-primary'						=> 'Primary / Flat - Rounded',
			'ts-button-rounded ts-button-flat-primary glow'					=> 'Primary / Flat - Rounded (Glow)',
			'ts-button-pill ts-button-flat-primary'							=> 'Primary / Flat - Pill',
			'ts-button-pill ts-button-flat-primary glow'					=> 'Primary / Flat - Pill (Glow)',
			// Action Color Buttons
			'ts-button-3d-action'											=> 'Action / 3D - Square',
			'ts-button-3d-action ts-button-rounded'							=> 'Action / 3D - Rounded',
			'ts-button-3d-action ts-button-pill'							=> 'Action / 3D - Pill',
			'ts-button-default ts-button-action'							=> 'Action / Default - Square',
			'ts-button-default glow ts-button-action'						=> 'Action / Default - Square (Glow)',
			'ts-button-rounded ts-button-default ts-button-action'			=> 'Action / Default - Rounded',
			'ts-button-rounded ts-button-default glow ts-button-action'		=> 'Action / Default - Rounded (Glow)',
			'ts-button-pill ts-button-default ts-button-action'				=> 'Action / Default - Pill',
			'ts-button-pill ts-button-default glow ts-button-action'		=> 'Action / Default - Pill (Glow)',
			'ts-button-flat-action'											=> 'Action / Flat - Square',
			'ts-button-flat-action glow'									=> 'Action / Flat - Square (Glow)',
			'ts-button-rounded ts-button-flat-action'						=> 'Action / Flat - Rounded',
			'ts-button-rounded ts-button-flat-action glow'					=> 'Action / Flat - Rounded (Glow)',
			'ts-button-pill ts-button-flat-action'							=> 'Action / Flat - Pill',
			'ts-button-pill ts-button-flat-action glow'						=> 'Action / Flat - Pill (Glow)',
			// Highlight Color Buttons
			'ts-button-3d-highlight'										=> 'Highlight / 3D - Square',
			'ts-button-3d-highlight ts-button-rounded'						=> 'Highlight / 3D - Rounded',
			'ts-button-3d-highlight ts-button-pill'							=> 'Highlight / 3D - Pill',
			'ts-button-default ts-button-highlight'							=> 'Highlight / Default - Square',
			'ts-button-default glow ts-button-highlight'					=> 'Highlight / Default - Square (Glow)',
			'ts-button-rounded ts-button-default ts-button-highlight'		=> 'Highlight / Default - Rounded',
			'ts-button-rounded ts-button-default glow ts-button-highlight'	=> 'Highlight / Default - Rounded (Glow)',
			'ts-button-pill ts-button-default ts-button-highlight'			=> 'Highlight / Default - Pill',
			'ts-button-pill ts-button-default glow ts-button-highlight'		=> 'Highlight / Default - Pill (Glow)',
			'ts-button-flat-highlight'										=> 'Highlight / Flat - Square',
			'ts-button-flat-highlight glow'									=> 'Highlight / Flat - Square (Glow)',
			'ts-button-rounded ts-button-flat-highlight'					=> 'Highlight / Flat - Rounded',
			'ts-button-rounded ts-button-flat-highlight glow'				=> 'Highlight / Flat - Rounded (Glow)',
			'ts-button-pill ts-button-flat-highlight'						=> 'Highlight / Flat - Pill',
			'ts-button-pill ts-button-flat-highlight glow'					=> 'Highlight / Flat - Pill (Glow)',
			// Caution Color Buttons
			'ts-button-3d-caution'											=> 'Caution / 3D - Square',
			'ts-button-3d-caution ts-button-rounded'						=> 'Caution / 3D - Rounded',
			'ts-button-3d-caution ts-button-pill'							=> 'Caution / 3D - Pill',
			'ts-button-default ts-button-caution'							=> 'Caution / Default - Square',
			'ts-button-default glow ts-button-caution'						=> 'Caution / Default - Square (Glow)',
			'ts-button-rounded ts-button-default ts-button-caution'			=> 'Caution / Default - Rounded',
			'ts-button-rounded ts-button-default glow ts-button-caution'	=> 'Caution / Default - Rounded (Glow)',
			'ts-button-pill ts-button-default ts-button-caution'			=> 'Caution / Default - Pill',
			'ts-button-pill ts-button-default glow ts-button-caution'		=> 'Caution / Default - Pill (Glow)',
			'ts-button-flat-caution'										=> 'Caution / Flat - Square',
			'ts-button-flat-caution glow'									=> 'Caution / Flat - Square (Glow)',
			'ts-button-rounded ts-button-flat-caution'						=> 'Caution / Flat - Rounded',
			'ts-button-rounded ts-button-flat-caution glow'					=> 'Caution / Flat - Rounded (Glow)',
			'ts-button-pill ts-button-flat-caution'							=> 'Caution / Flat - Pill',
			'ts-button-pill ts-button-flat-caution glow'					=> 'Caution / Flat - Pill (Glow)',
			// Royal Color Buttons
			'ts-button-3d-royal'											=> 'Royal / 3D - Square',
			'ts-button-3d-royal ts-button-rounded'							=> 'Royal / 3D - Rounded',
			'ts-button-3d-royal ts-button-pill'								=> 'Royal / 3D - Pill',
			'ts-button-default ts-button-royal'								=> 'Royal / Default - Square',
			'ts-button-default glow ts-button-royal'						=> 'Royal / Default - Square (Glow)',
			'ts-button-rounded ts-button-default ts-button-royal'			=> 'Royal / Default - Rounded',
			'ts-button-rounded ts-button-default glow ts-button-royal'		=> 'Royal / Default - Rounded (Glow)',
			'ts-button-pill ts-button-default ts-button-royal'				=> 'Royal / Default - Pill',
			'ts-button-pill ts-button-default glow ts-button-royal'			=> 'Royal / Default - Pill (Glow)',
			'ts-button-flat-royal'											=> 'Royal / Flat - Square',
			'ts-button-flat-royal glow'										=> 'Royal / Flat - Square (Glow)',
			'ts-button-rounded ts-button-flat-royal'						=> 'Royal / Flat - Rounded',
			'ts-button-rounded ts-button-flat-royal glow'					=> 'Royal / Flat - Rounded (Glow)',
			'ts-button-pill ts-button-flat-royal'							=> 'Royal / Flat - Pill',
			'ts-button-pill ts-button-flat-royal glow'						=> 'Royal / Flat - Pill (Glow)',
		);

		$prefixA 			= 'ts_vcsc_team_basic_';
		$prefixB 			= 'ts_vcsc_team_contact_';
		$prefixC			= 'ts_vcsc_team_social_';
		$prefixD			= 'ts_vcsc_team_skills_';
		$prefixE			= 'ts_vcsc_team_opening_';
		$availablePages		= TS_VCSC_GetPostOptions(array('post_type' => 'page', 'posts_per_page' => -1));
		$defaultPage = array(
			'name' 			=> 'External Page Teammate',
			'value' 		=> 'external'
		);
		array_unshift($availablePages, $defaultPage);
		$defaultPage = array(
			'name' 			=> 'No Page for Teammate',
			'value' 		=> '-1'
		);
		array_unshift($availablePages, $defaultPage);

		// Configure Metabox - Basic Information
		$meta_boxes['ts_vcsc_team_contact'] = array(
			'id'                		=> 'ts_vcsc_team_contact',          // meta box id, unique per meta box 
			'title'             		=> 'Basic Information',             // meta box title
			'pages'             		=> array('ts_team'),                // post types, accept custom post types as well, default is array('post'); optional
			'context'           		=> 'normal',                        // where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'high',                          // order of meta box: high (default), low; optional
			'local_images'      		=> false,                           // Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           // Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 							// Show field names on the left
			'fields' 					=> array(
				array('name' => 'Position:', 'std' => '', 'desc' => 'Provide some information about the team members position in your company or group.', 'id' => $prefixA . 'position', 'type' => 'text'),
				array('name' => '<i class="ts-teamicon-email3 ts-font-icon"></i> Email Address:', 'std' => '', 'desc' => '', 'id' => $prefixB . 'email', 'type' => 'text_email'),
				array('name' => '<i class="ts-teamicon-phone2 ts-font-icon"></i> Phone Number:', 'std' => '', 'desc' => '', 'id' => $prefixB . 'phone', 'type' => 'text_medium'),
				array('name' => '<i class="ts-teamicon-mobile ts-font-icon"></i> Cell Number:', 'std' => '', 'desc' => '', 'id' => $prefixB . 'cell', 'type' => 'text_medium'),
				array('name' => '<i class="ts-teamicon-portfolio ts-font-icon"></i> Portfolio URL:', 'std' => '', 'desc' => '', 'id' => $prefixB . 'portfolio', 'type' => 'text_url'),
				array('name' => 'Label for Portfolio URL:', 'std' => '', 'desc' => 'If left empty, the actual URL to the portfolio site will be shown.', 'id' => $prefixB . 'portfoliolabel', 'type' => 'text_medium'),
				array('name' => '<i class="ts-teamicon-link ts-font-icon"></i> Personal URL:', 'std' => '', 'desc' => '', 'id' => $prefixB . 'other', 'type' => 'text_url'),
				array('name' => 'Label for Personal URL:', 'std' => '', 'desc' => 'If left empty, the actual URL to the personal site will be shown.', 'id' => $prefixB . 'otherlabel', 'type' => 'text_medium'),
				array('name' => '<i class="ts-teamicon-skype ts-font-icon"></i> Skype User Name:', 'std' => '', 'desc' => '', 'id' => $prefixB . 'skype', 'type' => 'text_medium'),
			),
		);
		
		// Configure Metabox - Opening / Contact Hours
		$meta_boxes['ts_vcsc_team_opening'] = array(
			'id'                		=> 'ts_vcsc_team_opening',	// meta box id, unique per meta box 
			'title'             		=> 'Business / Opening Hours / Note',	// meta box title
			'pages'             		=> array('ts_team'),                	// post types, accept custom post types as well, default is array('post'); optional
			'context'           		=> 'normal',                        	// where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'high',                          	// order of meta box: high (default), low; optional
			'local_images'      		=> false,                           	// Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           	// Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 								// Show field names on the left
			'fields' 					=> array(
				array('name' => 'Use this section to provide additional information, such as business / opening hours, or other optional information.', 'desc' => 'The matching elements in Visual Composer will allow you to show or hide this section individually.', 'type' => 'title', 'id' => $prefixA . 'title'),
				array(
					'name'    	=> 'Icon:',
					'desc'    	=> 'Select the icon that should be shown alongside the header.',
					'id'      	=> $prefixD . 'symbol',
					'type'    	=> 'radio_inline',
					'std' 		=> 'clock1',
					'options' 	=> array(
						'clock1' 		=> '<i class="ts-teamicon-clock1 ts-font-icon"></i> ' . 'Clock',
						'calendar1'   	=> '<i class="ts-teamicon-calendar1 ts-font-icon"></i> ' . 'Calendar',
						'info1'     	=> '<i class="ts-teamicon-info1 ts-font-icon"></i> ' . 'Info',
						'location1'		=> '<i class="ts-teamicon-location1 ts-font-icon"></i> ' . 'Pin',
						'none'     		=> 'None',
					),
				),
				array('name' => 'Icon Color:', 'desc' => '', 'id' => $prefixD . 'symbolcolor', 'type' => 'colorpicker', 'default' => '#666666'),
				array('name' => 'Header:', 'std' => 'Business Hours', 'desc' => 'Enter a header that will be shown above the custom content you will provide below.', 'id' => $prefixD . 'header', 'type' => 'text_medium'),
				array('name' => 'Information:', 'std' => '', 'desc' => 'You can use any text and basic HTML code; create line breaks via "Enter" button or by using the appropriate HTML code.', 'id' => $prefixD . 'opening', 'type' => 'textarea_code'),
			),
		);
		
		// Configure Metabox - Dedicated Member Page
		$meta_boxes['ts_vcsc_team_page'] = array(
			'id'                		=> 'ts_vcsc_team_page',            	// meta box id, unique per meta box 
			'title'             		=> 'Link to Dedicated Page',		// meta box title
			'pages'             		=> array('ts_team'),                // post types, accept custom post types as well, default is array('post'); optional
			'context'           		=> 'normal',                        // where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'high',                          // order of meta box: high (default), low; optional
			'local_images'      		=> false,                           // Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           // Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 							// Show field names on the left
			'fields' 					=> array(
				array('name' => 'When used in a slider, you might not want to show all data in the slider; so provide a link (button) to a page that shows the full profile.', 'desc' => 'You can select whether to show or hide the link button in the individual elements settings later.', 'type' => 'title', 'id' => $prefixA . 'title'),
				array('name' => 'Dedicated Page:', 'desc' => 'If existing, select a page that is dedicated to this particular team member.', 'id' => $prefixA . 'dedicatedpage', 'type' => 'select', 'options' => $availablePages, 'std' => '-1'),
				array('name' => '<i class="ts-teamicon-link ts-font-icon"></i> External URL:', 'std' => '', 'desc' => '', 'id' => $prefixA . 'dedicatedlink', 'type' => 'text_url'),
				array('name' => 'Open in New Tab/Window:', 'std' => '', 'desc' => '', 'id'   => $prefixA . 'dedicatedtarget', 'type' => 'checkbox',),
				array(
					'name'    	=> 'Button Icon:',
					'desc'    	=> 'Select the icon that should be shown alongside the button label.',
					'id'      	=> $prefixA . 'dedicatedicon',
					'type'    	=> 'radio_inline',
					'std' 		=> 'eye2',
					'options' 	=> array(
						'eye2' 			=> '<i class="ts-teamicon-eye2 ts-font-icon"></i> ' . 'Eye 1',
						'eye5' 			=> '<i class="ts-teamicon-eye5 ts-font-icon"></i> ' . 'Eye 2',
						'eye1' 			=> '<i class="ts-teamicon-eye1 ts-font-icon"></i> ' . 'Eye 3',
						'eye3' 			=> '<i class="ts-teamicon-eye3 ts-font-icon"></i> ' . 'Eye 4',
						'info1'   		=> '<i class="ts-teamicon-info1 ts-font-icon"></i> ' . 'Info 1',
						'info4'   		=> '<i class="ts-teamicon-info4 ts-font-icon"></i> ' . 'Info 2',
						'link'   		=> '<i class="ts-teamicon-link ts-font-icon"></i> ' . 'Link 1',
						'link5'   		=> '<i class="ts-teamicon-link5 ts-font-icon"></i> ' . 'Link 2',
						'none'     		=> 'None',
					),
				),
				array('name' => 'Icon Color:', 'desc' => '', 'id' => $prefixA . 'dedicatedcolor', 'type' => 'colorpicker', 'default' => '#666666'),
				array('name' => 'Button Label:', 'std' => 'View Teammate', 'desc' => '', 'id' => $prefixA . 'dedicatedlabel', 'type' => 'text_medium'),
				array('name' => 'Button Tooltip:', 'std' => '', 'desc' => '', 'id' => $prefixA . 'dedicatedtooltip', 'type' => 'text'),
				array('name' => 'Button Type:', 'std' => array('ts-button-3d'), 'desc' => '', 'id' => $prefixA . 'dedicatedtype', 'type' => 'select', 'options' => $TS_VCSC_Button_Types,),
			),
		);
		
		// Configure Metabox - File Information
		$meta_boxes['ts_vcsc_team_file'] = array(
			'id'                		=> 'ts_vcsc_team_file',            	// meta box id, unique per meta box 
			'title'             		=> 'File Attachment',				// meta box title
			'pages'             		=> array('ts_team'),                // post types, accept custom post types as well, default is array('post'); optional
			'context'           		=> 'normal',                        // where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'high',                          // order of meta box: high (default), low; optional
			'local_images'      		=> false,                           // Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           // Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 							// Show field names on the left
			'fields' 					=> array(
				array('name' => 'Attachment', 'desc' => 'Attach a file, including information such as a resume, for your viewers to download.', 'id' => $prefixA . 'buttonfile', 'type' => 'file', 'allow' => array('url', 'attachment')),
				array(
					'name'    	=> 'Icon:',
					'desc'    	=> 'Select the icon that should be shown alongside the button label.',
					'id'      	=> $prefixA . 'buttonicon',
					'type'    	=> 'radio_inline',
					'std' 		=> 'download3',
					'options' 	=> array(
						'download3'		=> '<i class="ts-teamicon-download3 ts-font-icon"></i> ' . 'Download 1',
						'download4'		=> '<i class="ts-teamicon-download4 ts-font-icon"></i> ' . 'Download 2',
						'download5'		=> '<i class="ts-teamicon-download5 ts-font-icon"></i> ' . 'Download 3',
						'download7'		=> '<i class="ts-teamicon-download7 ts-font-icon"></i> ' . 'Download 4',
						'file4' 		=> '<i class="ts-teamicon-file4 ts-font-icon"></i> ' . 'File 1',
						'file14'   		=> '<i class="ts-teamicon-file14 ts-font-icon"></i> ' . 'File 2',	
						'link'   		=> '<i class="ts-teamicon-link ts-font-icon"></i> ' . 'Link 1',
						'link5'   		=> '<i class="ts-teamicon-link5 ts-font-icon"></i> ' . 'Link 2',
						'none'     		=> 'None',
					),
				),
				array('name' => 'Icon Color:', 'desc' => '', 'id' => $prefixA . 'buttoncolor', 'type' => 'colorpicker', 'default' => '#666666'),
				array('name' => 'Button Label:', 'std' => 'Download File', 'desc' => '', 'id' => $prefixA . 'buttonlabel', 'type' => 'text_medium'),
				array('name' => 'Button Tooltip:', 'std' => '', 'desc' => '', 'id' => $prefixA . 'buttontooltip', 'type' => 'text'),
				array('name' => 'Button Type:', 'std' => array('ts-button-3d'), 'desc' => '', 'id' => $prefixA . 'buttontype', 'type' => 'select', 'options' => $TS_VCSC_Button_Types,),
			),
		);
		
		// Configure Metabox - Social Networks
		$meta_boxes['ts_vcsc_team_social'] = array(
			'id'                		=> 'ts_vcsc_team_social',           // meta box id, unique per meta box 
			'title'             		=> 'Social Networks',				// meta box title
			'pages'             		=> array('ts_team'),                // post types, accept custom post types as well, default is array('post'); optional
			'context'           		=> 'normal',                        // where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'high',                          // order of meta box: high (default), low; optional
			'local_images'      		=> false,                           // Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           // Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 							// Show field names on the left
			'fields' 					=> array(
				array('name' => '<i class="ts-teamicon-facebook1 ts-font-icon"></i> Facebook URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'facebook', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-googleplus1 ts-font-icon"></i> Google+ URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'google', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-twitter1 ts-font-icon"></i> Twitter URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'twitter', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-linkedin ts-font-icon"></i> Linkedin URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'linkedin', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-xing3 ts-font-icon"></i> Xing URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'xing', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-envato ts-font-icon"></i> Envato URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'envato', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-rss1 ts-font-icon"></i> RSS URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'rss', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-forrst1 ts-font-icon"></i> Forrst URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'forrst', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-flickr3 ts-font-icon"></i> Flickr URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'flickr', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-instagram ts-font-icon"></i> Instagram URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'instagram', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-picasa1 ts-font-icon"></i> Picasa URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'picasa', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-pinterest1 ts-font-icon"></i> Pinterest URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'pinterest', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-vimeo1 ts-font-icon"></i> Vimeo URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'vimeo', 'type' => 'text_url'),
				array('name' => '<i class="ts-teamicon-youtube1 ts-font-icon"></i> Youtube URL:', 'std' => '', 'desc' => '', 'id' => $prefixC . 'youtube', 'type' => 'text_url'),
			),
		);
		
		// Configure Metabox - Skills
		$meta_boxes['ts_vcsc_team_skills'] = array(
			'id'         				=> 'ts_vcsc_team_skills',
			'title'      				=> 'Skill Sets',
			'pages'      				=> array('ts_team',),
			'context'           		=> 'normal',                        // where the meta box appear: normal (default), advanced, side; optional
			'priority'          		=> 'low',                          	// order of meta box: high (default), low; optional
			'local_images'      		=> false,                           // Use local or hosted images (meta box images for add/remove)
			'use_with_theme'    		=> false,                           // Change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			'show_names' 				=> true, 							// Show field names on the left
			'fields'     				=> array(
				array('name' => 'Add as many skill sets as you need for this teammate by using the "Add Another Skill" button.', 'desc' => 'Skills can also be re-ordered or removed by using the appropriate buttons.', 'type' => 'title', 'id' => $prefixD . 'title'),
				array(
					'id'          		=> $prefixD . 'skillset',
					'type'        		=> 'group',
					'description' 		=> '',
					'options'     		=> array(
						'add_button'    => 'Add Another Skill',
						'remove_button' => 'Remove Skill',
						'sortable'      => true,
					),
					'fields'      		=> array(
						array('name' 		=> 'Skill Name:', 'id' => 'skillname', 'type' => 'text_medium',),
						array('name' 		=> 'Skill Value in %:', 'id' => 'skillvalue', 'type' => 'text_medium',),
						array('name'    	=> 'Skill Color:', 'desc' => '', 'id' => 'skillcolor', 'type' => 'colorpicker', 'default' => '#00afd1'),
					),
				),
			),
		);
		
		return $meta_boxes;
	}
	add_filter('cmb_meta_boxes', 'TS_VCSC_Team_Meta_Boxes');
	
	// Load Required JS+CSS Files
	function TS_VCSC_Team_Post_Files() {
		global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
			$post 		= get_post($_GET['post']);
			$typenow 	= $post->post_type;
		}
		if ($typenow == 'ts_team') {
			if ($pagenow == 'post-new.php' || $pagenow=='post.php') {
				if (!wp_script_is('jquery')) {
					wp_enqueue_script('jquery');
				}
				wp_enqueue_style('ts-extend-uitotop');
				wp_enqueue_script('ts-extend-uitotop');
				wp_enqueue_script('jquery-easing');
				wp_enqueue_style('ts-font-teammates');
				wp_enqueue_style('ts-extend-select2');
				wp_enqueue_script('ts-extend-select2');
				wp_enqueue_style('ts-extend-posttypes');
				wp_enqueue_script('ts-extend-posttypes');
			}
		}
	}
	add_action('admin_init',									'TS_VCSC_Team_Post_Files');
	
	// Add Featured Image Column
	add_filter( 'manage_ts_team_posts_columns', 				'TS_VCSC_Add_Team_Image_Column' );
	add_action( 'manage_ts_team_posts_custom_column', 			'TS_VCSC_Show_Team_Image_Column', 10, 2 );
	function TS_VCSC_Add_Team_Image_Column( $defaults ){
		$defaults = array_merge(
			array('ts_team_post_thumbs' => __('Thumbnail')),
			$defaults
		);
		return $defaults;
	}
	function TS_VCSC_Show_Team_Image_Column( $column_name, $id ) {
		if ( $column_name === 'ts_team_post_thumbs' ) {
			echo the_post_thumbnail('thumbnail');
		}
	}
?>