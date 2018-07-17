<?php
if (!class_exists('TS_Teammates_Old')){
	class TS_Teammates_Old {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                                  array($this, 'TS_VCSC_Add_Teammate_Old_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                    array($this, 'TS_VCSC_Add_Teammate_Old_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',                                array($this, 'TS_VCSC_Add_Teammate_Old_Elements'), 9999999);
            }
            add_shortcode('TS-VCSC-Team-Mates',                         array($this, 'TS_VCSC_Teammate_Old'));
            add_shortcode('TS_VCSC_Team_Mates', 			            array($this, 'TS_VCSC_Teammate_Old'));
		}
        
        // Standalone Teammate
        function TS_VCSC_Teammate_Old ($atts, $content = null) {
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
                'team_member'			=> '',
                'custompost_name'		=> '',
                'style'					=> 'style1',
                'show_image'            => 'true',
                'show_grayscale'        => 'true',
				'show_effects'			=> 'true',
                'show_lightbox'         => 'true',
                'show_title'            => 'true',
                'show_content'          => 'true',
                'show_dedicated'        => 'false',
                'show_download'			=> 'true',
                'show_contact'			=> 'true',
                'show_social'			=> 'true',
                'show_skills'			=> 'true',
                'icon_style' 			=> 'simple',
				'icon_color'			=> '#000000',
                'icon_background'		=> '#f5f5f5',
                'icon_frame_color'		=> '#f5f5f5',
                'icon_frame_thick'		=> 1,
                'icon_margin' 			=> 5,
                'icon_align'			=> 'left',
                'icon_hover'			=> '',
                'tooltip_style'			=> '',
                'tooltip_position'		=> 'ts-simptip-position-top',
                'animation_view'		=> '',
                'margin_top'			=> 0,
                'margin_bottom'			=> 0,
                'el_id' 				=> '',
                'el_class'              => '',
				'css'					=> '',
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
                $team_block_id					= 'ts-vcsc-meet-team-' . mt_rand(999999, 9999999);
            }
        
            if ($animation_view != '') {
                $animation_css              	= TS_VCSC_GetCSSAnimation($animation_view);
            } else {
                $animation_css					= '';
            }
            
            $team_tooltipclasses				= "ts-simptip-multiline " . $tooltip_style . " " . $tooltip_position;
        
            if ((empty($icon_background)) || ($icon_style == 'simple')) {
                $icon_frame_style				= '';
            } else {
                $icon_frame_style				= 'background: ' . $icon_background . ';';
            }
            
            if ($icon_frame_thick > 0) {
                $icon_top_adjust				= 'top: ' . (10 - $icon_frame_thick) . 'px;';
            } else {
                $icon_top_adjust				= '';
            }
            
            if ($icon_style == 'simple') {
                $icon_frame_border				= '';
            } else {
                $icon_frame_border				= ' border: ' . $icon_frame_thick . 'px solid ' . $icon_frame_color . ';';
            }
            
            $icon_horizontal_adjust				= '';
        
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
            
            // Build Dedicated Page Link
            $team_dedicated     = '';
            if ($show_dedicated == "true") {
                if ((isset($Team_Dedicatedpage)) && ($Team_Dedicatedpage != -1)) {
                    $Team_Dedicatedpage         = get_page_link($Team_Dedicatedpage);
                    if (isset($Team_Dedicatedtarget)) {
                        $team_dedicated_target  = '_blank';
                    } else {
                        $team_dedicated_target  = '_parent';
                    }
                    $team_dedicated	.= '<div class="ts-teammate-dedicated">';
                    if (isset($Team_Dedicatedtooltip)) {
                        $team_dedicated 	.= '<a class="ts-teammate-page-link ts-button ' . $Team_Dedicatedtype . ' ' . $team_tooltipclasses . '" data-tstooltip="' . $Team_Dedicatedtooltip . '" href="' . TS_VCSC_makeValidURL($Team_Dedicatedpage) . '" target="' . $team_dedicated_target . '"><img class="ts-teammate-page-image" src="' . TS_VCSC_GetResourceURL('images/ts_vcsc_demo_icon_16x16.png') . '"> ' . $Team_Dedicatedlabel . '</a>';
                    } else {
                        $team_dedicated 	.= '<a class="ts-teammate-page-link ts-button ' . $Team_Dedicatedtype . '" href="' . TS_VCSC_makeValidURL($Team_Dedicatedpage) . '" target="' . $team_dedicated_target . '"><img class="ts-teammate-page-image" src="' . TS_VCSC_GetResourceURL('images/ts_vcsc_demo_icon_16x16.png') . '"> ' . $Team_Dedicatedlabel . '</a>';
                    }
                    $team_dedicated 	.= '</div>';
                    if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0) {
                        wp_enqueue_style('ts-extend-buttons',                 		TS_VCSC_GetResourceURL('css/jquery.buttons.css'), null, false, 'all');
                    }
                }
            }
            
            // Build Team Contact Information
            $team_contact		= '';
            $team_contact_count	= 0;
            if ($show_contact == "true") {
                $team_contact		.= '<div class="ts-team-contact">';
                    if (isset($Team_Email)) {
                        $team_contact_count++;
                        if (isset($Team_Emaillabel)) {
                            $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-email2 ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i><a target="_blank" class="" href="mailto:' . $Team_Email . '">' . $Team_Emaillabel . '</a></div>';
                        } else {
                            $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-email2 ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i><a target="_blank" class="" href="mailto:' . $Team_Email . '">' . $Team_Email . '</a></div>';
                        }
                    }
                    if (isset($Team_Phone)) {
                        $team_contact_count++;
                        $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-phone2 ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i>' . $Team_Phone . '</div>';
                    }
                    if (isset($Team_Cell)) {
                        $team_contact_count++;
                        $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-mobile ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i>' . $Team_Cell . '</div>';
                    }
                    if (isset($Team_Portfolio)) {
                        $team_contact_count++;
                        if (isset($Team_Portfoliolabel)) {
                            $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-portfolio ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Portfolio) . '">' . $Team_Portfoliolabel . '</a></div>';
                        } else {
                            $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-portfolio ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Portfolio) . '">' . TS_VCSC_makeValidURL($Team_Portfolio) . '</a></div>';
                        }
                    }
                    if (isset($Team_Other)) {
                        $team_contact_count++;
                        if (isset($Team_Otherlabel)) {
                            $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-link ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Other) . '">' . $Team_Otherlabel . '</a></div>';
                        } else {
                            $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-link ts-font-icon ts-teammate-icon" style="color: ' . $icon_color . ';"></i><a style="" target="_blank" class="" href="' . TS_VCSC_makeValidURL($Team_Other) . '">' . TS_VCSC_makeValidURL($Team_Other) . '</a></div>';
                        }
                    }
                    if (isset($Team_Skype)) {
                        $team_contact_count++;
                        $team_contact .= '<div class="ts-contact-parent"><i class="ts-teamicon-skype ts-font-icon ts-teammate-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i>' . $Team_Skype . '</div>';
                    }
                $team_contact		.= '</div>';
            }
            
            // Build Team Social Links
            $team_social 		= '';
            $team_social_count	= 0;
            if ($show_social == "true") {
                $team_social 		.= '<ul class="ts-teammate-icons ' . $icon_style . ' clearFixMe">';
                    if (isset($Team_Facebook)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Facebook"><a style="" target="_blank" class="ts-teammate-link facebook ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Facebook) . '"><i class="ts-teamicon-facebook1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Google)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Google+"><a style="" target="_blank" class="ts-teammate-link gplus ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Google) . '"><i class="ts-teamicon-googleplus1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Twitter)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Twitter"><a style="" target="_blank" class="ts-teammate-link twitter ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Twitter) . '"><i class="ts-teamicon-twitter1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Linkedin)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="LinkedIn"><a style="" target="_blank" class="ts-teammate-link linkedin ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Linkedin) . '"><i class="ts-teamicon-linkedin ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Xing)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Xing"><a style="" target="_blank" class="ts-teammate-link xing ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Xing) . '"><i class="ts-teamicon-xing3 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Envato)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Envato"><a style="" target="_blank" class="ts-teammate-link envato ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Envato) . '"><i class="ts-teamicon-envato ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Rss)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="RSS"><a style="" target="_blank" class="ts-teammate-link rss ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Rss) . '"><i class="ts-teamicon-rss1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Forrst)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Forrst"><a style="" target="_blank" class="ts-teammate-link forrst ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Forrst) . '"><i class="ts-teamicon-forrst1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Flickr)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Flickr"><a style="" target="_blank" class="ts-teammate-link flickr ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Flickr) . '"><i class="ts-teamicon-flickr3 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Instagram)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Instagram"><a style="" target="_blank" class="ts-teammate-link instagram ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Instagram) . '"><i class="ts-teamicon-instagram ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Picasa)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Picasa"><a style="" target="_blank" class="ts-teammate-link picasa ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Picasa) . '"><i class="ts-teamicon-picasa1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Pinterest)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Pinterest"><a style="" target="_blank" class="ts-teammate-link pinterest ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Pinterest) . '"><i class="ts-teamicon-pinterest1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Vimeo)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="Vimeo"><a style="" target="_blank" class="ts-teammate-link vimeo ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Vimeo) . '"><i class="ts-teamicon-vimeo1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                    if (isset($Team_Youtube)) {
                        $team_social_count++;
                        $team_social .= '<li class="ts-teammate-icon ' . $icon_align . ' ' . $team_tooltipclasses . '" style="' . $icon_frame_border . ' ' . $icon_frame_style . '" data-tstooltip="YouTube"><a style="" target="_blank" class="ts-teammate-link youtube ' . $icon_hover . '" href="' . TS_VCSC_makeValidURL($Team_Youtube) . '"><i class="ts-teamicon-youtube1 ts-font-icon" style="' . $icon_top_adjust . ' ' . $icon_horizontal_adjust . '"></i></a></li>';
                    }
                $team_social 		.= '</ul>';
            }
            
            // Build Team Skills
            $team_skills 		= '';
            $team_skills_count	= 0;
            if ((isset($Team_Skillset)) && ($show_skills == "true")) {
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
                            $team_skills .= '<div class="skill-label">' . $skill_name . '<span>(' . $skill_value . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $skill_color . '" data-level="' . $skill_value . '%" data-appear-animation-delay="400" style="width: ' . $skill_value . '%; ' . $skill_background . '"></div></div>';
                        }
                    }
                $team_skills		.= '</div>';
            } else if ((!isset($Team_Skillset)) && ($show_skills == "true")) {
                $skill_background 	= '';
                $team_skills		.= '<div class="ts-member-skills">';
                    if ((isset($Team_Skillname1)) && (isset($Team_Skillvalue1))) {
                        $team_skills_count++;
                        if (isset($Team_Skillcolor1)) {
                            $skill_background = 'background-color: ' . $Team_Skillcolor1 . ';';
                        }
                        $team_skills .= '<div class="skill-label">' . $Team_Skillname1 . '<span>(' . $Team_Skillvalue1 . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $Team_Skillcolor1 . '" data-level="' . $Team_Skillvalue1 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue1 . '%; ' . $skill_background . '"></div></div>';
                    }
                    if ((isset($Team_Skillname2)) && (isset($Team_Skillvalue2))) {
                        $team_skills_count++;
                        if (isset($Team_Skillcolor2)) {
                            $skill_background = 'background-color: ' . $Team_Skillcolor2 . ';';
                        }
                        $team_skills .= '<div class="skill-label">' . $Team_Skillname2 . '<span>(' . $Team_Skillvalue2 . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $Team_Skillcolor2 . '" data-level="' . $Team_Skillvalue2 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue2 . '%; ' . $skill_background . '"></div></div>';
                    }
                    if ((isset($Team_Skillname3)) && (isset($Team_Skillvalue3))) {
                        $team_skills_count++;
                        if (isset($Team_Skillcolor3)) {
                            $skill_background = 'background-color: ' . $Team_Skillcolor3 . ';';
                        }
                        $team_skills .= '<div class="skill-label">' . $Team_Skillname3 . '<span>(' . $Team_Skillvalue3 . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $Team_Skillcolor3 . '" data-level="' . $Team_Skillvalue3 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue3 . '%; ' . $skill_background . '"></div></div>';
                    }
                    if ((isset($Team_Skillname4)) && (isset($Team_Skillvalue4))) {
                        $team_skills_count++;
                        if (isset($Team_Skillcolor4)) {
                            $skill_background = 'background-color: ' . $Team_Skillcolor4 . ';';
                        }
                        $team_skills .= '<div class="skill-label">' . $Team_Skillname4 . '<span>(' . $Team_Skillvalue4 . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $Team_Skillcolor4 . '" data-level="' . $Team_Skillvalue4 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue4 . '%; ' . $skill_background . '"></div></div>';
                    }
                    if ((isset($Team_Skillname5)) && (isset($Team_Skillvalue5))) {
                        $team_skills_count++;
                        if (isset($Team_Skillcolor5)) {
                            $skill_background = 'background-color: ' . $Team_Skillcolor5 . ';';
                        }
                        $team_skills .= '<div class="skill-label">' . $Team_Skillname5 . '<span>(' . $Team_Skillvalue5 . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $Team_Skillcolor5 . '" data-level="' . $Team_Skillvalue5 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue5 . '%; ' . $skill_background . '"></div></div>';
                    }
                    if ((isset($Team_Skillname6)) && (isset($Team_Skillvalue6))) {
                        $team_skills_count++;
                        if (isset($Team_Skillcolor6)) {
                            $skill_background = 'background-color: ' . $Team_Skillcolor6 . ';';
                        }
                        $team_skills .= '<div class="skill-label">' . $Team_Skillname6 . '<span>(' . $Team_Skillvalue6 . '%)</span></div><div class="skill-bar"><div class="level" data-color="' . $Team_Skillcolor6 . '" data-level="' . $Team_Skillvalue6 . '%" data-appear-animation-delay="400" style="width: ' . $Team_Skillvalue6 . '%; ' . $skill_background . '"></div></div>';
                    }
                $team_skills		.= '</div>';
            }
            
            // Build Download Button
            $team_download 		= '';
            if ($show_download == "true") {
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
                        $Team_Buttontype = $Team_Buttontype;
                    } else {
                        $Team_Buttontype = 'ts-button-3d';
                    };
                    if (!empty($Team_File)) {
                        $team_download	.= '<div class="ts-teammate-download">';
                        if (isset($Team_Buttontooltip)) {
                            $team_download 	.= '<a class="ts-teammate-file-link ts-button ' . $Team_Buttontype . ' ' . $team_tooltipclasses . '" data-tstooltip="' . $Team_Buttontooltip . '" href="' . $Team_File . '" target="_blank"><img class="ts-teammate-file-image" src="' . TS_VCSC_GetResourceURL('images/filetypes/' . $Team_FileFormat . '.png') . '"> ' . $Team_Buttonlabel . '</a>';
                        } else {
                            $team_download 	.= '<a class="ts-teammate-file-link ts-button ' . $Team_Buttontype . '" href="' . $Team_File . '" target="_blank"><img class="ts-teammate-file-image" src="' . TS_VCSC_GetResourceURL('images/filetypes/' . $Team_FileFormat . '.png') . '"> ' . $Team_Buttonlabel . '</a>';
                        }
                        $team_download 	.= '</div>';
                        if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0) {
                            wp_enqueue_style('ts-extend-buttons',                 		TS_VCSC_GetResourceURL('css/jquery.buttons.css'), null, false, 'all');
                        }
                    }
                }
            }
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-teammate ' . $animation_css . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Team_Mates_Standalone', $atts);
			} else {
				$css_class	= 'ts-teammate ' . $animation_css . ' ' . $el_class;
			}
			
            // Create Output
            if ($style == "style1") {
                $output .= '<div id="' . $team_block_id . '" class="ts-team1 ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
                    if (($show_image == "true") && (!empty($Team_Image))) {
                        $output .= '<div class="team-avatar">';
                            $output .= '<img src="' . $Team_Image . '" rel="' . ($show_lightbox == "true" ? "nachoteam" : "") . '" title="' . $Team_Title . ' / ' . $Team_Position . '" alt="" class="' . ($show_lightbox == "true" ? "nch-lightbox" : "") . ' ' . ($show_grayscale == "true" ? "grayscale" : "") . ' ' . ($show_effects == "true" ? "hovereffect" : "") . '">';
                        $output .= '</div>';
                    }
                    $output .= '<div class="team-user">';
                        if (($show_title == "true") && (!empty($Team_Title))) {
                            $output .= '<h4 class="team-title">' . $Team_Title . '</h4>';
                        }
                        if (($show_title == "true") && (!empty($Team_Position))) {
                            $output .= '<div class="team-job">' . $Team_Position . '</div>';
                        }
                        $output .= $team_dedicated;
                        $output .= $team_download;
                    $output .= '</div>';
                    if (($show_content == "true") && (!empty($Team_Content))) {
                        $output .= '<div class="team-information">';
                            if (function_exists('wpb_js_remove_wpautop')){
                                $output .= '' . wpb_js_remove_wpautop(do_shortcode($Team_Content), true) . '';
                            } else {
                                $output .= '' . do_shortcode($Team_Content) . '';
                            }
                        $output .= '</div>';
                    }
                    if ($team_contact_count > 0) {
                        $output .= $team_contact;
                    }
                    if ($team_social_count > 0) {
                        $output .= $team_social;
                    }
                    if ($team_skills_count > 0) {
                        $output .= $team_skills;
                    }
                $output .= '</div>';
            }
			if ($style == "style2") {
                $output .= '<div id="' . $team_block_id . '" class="ts-team2 ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
                    $output .= '<div style="width: 25%; float: left;">';
                        if (($show_image == "true") && (!empty($Team_Image))) {
                            $output .= '<div class="ts-team2-header">';
                                $output .= '<img src="' . $Team_Image . '" rel="' . ($show_lightbox == "true" ? "nachoteam" : "") . '" title="' . $Team_Title . ' / ' . $Team_Position . '" alt="" class="' . ($show_lightbox == "true" ? "nch-lightbox" : "") . ' ' . ($show_grayscale == "true" ? "grayscale" : "") . ' ' . ($show_effects == "true" ? "hovereffect" : "") . '">';
                            $output .= '</div>';
                        }
                        if ($team_social_count > 0) {
                            $output .= '<div class="ts-team2-footer" style="' . (($show_image == "false") ? "margin-top: 0px;" : "") . '">';
                                $output .= $team_social;
                            $output .= '</div>';
                        }
                    $output .= '</div>';
                    if (($show_image == "true") || ($team_social_count > 0)) {
                        $output .= '<div class="ts-team2-content" style="">';
                    } else {
                        $output .= '<div class="ts-team2-content" style="width: 100%; margin-left: 0px;">';
                    }
                        $output .= '<div class="ts-team2-line"></div>';
                        if (($show_title == "true") && (!empty($Team_Title))) {
                            $output .= '<h3>' . $Team_Title . '</h3>';
                        }
                        if (($show_title == "true") && (!empty($Team_Position))) {
                            $output .= '<p class="ts-team2-lead">' . $Team_Position . '</p>';
                        }
                        if (($show_content == "true") && (!empty($Team_Content))) {
                            if (function_exists('wpb_js_remove_wpautop')){
                                $output .= '' . wpb_js_remove_wpautop(do_shortcode($Team_Content), true) . '';
                            } else {
                                $output .= '' . do_shortcode($Team_Content) . '';
                            }
                        }
                    $output .= '</div>';
                    $output .= $team_dedicated;
                    $output .= $team_download;
                    if ($team_contact_count > 0) {
                        $output .= $team_contact;
                    }
                    if ($team_skills_count > 0) {
                        $output .= $team_skills;
                    }
                $output .= '</div>';
            }
			if ($style == "style3") {
                $output .= '<div id="' . $team_block_id . '" class="ts-team3 ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
                    if (($show_image == "true") && (!empty($Team_Image))) {
                        $output .= '<img class="ts-team3-person-image ' . ($show_lightbox == "true" ? "nch-lightbox" : "") . ' ' . ($show_grayscale == "true" ? "grayscale" : "") . ' ' . ($show_effects == "true" ? "hovereffect" : "") . '" rel="' . ($show_lightbox == "true" ? "nachoteam" : "") . '" src="' . $Team_Image . '" title="' . $Team_Title . ' / ' . $Team_Position . '" alt="">';
                    }
                    if (($show_title == "true") && (!empty($Team_Title))) {
                        $output .= '<div class="ts-team3-person-name">' . $Team_Title . '</div>';
                    }
                    if (($show_title == "true") && (!empty($Team_Position))) {
                        $output .= '<div class="ts-team3-person-position">' . $Team_Position . '</div>';
                    }
                    if (($show_content == "true") && (!empty($Team_Content))) {
                        if (function_exists('wpb_js_remove_wpautop')){
                            $output .= '<div class="ts-team3-person-description">' . wpb_js_remove_wpautop(do_shortcode($Team_Content), true) . '</div>';
                        } else {
                            $output .= '<div class="ts-team3-person-description">' . do_shortcode($Team_Content) . '</div>';
                        }
                    }
                        $output .= $team_dedicated;
                        $output .= $team_download;
                        if ($team_contact_count > 0) {
                            $output .= $team_contact;
                        }
                        if ($team_social_count > 0) {
                            $output .= $team_social;
                        }
                        if ($team_skills_count > 0) {
                            $output .= $team_skills;
                        }
                    $output .= '<div class="ts-team3-person-space"></div>';					
                $output .= '</div>';
            }
    
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
		// Add Teammate Elements
        function TS_VCSC_Add_Teammate_Old_Elements() {
            global $VISUAL_COMPOSER_EXTENSIONS;
            // Deprecated Teammate Elements
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Single Teammate (Deprecated)", "ts_visual_composer_extend" ),
                    "base"                              => "TS-VCSC-Team-Mates",
                    "icon" 	                            => "icon-wpb-ts_vcsc_team_mates",
                    "class"                             => "",
                    "category"                          => __( 'VC Extensions (Deprecated)', "ts_visual_composer_extend" ),
                    "description"                       => __("Place a single teammate element", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(''),
                    //"admin_enqueue_css"               => array(''),
                    "params"                            => array(
                        // Teammate Selection
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Main Content",
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
                            "admin_label"		        => true,
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
                        // Style + Output Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "Style and Output",
                            "description"               => __( "", "ts_visual_composer_extend" )
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
                            "admin_label"               => true,
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Download Button", "ts_visual_composer_extend" ),
                            "param_name"                => "show_download",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the download button for this teammember.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Contact Information", "ts_visual_composer_extend" ),
                            "param_name"                => "show_contact",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the contact information for this teammember.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Social Links", "ts_visual_composer_extend" ),
                            "param_name"                => "show_social",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the social links for this teammember.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Skill Bars", "ts_visual_composer_extend" ),
                            "param_name"                => "show_skills",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the skill bars for this teammember.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        // Social Icon Style
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
                            "value"                     => "Social Icon Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Icon Settings",
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Style", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_style",
                            "value"                     => array(
                                "Simple"                => "simple",
                                "Square"                => "square",
                                "Rounded"               => "rounded",
                                "Circle"                => "circle",
                            ),
                            "group" 			        => "Icon Settings",
                        ),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Icon Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "icon_color",
							"value"             		=> "#000000",
							"description"       		=> __( "Define the color of the icon; only applies to icons for contact information but not social icons.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
                            "group" 			        => "Icon Settings",
						),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Icon Background Color", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_background",
                            "value"                     => "#f5f5f5",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') ),
                            "group" 			        => "Icon Settings",
                        ),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Icon Border Color", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_frame_color",
                            "value"                     => "#f5f5f5",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') ),
                            "group" 			        => "Icon Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Icon Frame Border Thickness", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_frame_thick",
                            "value"                     => "1",
                            "min"                       => "1",
                            "max"                       => "10",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') ),
                            "group" 			        => "Icon Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Icon Margin", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_margin",
                            "value"                     => "5",
                            "min"                       => "0",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Icon Settings",
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Icons Align", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_align",
                            "width"                     => 150,
                            "value"                     => array(
                                __( 'Left', "ts_visual_composer_extend" )         => "left",
                                __( 'Right', "ts_visual_composer_extend" )        => "right",
                                __( 'Center', "ts_visual_composer_extend" )       => "center" ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Icon Settings",
                        ),
						array(
							"type"				    	=> "css3animations",
							"class"				    	=> "",
							"heading"			    	=> __("Icons Hover Animation", "ts_visual_composer_extend"),
							"param_name"		    	=> "icon_hover",
							"standard"			    	=> "false",
							"prefix"			    	=> "ts-hover-css-",
							"connector"			    	=> "css3animations_in",
							"noneselect"		    	=> "true",
							"default"			    	=> "",
							"value"				    	=> "",
							"admin_label"		    	=> false,
							"description"		    	=> __("Select the hover animation for the social icons.", "ts_visual_composer_extend"),
                            "group" 			        => "Icon Settings",
						),
						array(
							"type"				    	=> "hidden_input",
							"heading"			    	=> __( "Icons Hover Animation", "ts_visual_composer_extend" ),
							"param_name"		    	=> "css3animations_in",
							"value"				    	=> "",
							"admin_label"		    	=> true,
							"description"		    	=> __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Icon Settings",
						),
                        // Other Teammate Settings
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
                            "value"                     =>  array(
								__( "None", "ts_visual_composer_extend" )                          => "",
								__( "Top to Bottom", "ts_visual_composer_extend" )                 => "top-to-bottom",
								__( "Bottom to Top", "ts_visual_composer_extend" )                 => "bottom-to-top",
								__( "Left to Right", "ts_visual_composer_extend" )                 => "left-to-right",
								__( "Right to Left", "ts_visual_composer_extend" )                 => "right-to-left",
								__( "Appear from Center", "ts_visual_composer_extend" )            => "appear",
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
// Register Shortcode with Visual Composer
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_TS_VCSC_Team_Mates extends WPBakeryShortCode {};
}
// Initialize "TS Teammates Old" Class
if (class_exists('TS_Teammates_Old')) {
	$TS_Teammates_Old = new TS_Teammates_Old;
}