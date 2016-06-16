<?php
if (!class_exists('TS_Poststicker')){
	class TS_Poststicker {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                                  array($this, 'TS_VCSC_Add_Posts_Ticker_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                    array($this, 'TS_VCSC_Add_Posts_Ticker_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',                                array($this, 'TS_VCSC_Add_Posts_Ticker_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Posts_Ticker_Standalone',            array($this, 'TS_VCSC_Posts_Ticker_Standalone'));
		}
        
        // Standalone Posts Ticker
        function TS_VCSC_Posts_Ticker_Standalone ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();

            wp_enqueue_script('ts-extend-newsticker');				
			wp_enqueue_style('ts-font-ecommerce');
			wp_enqueue_style('ts-font-teammatess');
			
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
				wp_enqueue_style('ts-extend-animations');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
				'post_type'				=> 'post',
				'date_format'			=> 'F j, Y',
				'time_format'			=> 'l, g:i A',
				
				'limit_posts'			=> 'true',
				'limit_by'				=> 'category',							// post_tag, cust_tax
				'limit_term'			=> '',
				
				'filter_menu'			=> 'true',
				'layout_menu'			=> 'true',
				'sort_menu'				=> 'false',
				'directions_menu'		=> 'false',
				
				'filter_by'				=> 'category', 							// post_tag, cust_tax
				
				'ticker_direction'		=> 'up',
				'ticker_speed'			=> 3000,
				'ticker_break'			=> 480,				
				
				'ticker_border_type'	=> '',
				'ticker_border_thick'	=> 1,
				'ticker_border_color'	=> '#ededed',
				'ticker_border_radius'	=> '',
				'ticker_auto'			=> 'false',
				'ticker_hover'			=> 'true',
				'ticker_controls'		=> 'true',				
				'ticker_symbol'			=> 'false',
				'ticker_icon'			=> '',
				'ticker_paint'			=> '#ffffff',
				'ticker_title'			=> 'true',
				'ticker_header'			=> 'Breaking News',
				'ticker_background'		=> '#D10000',
				'ticker_color'			=> '#ffffff',
				'ticker_type'			=> 'true',
				'ticker_date'			=> 'true',
				'ticker_side'			=> 'left',
				'ticker_fixed'			=> 'false',
				'ticker_position'		=> 'top',
				'ticker_adjustment'		=> 0,
				'ticker_target'			=> '_parent',
				
				'posts_limit'			=> 25,
				
                'margin_top'			=> 0,
                'margin_bottom'			=> 0,
                'el_id' 				=> '',
                'el_class'              => '',
				'css'					=> '',
            ), $atts ));
			
			$postslider_random			= mt_rand(999999, 9999999);
			$output						= '';
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$frontend_edit				= 'true';
					$ticker_fixed				= 'false';
					$ticker_auto				= 'false';
                } else {
					$frontend_edit				= 'false';
					$ticker_fixed				= $ticker_fixed;
					$ticker_auto				= $ticker_auto;
                }
            } else {
				$frontend_edit					= 'false';
				$ticker_fixed					= $ticker_fixed;
				$ticker_auto					= $ticker_auto;
            }
			
            if (!empty($el_id)) {
                $posts_container_id		= $el_id;
            } else {
                $posts_container_id		= 'ts-posts-ticker-parent-' . $postslider_random;
            }
			
			$limit_term 				= str_replace(' ', '', $limit_term);

			if ($limit_by == 'category') {
				$limit_tax 				= 'category';
			} else if ($limit_by == 'post_tag') {
				$limit_tax 				= 'post_tag';
			} else if ($limit_by == 'cust_tax') {
				$limit_tax 				= '';
			}

			$filter_tax 				= '';
			
			// - set the taxonomy for the filter menu -
			if ($filter_by == 'category') {
				$menu_tax 				= 'category';
			} else if ($filter_by == 'post_tag') {
				$menu_tax 				= 'post_tag';
			} else if ($filter_by == 'cust_tax') {
				$menu_tax 				= $filter_tax; 
			}

			// Set the WP Query Arguments
			$args = array(
				'post_type' 			=> $post_type,
				'posts_per_page' 		=> '-1'
			);
			if ($limit_posts == 'true' && taxonomy_exists($limit_tax)) {
				$limited_terms 			= explode(',', $limit_term);
				$args['tax_query'] = array(
					array (
						'taxonomy' 		=> $limit_tax,
						'field' 		=> 'slug',
						'terms' 		=> $limited_terms,
						'operator' 		=> 'NOT IN'
					)
				);
			}
			$isoposts = new WP_Query($args);
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Posts_Ticker_Standalone', $atts);
			} else {
				$css_class	= '';
			}
			
			if ($ticker_fixed == "true") {
				$newsticker_class		= 'ts-newsticker-fixed';
				$newsticker_position	= 'ts-newsticker-' . $ticker_position;
				if ($ticker_position == "top") {
					$newsticker_style	= 'top: ' . $ticker_adjustment . 'px;';
				} else {
					$newsticker_style	= 'bottom: ' . $ticker_adjustment . 'px;';
				}
				$margin_top				= 0;
				$margin_bottom			= 0;
			} else {
				$newsticker_class		= 'ts-newsticker-standard';
				$newsticker_position	= '';
				$newsticker_style		= '';
			}
			
			if ($ticker_border_type != '') {
				$newsticker_border		= 'border: ' . $ticker_border_thick . 'px ' . $ticker_border_type . ' ' . $ticker_border_color . ';';
			} else {
				$newsticker_border		= '';
			}
			
			if ($ticker_side == "left") {
				$newsticker_elements	= 'ts-newsticker-elements-left';
			} else {
				$newsticker_elements	= 'ts-newsticker-elements-right';
			}
			
			if ($ticker_title == "false") {
				$newsticker_header		= 'left: 0;';
				$newsticker_controls	= 'right: 10px;';
			} else {
				$newsticker_header		= '';
				$newsticker_controls	= '';
			}
			
			echo '<div id="' . $posts_container_id . '" class="ts-newsticker-parent ' . $css_class . '" style="margin-top: ' . $margin_top. 'px; margin-bottom: ' . $margin_bottom . ';">';
				// Create Individual Post Output
				$postCounter 	= 0;
				$postMonths 	= array();
				if (post_type_exists($post_type) && $isoposts->have_posts()) { 
					echo '<div id="ts-newsticker-oneliner-' . $postslider_random . '"
						class="ts-newsticker-oneliner ' . $newsticker_class . ' ' . $newsticker_position . ' ' . $ticker_border_radius . '"
						style="' . $newsticker_style . ' ' . $newsticker_border . '"
						data-ticker="ts-newsticker-ticker-' . $postslider_random . '"
						data-controls="ts-newsticker-controls-' . $postslider_random . '"
						data-navigation="' . $ticker_controls . '"
						data-break="' . $ticker_break . '"
						data-auto="' . $ticker_auto . '"
						data-speed="' . $ticker_speed . '"
						data-hover="' . $ticker_hover . '"
						data-direction="' . $ticker_direction . '"
						data-parent="' . $posts_container_id . '"
						data-side="' . $ticker_side . '"
						data-header="ts-newsticker-header-' . $postslider_random . '"
						data-next="ts-newsticker-controls-next-' . $postslider_random . '"
						data-prev="ts-newsticker-controls-prev-' . $postslider_random . '"
						data-play="ts-newsticker-controls-play-' . $postslider_random . '"
						data-stop="ts-newsticker-controls-stop-' . $postslider_random . '">';
						
						echo '<div class="ts-newsticker-elements-frame ' . $newsticker_elements . ' ' . $ticker_border_radius . '" style="">';
							// Add Navigation Controls
							echo '<div id="ts-newsticker-controls-' . $postslider_random . '" class="ts-newsticker-controls" style="' . (($ticker_controls == "true") ? "display: block;" : "display: none;") . ' ' . $newsticker_controls . '">';
								echo '<div id="ts-newsticker-controls-next-' . $postslider_random . '" style="' . (($ticker_controls == "true") ? "display: block;" : "display: none;") . '" class="ts-newsticker-controls-next"><span class="ts-ecommerce-arrowright5"></span></div>';
								echo '<div id="ts-newsticker-controls-prev-' . $postslider_random . '" style="' . (($ticker_controls == "true") ? "display: block;" : "display: none;") . '" class="ts-newsticker-controls-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
								echo '<div id="ts-newsticker-controls-stop-' . $postslider_random . '" class="ts-newsticker-controls-play" style="' . ($ticker_auto == "true" ? "display: block;" : "display: none;") . '"><span class="ts-ecommerce-pause"></span></div>';
								echo '<div id="ts-newsticker-controls-play-' . $postslider_random . '" class="ts-newsticker-controls-play" style="' . ($ticker_auto == "true" ? "display: none;" : "display: block;") . '"><span class="ts-ecommerce-play"></span></div>';
							echo '</div>';
							if (($ticker_side == "left") && ($ticker_title == "true")) {
								echo '<div id="ts-newsticker-header-' . $postslider_random . '" class="header ' . $ticker_border_radius . '" style="background: ' . $ticker_background .'; color: ' . $ticker_color . '; left: 0;">';
									if (($ticker_icon != '') && ($ticker_icon != 'transparent') && ($ticker_symbol == "true")) {
										echo '<i class="ts-font-icon ' . $ticker_icon . '" style="color: ' . $ticker_paint . '"></i>';
									}
									echo '<span>' . $ticker_header . '</span>';
								echo '</div>';
							}
							echo '<ul id="ts-newsticker-ticker-' . $postslider_random . '" class="newsticker ' . $ticker_border_radius . '" style="' . $newsticker_header . '">';
								while ($isoposts->have_posts() ) :
									$isoposts->the_post();
									$matched_terms				= 0;
									/*if ($limit_posts == 'true') {
										$limited_terms 			= explode(',', $limit_term);
										$present_terms			= get_the_category();
										foreach ($present_terms as $category) {
											if (in_array(trim($category->slug), $limited_terms)) {
												$matched_terms++;
											}
										}
									}*/
									if ($matched_terms == 0) {
										$postCounter++;
										if ($postCounter < $posts_limit + 1) {
											$postAttributes = 'data-full="' . get_post_time($date_format) . '" data-time="' . get_post_time($time_format) . '" data-author="' . get_the_author() . '" data-date="' . get_post_time('U') . '" data-modified="' . get_the_modified_time('U') . '" data-title="' . get_the_title() . '" data-comments="' . get_comments_number() . '" data-id="' .  get_the_ID() . '"';
											echo '<li ' . $postAttributes . '>';	
													if ('' != get_the_post_thumbnail()) { 
														$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
														echo '<img class="ts-newsticker-image" src="' . $thumbnail[0] .'" style="">';
													}
													echo '<a href="' . get_permalink() . '" target="' . $ticker_target . '">';
														if ($ticker_type == "true") {
															$format = get_post_format();
															if (false === $format) {
																$format 	= __( 'Standard', "ts_visual_composer_extend" );
																$class 		= 'standard';
															} else {
																$class		= strtolower($format);
															}	
															echo '<i class="ts-newsticker-type ts-newsticker-type-' . $class . '"></i>';
														}
														echo get_the_title();											
													echo '</a>';
													if ($ticker_date == "true") {
														echo '<span class="ts-newsticker-datetime" style="">' . get_post_time($date_format) . '</span>';
													}
											echo '</li>';
										}
									}
								endwhile;
							echo '</ul>';
							if (($ticker_side == "right") && ($ticker_title == "true")) {
								echo '<div id="ts-newsticker-header-' . $postslider_random . '" class="header ' . $ticker_border_radius . '" style="background: ' . $ticker_background .'; color: ' . $ticker_color . '; right: 0;">';
									if (($ticker_icon != '') && ($ticker_icon != 'transparent') && ($ticker_symbol == "true")) {
										echo '<i class="ts-font-icon ' . $ticker_icon . '" style="color: ' . $ticker_paint . '"></i>';
									}
									echo '<span>' . $ticker_header . '</span>';
								echo '</div>';
							}
						echo '</div>';
					echo '</div>';
					wp_reset_postdata();
				} else {
					echo '<p>Nothing found. Please check back soon!</p>';
				}
			echo '</div>';
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
        // Add Posts Slider Elements
        function TS_VCSC_Add_Posts_Ticker_Elements() {
            global $VISUAL_COMPOSER_EXTENSIONS;
            // Add Standalone Posts Slider
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Posts Ticker", "ts_visual_composer_extend" ),
                    "base"                              => "TS_VCSC_Posts_Ticker_Standalone",
                    "icon" 	                            => "icon-wpb-ts_vcsc_posts_ticker",
					"class"                     		=> "ts_vcsc_main_posts_ticker",
                    "category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
                    "description"                       => __("Place a Posts Ticker element", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(''),
                    //"admin_enqueue_css"               => array(''),
                    "params"                            => array(
                        // Posts Ticker Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "",
							"seperator"					=> "Content Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Exclude Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "limit_posts",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to exclude some post categories for the element.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"                      => "standardpostcat",
                            "heading"                   => __( "Select Excluded Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "limit_term",
                            "posttype"                  => "post",
                            "posttaxonomy"              => "ts_logos_category",
							"taxonomy"              	=> "ts_logos_category",
							"postsingle"				=> "Post",
							"postplural"				=> "Posts",
							"postclass"					=> "post",
                            "value"                     => "",
							"admin_label"		        => true,
                            "description"               => __( "Please select the categories you want to use or exclude for the element.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "limit_posts", "value" 	=> "true"),
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Total Number of Posts", "ts_visual_composer_extend" ),
                            "param_name"                => "posts_limit",
                            "value"                     => "25",
                            "min"                       => "1",
                            "max"                       => "100",
                            "step"                      => "1",
                            "unit"                      => '',
							"admin_label"		        => true,
                            "description"               => __( "Select the total number of posts to be retrieved from WordPress.", "ts_visual_composer_extend" ),
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Link Target", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_target",
							"value"             		=> array(
								__( "Same Window", "ts_visual_composer_extend" )                    => "_parent",
								__( "New Window", "ts_visual_composer_extend" )                     => "_blank"
							),
							"description"       		=> __( "Define how the link should be opened.", "ts_visual_composer_extend" ),
							"dependency"        		=> array("element" 	=> "show_button", "value" 	=> "true"),
						),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Post Type", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_type",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show an icon post type indicator.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Post Date", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_date",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the post date next to the title.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Date Format", "ts_visual_composer_extend" ),
                            "param_name"                => "date_format",
                            "value"                     => "F j, Y",
							"dependency"                => array("element" 	=> "ticker_date", "value" 	=> "true"),
                            "description"               => __( "Enter the format in which dates should be shown. You can find more information here:", "ts_visual_composer_extend" ) . '<br/><a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( "WordPress Date + Time Formats", "ts_visual_composer_extend" ) . '</a>'
                        ),
                        /*array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Time Format", "ts_visual_composer_extend" ),
                            "param_name"                => "time_format",
                            "value"                     => "l, g:i A",
                            "description"               => __( "Enter the format in which times should be shown. You can find more information here:", "ts_visual_composer_extend" ) . '<br/><a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( "WordPress Date + Time Formats", "ts_visual_composer_extend" ) . '</a>'
                        ),*/	
                        // Header Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "",
							"seperator"					=> "Header Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Header Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Header", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_title",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the header section for the ticker.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                            "group" 			        => "Header Settings",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Header Position", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_side",
							"value"             		=> array(
								__( "Left", "ts_visual_composer_extend" )                    	=> "left",
								__( "Right", "ts_visual_composer_extend" )                     	=> "right"
							),
							"description"       		=> __( "Define how the link should be opened.", "ts_visual_composer_extend" ),
							"dependency"        		=> array("element" 	=> "ticker_title", "value" 	=> "true"),
							"group" 			        => "Header Settings",
						),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Header Text", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_header",
                            "value"                     => "Breaking News",
                            "description"               => __( "Enter the text to be used in the header section.", "ts_visual_composer_extend" ),
							"dependency"        		=> array("element" 	=> "ticker_title", "value" 	=> "true"),
                            "group" 			        => "Header Settings",
                        ),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Header Background", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_background",
                            "value"                     => "#D10000",
                            "description"               => __( "Define the background color for the ticker header.", "ts_visual_composer_extend" ),
                            "dependency"        		=> array("element" 	=> "ticker_title", "value" 	=> "true"),
                            "group" 			        => "Header Settings",
                        ),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Header Color", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_color",
                            "value"                     => "#ffffff",
                            "description"               => __( "Define the text color for the ticker header.", "ts_visual_composer_extend" ),
                            "dependency"        		=> array("element" 	=> "ticker_title", "value" 	=> "true"),
                            "group" 			        => "Header Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Add Icon to Header", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_symbol",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to add an icon to the header text.", "ts_visual_composer_extend" ),
                            "dependency"        		=> array("element" 	=> "ticker_title", "value" 	=> "true"),
                            "group" 			        => "Header Settings",
                        ),
						array(
							"type"              		=> "icons_panel",
							"heading"           		=> __( "Ticker Icon", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_icon",
							"value"             		=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_List_Icons_Full,
							"description"       		=> __( "Select the icon you want to display.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "ticker_symbol", 'value' => 'true' ),
							"group" 			        => "Header Settings",
						),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Icon Color", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_paint",
                            "value"                     => "#ffffff",
                            "description"               => __( "Define the color for the icon.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array( 'element' => "ticker_symbol", 'value' => 'true' ),
                            "group" 			        => "Header Settings",
                        ),
                        // Ticker Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
                            "value"                     => "",
							"seperator"					=> "General Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Ticker Settings",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Ticker Direction", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_direction",
							"value"             		=> array(
								__( "Up", "ts_visual_composer_extend" )                    		=> "up",
								__( "Down", "ts_visual_composer_extend" )                     	=> "down"
							),
							"description"       		=> __( "Define in which direction the ticker should be animated to.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 			        => "Ticker Settings",
						),						
						array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Ticker Break-Point", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_break",
                            "value"                     => "480",
                            "min"                       => "360",
                            "max"                       => "1980",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define the the breakpoint at which the ticker should switch to a two-row layout.", "ts_visual_composer_extend" ),
                            "dependency" 				=> "",
                            "group" 			        => "Ticker Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Auto-Play", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_auto",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to auto-play the ticker on page load.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                            "group" 			        => "Ticker Settings",
                        ),
						array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Auto-Play Speed", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_speed",
                            "value"                     => "3000",
                            "min"                       => "1000",
                            "max"                       => "20000",
                            "step"                      => "100",
                            "unit"                      => 'ms',
                            "description"               => __( "Define the speed used to auto-play the ticker.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array("element" 	=> "ticker_auto", "value" 	=> "true"),
                            "group" 			        => "Ticker Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Stop on Hover", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_hover",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to stop the auto-play while hovering over the ticker.", "ts_visual_composer_extend" ),
                            "dependency"                => array("element" 	=> "ticker_auto", "value" 	=> "true"),
                            "group" 			        => "Ticker Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Navigation", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_controls",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show left/right navigation buttons for the ticker.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                            "group" 			        => "Ticker Settings",
                        ),
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_5",
                            "value"                     => "",
							"seperator"					=> "Fixed Ticker",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Ticker Settings",
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Fixed Ticker", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_fixed",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the ticker fixed on the screen.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                            "group" 			        => "Ticker Settings",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Ticker Position", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_position",
							"value"             		=> array(
								__( "Top", "ts_visual_composer_extend" )                    		=> "top",
								__( "Bottom", "ts_visual_composer_extend" )                     	=> "bottom"
							),
							"description"       		=> __( "Define in which direction the ticker should be animated to.", "ts_visual_composer_extend" ),
							"dependency"        		=> array("element" 	=> "ticker_fixed", "value" 	=> "true"),
							"group" 			        => "Ticker Settings",
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Position Adjustment", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_adjustment",
                            "value"                     => "0",
                            "min"                       => "0",
                            "max"                       => "100",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define an optional position adjustment to acccount for things such as a fixed menu.", "ts_visual_composer_extend" ),
							"dependency"        		=> array("element" 	=> "ticker_fixed", "value" 	=> "true"),
                            "group" 			        => "Ticker Settings",
                        ),
						// Ticker Border
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_4",
                            "value"                     => "",
							"seperator"					=> "Ticker Border",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Ticker Settings",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Border Type", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_border_type",
							"width"             		=> 300,
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )                          => "",
								__( "Solid Border", "ts_visual_composer_extend" )                  => "solid",
								__( "Dotted Border", "ts_visual_composer_extend" )                 => "dotted",
								__( "Dashed Border", "ts_visual_composer_extend" )                 => "dashed",
								__( "Double Border", "ts_visual_composer_extend" )                 => "double",
								__( "Grouve Border", "ts_visual_composer_extend" )                 => "groove",
								__( "Ridge Border", "ts_visual_composer_extend" )                  => "ridge",
								__( "Inset Border", "ts_visual_composer_extend" )                  => "inset",
								__( "Outset Border", "ts_visual_composer_extend" )                 => "outset",
							),
							"description"      	 		=> __( "Select the type of border around the ticker.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
                            "group" 			        => "Ticker Settings",
						),
						array(
							"type"              		=> "nouislider",
							"heading"           		=> __( "Border Thickness", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_border_thick",
							"value"             		=> "1",
							"min"               		=> "1",
							"max"               		=> "10",
							"step"              		=> "1",
							"unit"              		=> 'px',
							"description"       		=> __( "Define the thickness of the icon / image border.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "ticker_border_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
                            "group" 			        => "Ticker Settings",
						),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Border Color", "ts_visual_composer_extend" ),
                            "param_name"                => "ticker_border_color",
                            "value"                     => "#ededed",
                            "description"               => __( "Define the border color around the ticker.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array( 'element' => "ticker_border_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
                            "group" 			        => "Ticker Settings",
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Ticker Border Radius", "ts_visual_composer_extend" ),
							"param_name"        		=> "ticker_border_radius",
							"value"             		=> array(
								__( "None", "ts_visual_composer_extend" )                          => "",
								__( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
								__( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
								__( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
							),
							"description"       		=> __( "Define the optional radius for the ticker border.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "ticker_border_type", 'value' => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Border_Type_Values ),
							"group" 			        => "Ticker Settings",
						),
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_6",
                            "value"                     => "",
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
                            "type"                      => "load_file",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "el_file1",
                            "value"                     => "",
                            "file_type"                 => "js",
							"file_id"         			=> "ts-extend-element",
                            "file_path"                 => "js/ts-visual-composer-extend-element.min.js",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
						array(
							"type"              		=> "load_file",
							"heading"           		=> __( "", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_file2",
							"value"             		=> "Animation Files",
							"file_type"         		=> "css",
							"file_id"         			=> "ts-extend-animations",
							"file_path"         		=> "css/ts-visual-composer-extend-animations.min.css",
							"description"       		=> __( "", "ts_visual_composer_extend" )
						),
                    ))
                );
            }
        }
	}
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_TS_VCSC_Posts_Ticker_Standalone extends WPBakeryShortCode {
		public function singleParamHtmlHolder($param, $value) {
			$output 		= '';
			// Compatibility fixes
			$old_names 		= array('yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange');
			$new_names 		= array('alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning');
			$value 			= str_ireplace($old_names, $new_names, $value);
			//$value 		= __($value, "ts_visual_composer_extend");
			//
			$param_name 	= isset($param['param_name']) ? $param['param_name'] : '';
			$heading 		= isset($param['heading']) ? $param['heading'] : '';
			$type 			= isset($param['type']) ? $param['type'] : '';
			$class 			= isset($param['class']) ? $param['class'] : '';

            if (isset($param['holder']) === true && in_array($param['holder'], array('div', 'span', 'p'))) {
                $output .= '<'.$param['holder'].' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">'.$value.'</'.$param['holder'].'>';
            } else if (isset($param['holder']) === true && $param['holder'] == 'input') {
                $output .= '<'.$param['holder'].' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.$value.'">';
            } else if (isset($param['holder']) === true && in_array($param['holder'], array('img', 'iframe'))) {
				$output .= '<'.$param['holder'].' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="'.$value.'">';
			} else if (isset($param['holder']) === true && $param['holder'] == 'imagelist') {
				$images_ids = empty($value) ? array() : explode(',', trim($value));
				$output .= '<ul class="attachment-thumbnails'.(empty($images_ids) ? ' image-exists' : '' ).'" data-name="' . $param_name . '">';
					foreach($images_ids as $image) {
						$img = wpb_getImageBySize(array( 'attach_id' => (int)$image, 'thumb_size' => 'thumbnail' ));
						$output .= ( $img ? '<li>'.$img['thumbnail'].'</li>' : '<li><img width="150" height="150" test="'.$image.'" src="' . WPBakeryVisualComposer::getInstance()->assetURL('vc/blank.gif') . '" class="attachment-thumbnail" alt="" title="" /></li>');
					}
				$output .= '</ul>';
				$output .= '<a href="#" class="column_edit_trigger' . ( !empty($images_ids) ? ' image-exists' : '' ) . '" style="margin-bottom: 10px;">' . __( 'Add or Remove Featured Media', "ts_visual_composer_extend" ) . '</a>';
            }
			
            if (isset($param['admin_label']) && $param['admin_label'] === true) {
                $output .= '<span style="width: 100%; display: block;" class="vc_admin_label admin_label_'.$param['param_name'].(empty($value) ? ' hidden-label' : '').'"><label>'. $param['heading'] .'</label>: '.$value.'</span>';
            }

			return $output;
		}
	};
}

// Initialize "TS Poststicker" Class
if (class_exists('TS_Poststicker')) {
	$TS_Poststicker = new TS_Poststicker;
}