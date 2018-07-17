<?php
if (!class_exists('TS_Skillsets')){
	class TS_Skillsets {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                                  array($this, 'TS_VCSC_Add_Skillsets_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                    array($this, 'TS_VCSC_Add_Skillsets_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',                                array($this, 'TS_VCSC_Add_Skillsets_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Skill_Sets_Standalone',              array($this, 'TS_VCSC_Skill_Sets_Standalone'));
		}
        
        // Standalone Skillset
        function TS_VCSC_Skill_Sets_Standalone ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();

            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-extend-simptip');
                wp_enqueue_style('ts-extend-animations');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
                'skillset_id'			=> '',
                'custompost_name'		=> '',
                'bar_height'            => 2,
                'bar_stripes'           => 'false',
                'bar_animation'         => 'false',
                'tooltip_style'			=> '',
                'animation_view'		=> '',
                'margin_top'			=> 0,
                'margin_bottom'			=> 0,
                'el_id' 				=> '',
                'el_class'              => '',
				'css'					=> '',
            ), $atts ));

            // Check for Skillset and End Shortcode if Empty
            if (empty($skillset_id)) {
                $output .= '<div style="text-align: justify; font-weight: bold; font-size: 14px; color: red;">Please select a skillset in the element settings!</div>';
                echo $output;
                $myvariable = ob_get_clean();
                return $myvariable;
            }
            
            $output                             = '';
            $bar_classes                        = '';
            
            if ($bar_stripes == "true") {
                $bar_classes                    .= ' striped';
                if ($bar_animation == "true") {
                    $bar_classes                .= ' animated';
                }
            }
        
            if (!empty($el_id)) {
                $skill_block_id					= $el_id;
            } else {
                $skill_block_id					= 'ts-vcsc-skillset-' . mt_rand(999999, 9999999);
            }
        
            if ($animation_view != '') {
                $animation_css              	= TS_VCSC_GetCSSAnimation($animation_view);
            } else {
                $animation_css					= '';
            }
        
            // Retrieve Skillset Post Main Content
            $skill_array						= array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_skillsets',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $skill_query = new WP_Query($args);
            if ($skill_query->have_posts()) {
                foreach($skill_query->posts as $p) {
                    if ($p->ID == $skillset_id) {
                        $skill_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                        );
                        $skill_array[] = $skill_data;
                    }
                }
            }
            wp_reset_postdata();
            
            // Build Skillset Post Main Content
            foreach ($skill_array as $index => $array) {
                $Skill_Title 					= $skill_array[$index]['title'];
                $Skill_ID 						= $skill_array[$index]['id'];
            }
            
            // Retrieve Team Post Meta Content
            $custom_fields 						= get_post_custom($Skill_ID);
            $custom_fields_array				= array();
            foreach ($custom_fields as $field_key => $field_values) {
                if (!isset($field_values[0])) continue;
                if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                if (strpos($field_key, 'ts_vcsc_skillset_') !== false) {
                    $field_key_split 			= explode("_", $field_key);
                    $field_key_length 			= count($field_key_split) - 1;
                    $custom_data = array(
                        'group'					=> $field_key_split[$field_key_length - 1],
                        'name'					=> 'Skill_' . ucfirst($field_key_split[$field_key_length]),
                        'value'					=> $field_values[0],
                    );
                    $custom_fields_array[] = $custom_data;
                }
            }
            foreach ($custom_fields_array as $index => $array) {
                ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
            }
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-post-skills ' . $animation_css . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Skill_Sets_Standalone', $atts);
			} else {
				$css_class	= 'ts-post-skills ' . $animation_css . ' ' . $el_class;
			}
			
            // Build Skillset
            $team_skills 		= '';
            $team_skills_count	= 0;
            if (isset($Skill_Group)) {
                $skill_entries      = get_post_meta( $Skill_ID, 'ts_vcsc_skillset_basic_group', true);
                $skill_background 	= '';
                $team_skills		.= '<div id="' . $skill_block_id . '" class="' . $css_class . '">';
                    foreach ((array) $skill_entries as $key => $entry) {
                        $skill_name = $skill_value = $skill_color = '';
                        if (isset($entry['skillname'])) {
                            $skill_name      = esc_html($entry['skillname']);
                        }
                        if (isset($entry['skillvalue'])) {
                            $skill_value     = esc_html($entry['skillvalue']);
                        }
                        if (isset($entry['skillcolor'])) {
                            $skill_color     = esc_html($entry['skillcolor']);
                        }
                        if ((strlen($skill_name) != 0) && (strlen($skill_value) != 0)) {
                            $team_skills_count++;
                            if ((strlen($skill_color) != 0) && ($skill_color != '#')) {
                                $skill_background = 'background-color: ' . $skill_color . ';';
                            } else {
								$skill_background = 'background-color: #00afd1;';
							}
                            $team_skills .= '<div class="skill-label">' . $skill_name . '<span>(' . $skill_value . '%)</span></div><div class="skill-bar" style="height: ' . $bar_height . 'px;"><div class="level' . $bar_classes . '" data-color="' . $skill_color . '" data-level="' . $skill_value . '%" data-appear-animation-delay="400" style="width: ' . $skill_value . '%; ' . $skill_background . '"></div></div>';
                        }
                    }
                $team_skills		.= '</div>';
            }
    
            // Create Output
            $output = $team_skills;

            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
        // Add Skillset Elements
        function TS_VCSC_Add_Skillsets_Elements() {
            global $VISUAL_COMPOSER_EXTENSIONS;
            // Add Standalone Skillset
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Skillsets", "ts_visual_composer_extend" ),
                    "base"                              => "TS_VCSC_Skill_Sets_Standalone",
                    "icon" 	                            => "icon-wpb-ts_vcsc_skillset_standalone",
                    "class"                             => "",
                    "category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
                    "description"                       => __("Place a skillsets element", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(''),
                    //"admin_enqueue_css"               => array(''),
                    "params"                            => array(
                        // Skillset Selection
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Main Content",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompost",
                            "heading"                   => __( "Skillset", "ts_visual_composer_extend" ),
                            "param_name"                => "skillset_id",
                            "posttype"                  => "ts_skillsets",
                            "posttaxonomy"              => "ts_skillsets_category",
							"taxonomy"              	=> "ts_skillsets_category",
							"postsingle"				=> "Skillset",
							"postplural"				=> "Skillsets",
							"postclass"					=> "skillset",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "hidden_input",
                            "heading"                   => __( "Skillset Name", "ts_visual_composer_extend" ),
                            "param_name"                => "custompost_name",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Bar Height", "ts_visual_composer_extend" ),
                            "param_name"                => "bar_height",
                            "value"                     => "2",
                            "min"                       => "2",
                            "max"                       => "75",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "admin_label"		        => true,
                            "description"               => __( "Define the height for each individual skill bar.", "ts_visual_composer_extend" ),
                        ),
                        array(
                            "type"				        => "switch",
                            "heading"                   => __( "Add Stripes", "ts_visual_composer_extend" ),
                            "param_name"                => "bar_stripes",
                            "value"                     => "false",
                            "on"				        => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"				        => __( 'No', "ts_visual_composer_extend" ),
                            "style"				        => "select",
                            "design"			        => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to add a stripes to the skill bar.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"				        => "switch",
                            "heading"                   => __( "Add Stripes Animation", "ts_visual_composer_extend" ),
                            "param_name"                => "bar_animation",
                            "value"                     => "false",
                            "on"				        => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"				        => __( 'No', "ts_visual_composer_extend" ),
                            "style"				        => "select",
                            "design"			        => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to add an animation to the striped skill bar.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "bar_stripes", 'value' => 'true')
                        ),
                        // Other Skillset Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_4",
                            "value"                     => "Other Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Viewport Animation", "ts_visual_composer_extend" ),
                            "param_name"                => "animation_view",
                            "value"                     => array(
                                "None"                              => "",
                                "Top to Bottom"                     => "top-to-bottom",
                                "Bottom to Top"                     => "bottom-to-top",
                                "Left to Right"                     => "left-to-right",
                                "Right to Left"                     => "right-to-left",
                                "Appear from Center"                => "appear",
                            ),
                            "description"               => __( "Select the viewport animation for the element.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "animations", 'value' => 'true' ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_top",
                            "value"                     => "0",
                            "min"                       => "-50",
                            "max"                       => "500",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_bottom",
                            "value"                     => "0",
                            "min"                       => "-50",
                            "max"                       => "500",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
                            "param_name"                => "el_id",
                            "value"                     => "",
                            "description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Extra Class Name", "ts_visual_composer_extend" ),
                            "param_name"                => "el_class",
                            "value"                     => "",
                            "description"               => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        // Load Custom CSS/JS File
                        array(
                            "type"                      => "load_file",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "el_file",
                            "value"                     => "",
                            "file_type"                 => "js",
                            "file_path"                 => "js/ts-visual-composer-extend-element.min.js",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                    ))
                );
            }
        }
	}
}
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_TS_VCSC_Skill_Sets_Standalone extends WPBakeryShortCode {};
}
// Initialize "TS Skillsets" Class
if (class_exists('TS_Skillsets')) {
	$TS_Skillsets = new TS_Skillsets;
}