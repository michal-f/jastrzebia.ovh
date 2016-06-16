<?php
if (!class_exists('TS_Teampage')){
	class TS_Teampage {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                                  array($this, 'TS_VCSC_Add_Team_Page_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                    array($this, 'TS_VCSC_Add_Team_Page_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',                                array($this, 'TS_VCSC_Add_Team_Page_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Team_Page_Elements',					array($this, 'TS_VCSC_Team_Page_Elements'));
		}
        

        function TS_VCSC_Team_Page_Elements ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();

            wp_enqueue_script('ts-extend-hammer');
            wp_enqueue_script('ts-extend-nacho');
            wp_enqueue_style('ts-extend-nacho');
            wp_enqueue_style('ts-font-teammates');
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-extend-simptip');
                wp_enqueue_style('ts-extend-animations');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
                'team_member'				=> '',
                'custompost_name'			=> '',
				'element'					=> 'image',
				// Section: Image
                'style'						=> 'style1',
                'show_grayscale'        	=> 'true',
				'grayscale_hover'			=> 'true',
				'show_effects'				=> 'true',
                'show_lightbox'        	 	=> 'true',
				// Section: Name / Position
				'name_align'				=> 'left',
				'name_title_size'			=> 18,
				'name_job_size'				=> 16,
				// Section: Contact
				'contact_icon_color'		=> '#000000',
				'contact_icon_size'			=> 14,
				'contact_font_size'			=> 14,
				// Section: Social Networks
                'social_icon_style'			=> 'simple',
				'social_icon_size'			=> 16,
                'social_icon_background'	=> '#f5f5f5',
                'social_icon_frame_color'	=> '#f5f5f5',
                'social_icon_frame_thick'	=> 1,
                'social_icon_margin'		=> 5,
                'social_icon_align'			=> 'left',
                'social_icon_hover'			=> '',
                'tooltip_style'				=> '',
                'tooltip_position'			=> 'ts-simptip-position-top',
				// Section: Optional Information
				'optional_title_align'		=> 'left',
				'optional_text_align'		=> 'left',
				'optional_title_size'		=> 14,
				'optional_font_size'		=> 14,
				// Section: Download
				'button_width'				=> 100,
				'button_align'				=> 'center',
				// Section: Skillsets
                'skills_height'            	=> 2,
                'skills_stripes'           	=> 'false',
                'skills_animation'         	=> 'false',
				// Other Settings
                'margin_top'				=> 0,
                'margin_bottom'				=> 0,
                'el_id' 					=> '',
                'el_class'              	=> '',
				'css'						=> '',
            ), $atts ));
            
            $output = '';
            
            // Check for Teammate and End Shortcode if Empty
            if (empty($team_member)) {
                $output .= '<div style="text-align: justify; font-weight: bold; font-size: 14px; color: red;">Please select a teammate in the element settings!</div>';
                echo $output;
                $myvariable = ob_get_clean();
                return $myvariable;
            }
        
            if (!empty($el_id)) {
                $team_block_id					= $el_id;
            } else {
                $team_block_id					= 'ts-vcsc-teampage-' . $element . '-' . mt_rand(999999, 9999999);
            }
        
            $animation_css						= '';
            
            $team_tooltipclasses				= "ts-simptip-multiline " . $tooltip_style . " " . $tooltip_position;
        
            if ((empty($social_icon_background)) || ($social_icon_style == 'simple')) {
                $icon_frame_style				= '';
            } else {
                $icon_frame_style				= 'background: ' . $social_icon_background . ';';
            }
            
            if ($social_icon_frame_thick > 0) {
                $icon_top_adjust				= 'top: ' . (10 - $social_icon_frame_thick) . 'px;';
            } else {
                $icon_top_adjust				= '';
            }
			
			$icon_frame_size					= 'font-size: ' . $social_icon_size . 'px; line-height: ' . $social_icon_size . 'px; width: ' . $social_icon_size . 'px; height: ' . $social_icon_size . 'px;';
            
            if ($social_icon_style == 'simple') {
                $icon_frame_border				= '';
            } else {
                $icon_frame_border				= ' border: ' . $social_icon_frame_thick . 'px solid ' . $social_icon_frame_color . ';';
            }
            
			$icon_size_adjust					= 'font-size: ' . $contact_icon_size . 'px; line-height: ' . $contact_icon_size . 'px; height: ' . $contact_icon_size . 'px; width: ' . $contact_icon_size . 'px; ';
            $icon_horizontal_adjust				= '';
			$icon_top_adjust					= '';
			
			if ($contact_icon_size > $contact_font_size) {
				$line_height_adjust				= 'line-height: ' . $contact_icon_size . 'px;';
			} else {
				$line_height_adjust				= 'line-height: ' . $contact_font_size . 'px;';
			}
        
            $team_social 						= '';
        
            // Retrieve Team Post Main Content
            $team_array							= array();
            $category_fields 	                = array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_team',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $team_query = new WP_Query($args);
            if ($team_query->have_posts()) {
                foreach($team_query->posts as $p) {
                    if ($p->ID == $team_member) {
                        $team_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                        );
                        $team_array[] = $team_data;
                    }
                }
            }
            wp_reset_postdata();
            
            // Build Team Post Main Content
            foreach ($team_array as $index => $array) {
                $Team_Author					= $team_array[$index]['author'];
                $Team_Name 						= $team_array[$index]['name'];
                $Team_Title 					= $team_array[$index]['title'];
                $Team_ID 						= $team_array[$index]['id'];
                $Team_Content 					= $team_array[$index]['content'];
                $Team_Image						= wp_get_attachment_image_src(get_post_thumbnail_id($Team_ID), 'full');
                if ($Team_Image == false) {
                    $Team_Image          		= TS_VCSC_GetResourceURL('images/Default_person.jpg');
                } else {
                    $Team_Image          		= $Team_Image[0];
                }
            }
            
            // Retrieve Team Post Meta Content
            $custom_fields 						= get_post_custom($Team_ID);
            $custom_fields_array				= array();
            foreach ($custom_fields as $field_key => $field_values) {
                if (!isset($field_values[0])) continue;
                if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                if (strpos($field_key, 'ts_vcsc_team_') !== false) {
                    $field_key_split 			= explode("_", $field_key);
                    $field_key_length 			= count($field_key_split) - 1;
                    $custom_data = array(
                        'group'					=> $field_key_split[$field_key_length - 1],
                        'name'					=> 'Team_' . ucfirst($field_key_split[$field_key_length]),
                        'value'					=> $field_values[0],
                    );
                    $custom_fields_array[] = $custom_data;
                }
            }
            foreach ($custom_fields_array as $index => $array) {
                ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
            }
            if (isset($Team_Position)) {
                $Team_Position 					= $Team_Position;
            } else {
                $Team_Position 					= '';
            }
            if (isset($Team_Buttonlabel)) {
                $Team_Buttonlabel				= $Team_Buttonlabel;
            } else {
                $Team_Buttonlabel				= '';
            }
            
   
            // Build Team Contact Information
            $team_contact			= '';
            $team_contact_count		= 0;
            if ($element == 'contact') {
				$team_contact		= '';
				$team_contact_count	= 0;
				$team_contact		.= '<div class="ts-team-contact">';
					if (isset($Team_Email)) {
						$team_contact_count++;
						if (isset($Team_Emaillabel)) {
							$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-email3 ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i><a target="_blank" class="" href="mailto:' . $Team_Email . '">' . $Team_Emaillabel . '</a></div>';
						} else {
							$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-email3 ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i><a target="_blank" class="" href="mailto:' . $Team_Email . '">' . $Team_Email . '</a></div>';
						}
					}
					if (isset($Team_Phone)) {
						$team_contact_count++;
						$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-phone2 ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i>' . $Team_Phone . '</div>';
					}
					if (isset($Team_Cell)) {
						$team_contact_count++;
						$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-mobile ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i>' . $Team_Cell . '</div>';
					}
					if (isset($Team_Portfolio)) {
						$team_contact_count++;
						if (isset($Team_Portfoliolabel)) {
							$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-portfolio ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Portfolio) . '">' . $Team_Portfoliolabel . '</a></div>';
						} else {
							$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-portfolio ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Portfolio) . '">' . TS_VCSC_makeValidURL($Team_Portfolio) . '</a></div>';
						}
					}
					if (isset($Team_Other)) {
						$team_contact_count++;
						if (isset($Team_Otherlabel)) {
							$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-link ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Other) . '">' . $Team_Otherlabel . '</a></div>';
						} else {
							$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-link ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . 'color: ' . $contact_icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Other) . '">' . TS_VCSC_makeValidURL($Team_Other) . '</a></div>';
						}
					}
					if (isset($Team_Skype)) {
						$team_contact_count++;
						$team_contact .= '<div class="ts-contact-parent" style="font-size: ' . $contact_font_size . 'px; ' . $line_height_adjust . '"><i class="ts-teamicon-skype ts-font-icon ts-teammate-icon" style="' . $icon_size_adjust . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i>' . $Team_Skype . '</div>';
					}
				$team_contact		.= '</div>';
            }
			// Build Opening / Contact Hours
			$team_opening			= '';
			$team_opening_count		= 0;
			if ($element == "optional") {
				$team_opening			= '';
				$team_opening_count		= 0;
				$team_opening		.= '<div class="ts-team-opening-parent">';
					if (isset($Team_Header)) {
						if ($Team_Symbol == "none") {
							$team_opening .= '<div class="ts-team-opening-header" style="text-align: ' . $optional_title_align . '; font-size: ' . $optional_title_size . 'px;">' . $Team_Header . '</div>';
						} else {
							$team_opening .= '<div class="ts-team-opening-header" style="text-align: ' . $optional_title_align . '; font-size: ' . $optional_title_size . 'px;"><i class="ts-teamicon-' . $Team_Symbol . ' ts-font-icon ts-teammate-icon" style="font-size: ' . $optional_title_size . 'px; line-height: ' . $optional_title_size . 'px; width: ' . $optional_title_size . 'px; height: ' . $optional_title_size . 'px;' . (isset($Team_Symbolcolor) ? "color: " . $Team_Symbolcolor . ";" : "") . '"></i>' . $Team_Header . '</div>';
						}
					}
					if ((isset($Team_Opening)) && ($Team_Opening != 'block')) {
						$team_opening_count++;
						$team_opening .= '<div class="ts-team-opening-block" style="text-align: ' . $optional_text_align . '; font-size: ' . $optional_font_size . 'px;">' . $Team_Opening . '</div>';
					}
				$team_opening		.= '</div>';
			}
			// Build Team Social Links
            $team_social 			= '';
            $team_social_count		= 0;
            if ($element == "social") {
				$team_social 		.= '<ul class="ts-teammate-icons ' . $social_icon_style . ' clearFixMe" style="">';
					if (isset($Team_Facebook)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Facebook"><a style="" target="_blank" class="ts-teammate-link facebook ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Facebook) . '"><i class="ts-teamicon-facebook1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Google)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Google+"><a style="" target="_blank" class="ts-teammate-link gplus ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Google) . '"><i class="ts-teamicon-googleplus1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Twitter)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Twitter"><a style="" target="_blank" class="ts-teammate-link twitter ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Twitter) . '"><i class="ts-teamicon-twitter1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Linkedin)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="LinkedIn"><a style="" target="_blank" class="ts-teammate-link linkedin ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Linkedin) . '"><i class="ts-teamicon-linkedin ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Xing)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Xing"><a style="" target="_blank" class="ts-teammate-link xing ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Xing) . '"><i class="ts-teamicon-xing3 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Envato)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Envato"><a style="" target="_blank" class="ts-teammate-link envato ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Envato) . '"><i class="ts-teamicon-envato ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Rss)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="RSS"><a style="" target="_blank" class="ts-teammate-link rss ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Rss) . '"><i class="ts-teamicon-rss1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Forrst)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Forrst"><a style="" target="_blank" class="ts-teammate-link forrst ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Forrst) . '"><i class="ts-teamicon-forrst1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Flickr)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Flickr"><a style="" target="_blank" class="ts-teammate-link flickr ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Flickr) . '"><i class="ts-teamicon-flickr3 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Instagram)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Instagram"><a style="" target="_blank" class="ts-teammate-link instagram ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Instagram) . '"><i class="ts-teamicon-instagram ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Picasa)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Picasa"><a style="" target="_blank" class="ts-teammate-link picasa ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Picasa) . '"><i class="ts-teamicon-picasa1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Pinterest)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Pinterest"><a style="" target="_blank" class="ts-teammate-link pinterest ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Pinterest) . '"><i class="ts-teamicon-pinterest1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Vimeo)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Vimeo"><a style="" target="_blank" class="ts-teammate-link vimeo ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Vimeo) . '"><i class="ts-teamicon-vimeo1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
					if (isset($Team_Youtube)) {
						$team_social_count++;
						$team_social .= '<li class="ts-teammate-icon ' . $social_icon_align . ' ' . $team_tooltipclasses . '" style="margin: ' . $social_icon_margin . 'px; ' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="YouTube"><a style="" target="_blank" class="ts-teammate-link youtube ' . $social_icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Youtube) . '"><i class="ts-teamicon-youtube1 ts-font-icon" style="' . $icon_frame_size . ' ' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
					}
				$team_social 		.= '</ul>';
            }
            
            // Build Team Skills
            $team_skills 			= '';
            $team_skills_count		= 0;
			if ($element == "skillsets") {
				$skills_classes			= '';
				if ($skills_stripes == "true") {
					$skills_classes		.= ' striped';
					if ($skills_animation == "true") {
						$skills_classes	.= ' animated';
					}
				}			
				if (isset($Team_Skillset)) {
					$skill_entries      = get_post_meta( $Team_ID, 'ts_vcsc_team_skills_skillset', true);
					$skill_background 	= '';
					$team_skills		.= '<div class="ts-member-skills">';
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
								}
								$team_skills .= '<div class="skill-label">' . $skill_name . '<span>(' . $skill_value . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $skill_color . '" data-level="' . $skill_value . '%" data-appear-animation-delay="400" style="width: ' . $skill_value . '%; ' . $skill_background . '"></div></div>';
							}
						}
					$team_skills		.= '</div>';
				} else if (!isset($Team_Skillset)) {
					$skill_background 	= '';
					$team_skills		.= '<div class="ts-member-skills">';
						if ((isset($Team_Skillname1)) && (isset($Team_Skillvalue1))) {
							$team_skills_count++;
							if (isset($Team_Skillcolor1)) {
								$skill_background = 'background-color: ' . $Team_Skillcolor1 . ';';
							}
							$team_skills .= '<div class="skill-label">' . $Team_Skillname1 . '<span>(' . $Team_Skillvalue1 . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $Team_Skillcolor1 . '" data-level="' . $Team_Skillvalue1 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue1 . '%; ' . $skill_background . '"></div></div>';
						}
						if ((isset($Team_Skillname2)) && (isset($Team_Skillvalue2))) {
							$team_skills_count++;
							if (isset($Team_Skillcolor2)) {
								$skill_background = 'background-color: ' . $Team_Skillcolor2 . ';';
							}
							$team_skills .= '<div class="skill-label">' . $Team_Skillname2 . '<span>(' . $Team_Skillvalue2 . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $Team_Skillcolor2 . '" data-level="' . $Team_Skillvalue2 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue2 . '%; ' . $skill_background . '"></div></div>';
						}
						if ((isset($Team_Skillname3)) && (isset($Team_Skillvalue3))) {
							$team_skills_count++;
							if (isset($Team_Skillcolor3)) {
								$skill_background = 'background-color: ' . $Team_Skillcolor3 . ';';
							}
							$team_skills .= '<div class="skill-label">' . $Team_Skillname3 . '<span>(' . $Team_Skillvalue3 . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $Team_Skillcolor3 . '" data-level="' . $Team_Skillvalue3 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue3 . '%; ' . $skill_background . '"></div></div>';
						}
						if ((isset($Team_Skillname4)) && (isset($Team_Skillvalue4))) {
							$team_skills_count++;
							if (isset($Team_Skillcolor4)) {
								$skill_background = 'background-color: ' . $Team_Skillcolor4 . ';';
							}
							$team_skills .= '<div class="skill-label">' . $Team_Skillname4 . '<span>(' . $Team_Skillvalue4 . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $Team_Skillcolor4 . '" data-level="' . $Team_Skillvalue4 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue4 . '%; ' . $skill_background . '"></div></div>';
						}
						if ((isset($Team_Skillname5)) && (isset($Team_Skillvalue5))) {
							$team_skills_count++;
							if (isset($Team_Skillcolor5)) {
								$skill_background = 'background-color: ' . $Team_Skillcolor5 . ';';
							}
							$team_skills .= '<div class="skill-label">' . $Team_Skillname5 . '<span>(' . $Team_Skillvalue5 . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $Team_Skillcolor5 . '" data-level="' . $Team_Skillvalue5 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue5 . '%; ' . $skill_background . '"></div></div>';
						}
						if ((isset($Team_Skillname6)) && (isset($Team_Skillvalue6))) {
							$team_skills_count++;
							if (isset($Team_Skillcolor6)) {
								$skill_background = 'background-color: ' . $Team_Skillcolor6 . ';';
							}
							$team_skills .= '<div class="skill-label">' . $Team_Skillname6 . '<span>(' . $Team_Skillvalue6 . '%)</span></div><div class="skill-bar" style="height: ' . $skills_height . 'px;"><div class="level' . $skills_classes . '" data-color="' . $Team_Skillcolor6 . '" data-level="' . $Team_Skillvalue6 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue6 . '%; ' . $skill_background . '"></div></div>';
						}
					$team_skills		.= '</div>';
				}
			}
			// Build Download Button
            $team_download 			= '';
            if ($element == 'download') {
				if ((isset($Team_Buttonfile)) || (isset($Team_Attachment))) {
					if (isset($Team_Buttonfile)) {
						$Team_File          = $Team_Buttonfile;
					} else {
						$Team_Attachment    = get_post_meta($Team_ID, 'ts_vcsc_team_basic_attachment', true);
						$Team_Attachment    = wp_get_attachment_url($Team_Attachment['id']);
						$Team_File          = $Team_Attachment;
					}
					$Team_FileFormat        = pathinfo($Team_File, PATHINFO_EXTENSION);
					if (isset($Team_Buttontype)) {
						$Team_Buttontype 	= $Team_Buttontype;
					} else {
						$Team_Buttontype 	= 'ts-button-3d';
					};
					// Button Width + Position Adjustment
					if ($button_width < 100) {
						$button_style		= 'width: ' . $button_width . '%; margin: 0 auto; display: block;';
						if ($button_align == 'center') {
							$button_style	.= ' float: none;';
						} else if ($button_align == 'left') {
							$button_style	.= ' float: left;';
						} else if ($button_align == 'right') {
							$button_style	.= ' float: right;';
						}
					}					
					if (!empty($Team_File)) {
						$team_download	.= '<div class="ts-teammate-download" style="' . $button_style . '">';
							if (isset($Team_Buttontooltip)) {
								if (((isset($Team_Buttonicon)) && ($Team_Buttonicon == "none")) || (!isset($Team_Buttonicon))) {
									$team_download 	.= '<a class="ts-teammate-file-link ts-button ' . $Team_Buttontype . ' ' . $team_tooltipclasses . '" data-format="' . $Team_FileFormat . '" data-tstooltip="' . $Team_Buttontooltip . '" href="' . $Team_File . '" target="_blank">' . $Team_Buttonlabel . '</a>';
								} else {
									$team_download 	.= '<a class="ts-teammate-file-link ts-button ' . $Team_Buttontype . ' ' . $team_tooltipclasses . '" data-format="' . $Team_FileFormat . '" data-tstooltip="' . $Team_Buttontooltip . '" href="' . $Team_File . '" target="_blank"><i class="ts-teamicon-' . $Team_Buttonicon . ' ts-font-icon ts-teammate-icon" style="' . (isset($Team_Buttoncolor) ? "color: " . $Team_Buttoncolor . ":" : "") . '"></i> ' . $Team_Buttonlabel . '</a>';
								}                        
							} else {
								if (((isset($Team_Buttonicon)) && ($Team_Buttonicon == "none")) || (!isset($Team_Buttonicon))) {
									$team_download 	.= '<a class="ts-teammate-file-link ts-button ' . $Team_Buttontype . '" data-format="' . $Team_FileFormat . '" href="' . $Team_File . '" target="_blank">' . $Team_Buttonlabel . '</a>';
								} else {
									$team_download 	.= '<a class="ts-teammate-file-link ts-button ' . $Team_Buttontype . '" data-format="' . $Team_FileFormat . '" href="' . $Team_File . '" target="_blank"><i class="ts-teamicon-' . $Team_Buttonicon . ' ts-font-icon ts-teammate-icon" style="' . (isset($Team_Buttoncolor) ? "color: " . $Team_Buttoncolor . ";" : "") . '"></i> ' . $Team_Buttonlabel . '</a>';
								}
							}
						$team_download 	.= '</div>';
						if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0) {
							wp_enqueue_style('ts-extend-buttons',                 		TS_VCSC_GetResourceURL('css/jquery.buttons.css'), null, false, 'all');
						}
					}
				}
            }
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-teampage ' . $animation_css . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Team_Page_Element', $atts);
			} else {
				$css_class	= 'ts-teampage ' . $animation_css . ' ' . $el_class;
			}
			
            // Create Output
			if ($element == 'image') {
				// Grayscale Class
				if (($show_grayscale == "true") && ($grayscale_hover == "true")) {
					$grayscale_class	= 'ts-grayscale-hover';
				} else if (($show_grayscale == "true") && ($grayscale_hover == "false")) {
					$grayscale_class	= 'ts-grayscale-default';
				} else {
					$grayscale_class	= 'ts-grayscale-none';
				}
				if ($style == "style1") {
					$output .= '<div id="' . $team_block_id . '" class="ts-teampage-image ts-team1 ' . $css_class . ' ' . $grayscale_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';
						if (!empty($Team_Image)) {
							$output .= '<div class="team-avatar" style="margin: 0 auto;">';
								$output .= '<img src="' . $Team_Image . '" style="max-width: 100%; max-height: 100%;" rel="' . ($show_lightbox == "true" ? "nachoteam" : "") . '" title="' . $Team_Title . ' / ' . $Team_Position . '" alt="" class="' . ($show_lightbox == "true" ? "nch-lightbox" : "") . ' ' . ($show_grayscale == "true" ? "grayscale" : "") . ' ' . ($show_effects == "true" ? "hovereffect" : "") . '">';
							$output .= '</div>';
						}
					$output .= '</div>';
				}
				if ($style == "style2") {
					$output .= '<div id="' . $team_block_id . '" class="ts-teampage-image ts-team2 ' . $css_class . ' ' . $grayscale_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';
						if (!empty($Team_Image)) {
							$output .= '<div class="ts-team2-header" style="margin: 0 auto;">';
								$output .= '<img src="' . $Team_Image . '" rel="' . ($show_lightbox == "true" ? "nachoteam" : "") . '" title="' . $Team_Title . ' / ' . $Team_Position . '" alt="" class="' . ($show_lightbox == "true" ? "nch-lightbox" : "") . ' ' . ($show_grayscale == "true" ? "grayscale" : "") . ' ' . ($show_effects == "true" ? "hovereffect" : "") . '">';
							$output .= '</div>';
						}
					$output .= '</div>';
				}
				if ($style == "style3") {
					$output .= '<div id="' . $team_block_id . '" class="ts-teampage-image ts-team3 ' . $css_class . ' ' . $grayscale_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';
						if (!empty($Team_Image)) {
							$output .= '<img class="ts-team3-person-image ' . ($show_lightbox == "true" ? "nch-lightbox" : "") . ' ' . ($show_grayscale == "true" ? "grayscale" : "") . ' ' . ($show_effects == "true" ? "hovereffect" : "") . '" rel="' . ($show_lightbox == "true" ? "nachoteam" : "") . '" src="' . $Team_Image . '" title="' . $Team_Title . ' / ' . $Team_Position . '" alt="">';
						}			
					$output .= '</div>';
				}
			}
			if ($element == 'name') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-name ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';
					$output .= '<div class="team-user" style="text-align: ' . $name_align . '">';
						if (!empty($Team_Title)) {
							$output .= '<h4 class="team-title" style="font-size: ' . $name_title_size . 'px;">' . $Team_Title . '</h4>';
						}
						if (!empty($Team_Position)) {
							$output .= '<div class="team-job" style="font-size: ' . $name_job_size . 'px;">' . $Team_Position . '</div>';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($element == 'contact') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-contact ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';
					if ($team_contact_count > 0) {
						$output .= $team_contact;
					}
				$output .= '</div>';
			}
			if ($element == 'social') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-social ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';
					$output .= $team_social;
				$output .= '</div>';
			}
			if ($element == 'description') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-description ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';				
					$output .= '<div class="team-information">';
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= '' . wpb_js_remove_wpautop(do_shortcode($Team_Content), true) . '';
						} else {
							$output .= '' . do_shortcode($Team_Content) . '';
						}
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($element == 'optional') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-opening ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';							
					if ($team_opening_count > 0) {
						$output .= $team_opening;
					}
				$output .= '</div>';
			}
			if ($element == 'download') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-download ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';							
					$output .= $team_download;
				$output .= '</div>';
			}
			if ($element == 'skillsets') {
				$output .= '<div id="' . $team_block_id . '" class="ts-teampage-skills ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; padding: 0;">';							
					if ($team_skills_count > 0) {
						$output .= $team_skills;
					}
				$output .= '</div>';
			}
			
			echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }

		// Add Teammate Elements
        function TS_VCSC_Add_Team_Page_Elements() {
            global $VISUAL_COMPOSER_EXTENSIONS;
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name"                           	=> __("TS Teampage Section", "ts_visual_composer_extend"),
                    "base"                           	=> "TS_VCSC_Team_Page_Elements",
                    "class"                          	=> "",
                    "icon"                           	=> "icon-wpb-ts_vcsc_meet_team_teampage",
                    "category"                       	=> __("VC Extensions", "ts_visual_composer_extend"),
                    "description"                    	=> __("Place a section from a teammate", "ts_visual_composer_extend"),
                    "params"                         	=> array(
                        // Teammate Selection
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
							"value"						=> "",
                            "seperator"					=> "Teammate Selection",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompost",
                            "heading"                   => __( "Teammate", "ts_visual_composer_extend" ),
                            "param_name"                => "team_member",
                            "posttype"                  => "ts_team",
                            "posttaxonomy"              => "ts_team_category",
							"taxonomy"              	=> "ts_team_category",
							"postsingle"				=> "Teammate",
							"postplural"				=> "Teammates",
							"postclass"					=> "teammate",
                            "value"                     => "",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "hidden_input",
                            "heading"                   => __( "Member Name", "ts_visual_composer_extend" ),
                            "param_name"                => "custompost_name",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Teammate Section", "ts_visual_composer_extend" ),
                            "param_name"                => "element",
                            "value"                     => array(
                                __( 'Featured Image', "ts_visual_composer_extend" )          	=> "image",
                                __( 'Name / Position', "ts_visual_composer_extend" )          	=> "name",
                                __( 'Contact Information', "ts_visual_composer_extend" )		=> "contact",
								__( 'Social Networks', "ts_visual_composer_extend" )          	=> "social",
								__( 'Main Content', "ts_visual_composer_extend" )          		=> "description",
								__( 'Optional Information', "ts_visual_composer_extend" )		=> "optional",
								__( 'Download Button', "ts_visual_composer_extend" )			=> "download",
								__( 'Skillsets', "ts_visual_composer_extend" )          		=> "skillsets",
                            ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "admin_label"               => true,
                            "dependency"                => ""
                        ),
						// Image Effects
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
							"value"						=> "",
                            "seperator"					=> "Featured Image",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'image' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Design", "ts_visual_composer_extend" ),
                            "param_name"                => "style",
                            "value"                     => array(
                                __( 'Style 1', "ts_visual_composer_extend" )          => "style1",
                                __( 'Style 2', "ts_visual_composer_extend" )          => "style2",
                                __( 'Style 3', "ts_visual_composer_extend" )          => "style3",
                            ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'image' ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Use Grayscale Effect", "ts_visual_composer_extend" ),
                            "param_name"                => "show_grayscale",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to apply a grayscale effect on the featured image.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'image' ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Use Grayscale on Hover", "ts_visual_composer_extend" ),
                            "param_name"                => "grayscale_hover",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the grayscale effect when hovering over the featured image.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "show_grayscale", 'value' => 'true' ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Use Other Hover Effects", "ts_visual_composer_extend" ),
                            "param_name"                => "show_effects",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to use another style-specifc hover effects on the featured image.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'image' ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Use Lightbox with Image", "ts_visual_composer_extend" ),
                            "param_name"                => "show_lightbox",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to apply the lightbox to the featured image.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'image' ),
                        ),
						// Name / Position
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
							"value"						=> "",
                            "seperator"					=> "Name / Position Style",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'name' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Alignment", "ts_visual_composer_extend" ),
                            "param_name"                => "name_align",
                            "value"                     => array(
                                __( 'Left', "ts_visual_composer_extend" )          		=> "left",
                                __( 'Center', "ts_visual_composer_extend" )          	=> "center",
                                __( 'Right', "ts_visual_composer_extend" )          	=> "right",
								__( 'Justified', "ts_visual_composer_extend" )          => "justify",
                            ),
                            "description"               => __( "Define how the name and position should be aligned in the column.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'name' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Name Size", "ts_visual_composer_extend" ),
                            "param_name"                => "name_title_size",
                            "value"                     => "18",
                            "min"                       => "10",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the font size for the teammates name.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'name' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Position Size", "ts_visual_composer_extend" ),
                            "param_name"                => "name_job_size",
                            "value"                     => "16",
                            "min"                       => "10",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the font size for the teammates position or title.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'name' ),
                        ),
						// Contact Information
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_4",
							"value"						=> "",
                            "seperator"					=> "Contact Style",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'contact' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Icon Size", "ts_visual_composer_extend" ),
                            "param_name"                => "contact_icon_size",
                            "value"                     => "14",
                            "min"                       => "10",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the icon size for the contact information.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'contact' ),
                        ),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "contact_icon_color",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the color of the icon for the contact information.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'contact' ),
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Font Size", "ts_visual_composer_extend" ),
                            "param_name"                => "contact_font_size",
                            "value"                     => "14",
                            "min"                       => "10",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the font size for the contact information.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'contact' ),
                        ),
                        // Social Icon Style
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_5",
							"value"						=> "",
                            "seperator"					=> "Social Icon Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Style", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_style",
                            "value"                     => array(
                                "Simple"                => "simple",
                                "Square"                => "square",
                                "Rounded"               => "rounded",
                                "Circle"                => "circle",
                            ),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Icon Size", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_size",
                            "value"                     => "16",
                            "min"                       => "12",
                            "max"                       => "60",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the size for the social icons.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
                        ),
						array(
							"type"				    	=> "css3animations",
							"class"				    	=> "",
							"heading"			    	=> __("Icons Hover Animation", "ts_visual_composer_extend"),
							"param_name"		    	=> "social_icon_hover",
							"standard"			    	=> "false",
							"prefix"			    	=> "ts-hover-css-",
							"connector"			    	=> "social_css3animations_in",
							"noneselect"		    	=> "true",
							"default"			    	=> "",
							"value"				    	=> "",
							"admin_label"		    	=> false,
							"description"		    	=> __("Select the hover animation for the social icons.", "ts_visual_composer_extend"),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
						),
						array(
							"type"				    	=> "hidden_input",
							"heading"			    	=> __( "Icons Hover Animation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "social_css3animations_in",
							"value"				    	=> "",
							"admin_label"		    	=> false,
							"description"		    	=> __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
						),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Icon Background Color", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_background",
                            "value"                     => "#f5f5f5",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "social_icon_style", 'value' => array('square', 'rounded', 'circle') ),
                        ),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Icon Border Color", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_frame_color",
                            "value"                     => "#f5f5f5",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "social_icon_style", 'value' => array('square', 'rounded', 'circle') ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Icon Frame Border Thickness", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_frame_thick",
                            "value"                     => "1",
                            "min"                       => "1",
                            "max"                       => "10",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "social_icon_style", 'value' => array('square', 'rounded', 'circle') ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Icon Margin", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_margin",
                            "value"                     => "5",
                            "min"                       => "0",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Icons Align", "ts_visual_composer_extend" ),
                            "param_name"                => "social_icon_align",
                            "width"                     => 150,
                            "value"                     => array(
                                __( 'Left', "ts_visual_composer_extend" )         => "left",
                                __( 'Right', "ts_visual_composer_extend" )        => "right",
                                __( 'Center', "ts_visual_composer_extend" )       => "center" ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'social' ),
                        ),
                        // Optional Information
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_6",
							"value"						=> "",
                            "seperator"					=> "Optional Info - Title Style",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'optional' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Title Alignment", "ts_visual_composer_extend" ),
                            "param_name"                => "optional_title_align",
                            "value"                     => array(
                                __( 'Left', "ts_visual_composer_extend" )          		=> "left",
                                __( 'Center', "ts_visual_composer_extend" )          	=> "center",
                                __( 'Right', "ts_visual_composer_extend" )          	=> "right",
								__( 'Justified', "ts_visual_composer_extend" )          => "justify",
                            ),
                            "description"               => __( "Define how the title should be aligned.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'optional' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Title Size", "ts_visual_composer_extend" ),
                            "param_name"                => "optional_title_size",
                            "value"                     => "14",
                            "min"                       => "10",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the font size for the title.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'optional' ),
                        ),
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_7",
							"value"						=> "",
                            "seperator"					=> "Optional Info - Text Style",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'optional' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Text Alignment", "ts_visual_composer_extend" ),
                            "param_name"                => "optional_text_align",
                            "value"                     => array(
                                __( 'Left', "ts_visual_composer_extend" )          		=> "left",
                                __( 'Center', "ts_visual_composer_extend" )          	=> "center",
                                __( 'Right', "ts_visual_composer_extend" )          	=> "right",
								__( 'Justified', "ts_visual_composer_extend" )          => "justify",
                            ),
                            "description"               => __( "Define how the text should be aligned.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'optional' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Font Size", "ts_visual_composer_extend" ),
                            "param_name"                => "optional_font_size",
                            "value"                     => "14",
                            "min"                       => "10",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the font size for the text.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'optional' ),
                        ),
						// Download Button
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_8",
							"value"						=> "",
                            "seperator"					=> "Button Style",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'download' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Button Width", "ts_visual_composer_extend" ),
                            "param_name"                => "button_width",
                            "value"                     => "100",
                            "min"                       => "10",
                            "max"                       => "100",
                            "step"                      => "1",
                            "unit"                      => '%',
                            "description"               => __( "Define the width of the download button.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'download' ),
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Button Alignment", "ts_visual_composer_extend" ),
                            "param_name"                => "button_align",
                            "value"                     => array(
                                __( 'Center', "ts_visual_composer_extend" )          	=> "center",
                                __( 'Left', "ts_visual_composer_extend" )          		=> "left",
                                __( 'Right', "ts_visual_composer_extend" )          	=> "right",
                            ),
                            "description"               => __( "Define how the button should be aligned.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'download' ),
                        ),
						// Skillsets
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_9",
							"value"						=> "",
                            "seperator"					=> "Skill Bars Style",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'skillsets' ),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Bar Height", "ts_visual_composer_extend" ),
                            "param_name"                => "skills_height",
                            "value"                     => "2",
                            "min"                       => "2",
                            "max"                       => "75",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the height for each individual skill bar.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "element", 'value' => 'skillsets' ),
                        ),
                        array(
                            "type"				        => "switch",
                            "heading"                   => __( "Add Stripes", "ts_visual_composer_extend" ),
                            "param_name"                => "skills_stripes",
                            "value"                     => "false",
                            "on"				        => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"				        => __( 'No', "ts_visual_composer_extend" ),
                            "style"				        => "select",
                            "design"			        => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to add a stripes to the skill bar.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "element", 'value' => 'skillsets' ),
                        ),
                        array(
                            "type"				        => "switch",
                            "heading"                   => __( "Add Stripes Animation", "ts_visual_composer_extend" ),
                            "param_name"                => "skills_animation",
                            "value"                     => "false",
                            "on"				        => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"				        => __( 'No', "ts_visual_composer_extend" ),
                            "style"				        => "select",
                            "design"			        => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to add an animation to the striped skill bar.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "skills_stripes", 'value' => 'true'),
                        ),
                        // Other Teammate Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_10",
							"value"						=> "",
                            "seperator"					=> "Other Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
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
							"type"						=> "load_file",
							"heading"					=> __( "", "ts_visual_composer_extend" ),
							"value"						=> "",
							"param_name"				=> "el_file1",
							"file_type"					=> "js",
							"file_path"					=> "js/ts-visual-composer-extend-element.min.js",
							"description"				=> __( "", "ts_visual_composer_extend" )
						),
						array(
							"type"						=> "load_file",
							"heading"					=> __( "", "ts_visual_composer_extend" ),
							"value"						=> "",
							"param_name"				=> "el_file2",
							"file_type"					=> "css",
							"file_id"					=> "ts-extend-animations",
							"file_path"					=> "css/ts-visual-composer-extend-animations.min.css",
							"description"				=> __( "", "ts_visual_composer_extend" )
						),
                    ))
                );
            }			
		}
	}
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_TS_VCSC_Team_Page_Elements extends WPBakeryShortCode {};
}
// Initialize "TS Teampage Section" Class
if (class_exists('TS_Teampage')) {
	$TS_Teampage = new TS_Teampage;
}