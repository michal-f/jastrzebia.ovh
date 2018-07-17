<?php
if (!class_exists('TS_Timelines')){
	class TS_Timelines {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                              	array($this, 'TS_VCSC_Add_Timeline_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                	array($this, 'TS_VCSC_Add_Timeline_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',								array($this, 'TS_VCSC_Add_Timeline_Elements'), 9999999);
            }
			add_shortcode('TS_VCSC_Timeline_Single',          			array($this, 'TS_VCSC_Timeline_Function_Single'));
			add_shortcode('TS_VCSC_Timeline_Break',          			array($this, 'TS_VCSC_Timeline_Function_Break'));
			add_shortcode('TS_VCSC_Timeline_Container',         		array($this, 'TS_VCSC_Timeline_Function_Container'));
		}
		
		// Load Isotope Customization at Page End
		function TS_VCSC_Timeline_Function_Isotope () {
			echo '<script data-cfasync="false" type="text/javascript" src="' . TS_VCSC_GetResourceURL('js/jquery.vcsc.isotope.custom.min.js') . '"></script>';
		}
		// Timeline Item
		function TS_VCSC_Timeline_Function_Single ($atts, $content = null) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			ob_start();
	
			extract( shortcode_atts( array(
				'full_width'				=> 'false',
				'featured_media'			=> 'none',
				
				'featured_image'			=> '',
				'featured_image_alt'		=> '',
				
				'featured_slider'			=> '',
				
				'featured_youtube_url'		=> '',
				'featured_youtube_related'	=> 'false',
				'featured_youtube_play'		=> 'false',
				
				'featured_dailymotion_url'	=> '',
				'featured_dailymotion_play'	=> 'false',
				
				'featured_vimeo_url'		=> '',
				'featured_vimeo_play'		=> 'false',
				
				'featured_media_height'		=> 'height: 100%;',
				'featured_media_width'		=> 100,
				'featured_media_align'		=> 'center',
				
				'link'						=> '',
				'tooltip_css'				=> 'false',
				'tooltip_position'			=> 'ts-simptip-position-top',
				'tooltip_style'				=> '',
				
				'button_align'				=> 'center',
				'button_width'				=> 100,
				'button_type'				=> 'square',
				'button_square'				=> 'ts-button-3d',
				'button_rounded'			=> 'ts-button-3d ts-button-rounded',
				'button_pill'				=> 'ts-button-3d ts-button-pill',
				'button_circle'				=> 'ts-button-3d ts-button-circle',
				'button_size'				=> '',
				'button_wrapper'			=> 'false',
				'button_text'				=> '',
				'button_change'				=> 'false',
				'button_color'				=> '#666666',
				'button_font'				=> 18,
				
				'thumbnail_position'		=> 'bottom',
				'thumbnail_height'			=> 100,
				
				'lightbox_featured'			=> 'true',
				'lightbox_group'			=> 'true',
				'lightbox_group_name'		=> '',
				'lightbox_size'				=> 'full',
				'lightbox_effect'			=> 'random',
				'lightbox_autoplay'			=> 'false',
				'lightbox_speed'			=> 5000,
				'lightbox_social'			=> 'true',
				'lightbox_backlight'		=> 'auto',
				'lightbox_backlight_color'	=> '#ffffff',
				
				'number_images'				=> 1,
				'auto_height'				=> 'true',
				'page_rtl'					=> 'false',
				'auto_play'					=> 'false',
				'show_bar'					=> 'false',
				'bar_color'					=> '#dd3333',
				'show_speed'				=> 5000,
				'stop_hover'				=> 'true',
				'show_navigation'			=> 'true',
				"items_loop"				=> 'false',
				'animation_in'				=> 'ts-viewport-css-flipInX',
				'animation_out'				=> 'ts-viewport-css-slideOutDown',
				'animation_mobile'			=> 'false',
				
				'date_text'					=> '',
				
				'title_text'				=> '',
				'title_align'				=> 'center',
				'title_color'				=> '#7c7979',
				
				'icon'						=> '',
				'icon_color'				=> '#7c7979',

				'el_id' 					=> '',
				'el_class'                  => '',
				'css'						=> '',
			), $atts ));
			
			$media_string					= '';
			$output 						= '';
		
			$randomizer						= mt_rand(999999, 9999999);
		
			if (!empty($el_id)) {
				$timeline_id				= $el_id;
			} else {
				$timeline_id				= 'ts-vcsc-timeline-item-' . $randomizer;
			}
			
			// Media Layout
			if ($featured_media_align == "center") {
				$image_alignment				= "margin: 5px auto; float: none;";
			} else if ($featured_media_align == "left") {
				$image_alignment				= "margin: 5px 0; float: left;";
			} else if ($featured_media_align == "right") {
				$image_alignment				= "margin: 5px 0; float: right;";
			}
			$image_dimensions					= 'width: 100%; height: auto;';
			$parent_dimensions					= 'width: ' . $featured_media_width . '%; ' . $featured_media_height;
			if ($lightbox_backlight == "auto") {
				$nacho_color					= '';
			} else if ($lightbox_backlight == "custom") {
				$nacho_color					= 'data-color="' . $lightbox_backlight_color . '"';
			} else if ($lightbox_backlight == "hideit") {
				$nacho_color					= 'data-color="#000000"';
			}
			// Adjustment for Inline Edit Mode of VC
			if ((function_exists('vc_is_inline')) && (vc_is_inline())) {
				$full_width						= 'true';
				$vcinline_active				= 'true';
				$vcinline_class					= '';
				$vcinline_slider				= 'owl-carousel-edit';
			} else {
				$full_width						= $full_width;
				$vcinline_active				= 'false';
				$vcinline_class					= '';
				$vcinline_slider				= 'owl-carousel';
			}
			
			// Featured Media: Image
			if ($featured_media == 'image') {
				if (!empty($featured_image)) {
					$media_image 				= wp_get_attachment_image_src($featured_image, 'large');
					$image_extension 			= pathinfo($media_image[0], PATHINFO_EXTENSION);
					if ($featured_image_alt != "") {
						$alt_attribute			= $featured_image_alt;
					} else {
						$alt_attribute			= basename($media_image[0], "." . $image_extension);
					}
					if ($lightbox_featured == "false") {
						$media_string .= '<div class="ts-timeline-media" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
							$media_string .= '<img class="" src="' . $media_image[0] . '" alt="' . $alt_attribute . '" style="max-width: ' . $media_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
						$media_string .= '</div>';
					} else {
						$media_string .= '<div class="ts-timeline-media nchgrid-item nchgrid-tile nch-lightbox-image" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
							$media_string .= '<a href="' . $media_image[0] . '" class="nch-lightbox-media" data-title="' . $title_text . '" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
								$media_string .= '<img src="' . $media_image[0] . '" alt="' . $alt_attribute . '" title="" style="max-width: ' . $media_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
								$media_string .= '<div class="nchgrid-caption"></div>';
								if (!empty($title_text)) {
									$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
								}
							$media_string .= '</a>';
						$media_string .= '</div>';
					}
				}
			}
			// Featured Media: Image Slider
			if ($featured_media == 'slider') {
				if (!empty($featured_slider)) {
					$featured_slider 			= explode(',', $featured_slider);
					$i 							= -1;
					$b							= 0;
					$nachoLength 				= count($featured_slider) - 1;					
					// Add Progressbar
					if (($auto_play == "true") && ($show_bar == "true") && ($frontend_edit == "false")) {
						$media_string .= '<div id="ts-owlslider-progressbar-' . $randomizer . '" class="ts-owlslider-progressbar-holder" style=""><div class="ts-owlslider-progressbar" style="background: ' . $bar_color . '; height: 100%; width: 0%;"></div></div>';
					}					
					if ((($auto_play == "true") && ($show_bar == "true")) || ($show_navigation == "true")) {
						$slider_margin			= 'margin-top: -10px;';
					} else {
						$slider_margin			= 'margin-top: 5px;';
					}
					if ((($auto_play == "true") || ($show_navigation == "true")) && (count($featured_slider) > 1)) {
						$media_string .= '<div id="ts-owlslider-controls-' . $randomizer . '" class="ts-owlslider-controls">';
							if ($show_navigation == "true") {
								$media_string .= '<div id="ts-owlslider-controls-next-' . $randomizer . '" class="ts-owlslider-controls-next"><span class="ts-ecommerce-arrowright5"></span></div>';
								$media_string .= '<div id="ts-owlslider-controls-prev-' . $randomizer . '" class="ts-owlslider-controls-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
							}
							if ($auto_play == "true") {
								$media_string .= '<div id="ts-owlslider-controls-play-' . $randomizer . '" class="ts-owlslider-controls-play active"><span class="ts-ecommerce-pause"></span></div>';
							}
						$media_string .= '</div>';
					}					
					$media_string .= '<div id="ts-timeline-gallery-slider-' . $randomizer . '" class="ts-timeline-gallery-slider ' . $vcinline_slider . '" style="' . $slider_margin . '" data-id="' . $randomizer . '" data-items="' . $number_images . '" data-rtl="' . $page_rtl . '" data-loop="' . $items_loop . '" data-navigation="' . $show_navigation . '" data-mobile="' . $animation_mobile . '" data-animationin="' . $animation_in . '" data-animationout="' . $animation_out . '" data-height="' . $auto_height . '" data-play="' . $auto_play . '" data-bar="' . $show_bar . '" data-color="' . $bar_color . '" data-speed="' . $show_speed . '" data-hover="' . $stop_hover . '">';
						foreach ($featured_slider as $single_image) {
							$i++;
							$modal_image			= wp_get_attachment_image_src($single_image, $lightbox_size);
							$image_extension		= pathinfo($modal_image[0], PATHINFO_EXTENSION);
							if ($lightbox_featured == "false") {
								if ((($i == 0) && ($vcinline_active == "true")) || ($vcinline_active == "false")) {
									$media_string .= '<div id="' . $timeline_id . '-' . $i .'-parent" class="' . $timeline_id . '-parent ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-image" style="">';
										$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $modal_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
										$media_string .= '<div class="nchgrid-caption"></div>';
									$media_string .= '</div>';
								}
							} else {
								if (($i == $nachoLength) && ($vcinline_active == "false")) {
									$media_string .= '<div id="' . $timeline_id . '-' . $i .'-parent" class="' . $timeline_id . '-parent ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-image" style="">';
										$media_string .= '<a id="' . $timeline_id . '-' . $i .'" href="' . $modal_image[0] . '" data-title="" class="nch-lightbox-media ts-hover-image ' . $timeline_id . '-slider-image" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="f" data-autoplay="' . ($lightbox_autoplay == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-thumbsize="' . $thumbnail_height . '" data-thumbs="' . $thumbnail_position . '" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $modal_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
										$media_string .= '</a>';
									$media_string .= '</div>';
								} else if ((($i == 0) && ($vcinline_active == "true")) || ($vcinline_active == "false")) {
									$media_string .= '<div id="' . $timeline_id . '-' . $i .'-parent" class="' . $timeline_id . '-parent ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-image" style="">';
										$media_string .= '<a id="' . $timeline_id . '-' . $i .'" href="' . $modal_image[0] . '" data-title="" class="nch-lightbox-media ts-hover-image ' . $timeline_id . '-slider-image" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" ' . $nacho_color . '>';
											$media_string .= '<img src="' . $modal_image[0] . '" style="max-width: ' . $modal_image[1] . 'px; padding: 0; margin: 0 auto; display: block; ' . $image_dimensions . '">';
											$media_string .= '<div class="nchgrid-caption"></div>';
										$media_string .= '</a>';
									$media_string .= '</div>';
								}
							}
						}
					$media_string .= '</div>';
				}
			}
			// Featured Media: YouTube
			if (($featured_media == 'youtube_default') || ($featured_media == 'youtube_custom') || ($featured_media == 'youtube_embed')) {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $featured_youtube_url)) {
					$featured_youtube_url		= $featured_youtube_url;
				} else {
					$featured_youtube_url		= 'https://www.youtube.com/watch?v=' . $featured_youtube_url;
				}
				if ($featured_youtube_play == "true") {
					$video_autoplay				= '?autoplay=1';
				} else {
					$video_autoplay				= '?autoplay=0';
				}
				if ($featured_youtube_related == "true") {
					$video_related				= '&rel=1';
				} else {
					$video_related				= '&rel=0';
				}
				if (($featured_media == 'youtube_default')) {
					$media_image 				= TS_VCSC_VideoImage_Youtube($featured_youtube_url);
					$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-youtube" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<a href="' . $featured_youtube_url . '" class="nch-lightbox-media" data-title="' . $title_text . '" data-related="' . $video_related . '" data-videoplay="' . $video_autoplay . '" data-type="youtube" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$media_string .= '<div class="nchgrid-caption"></div>';
							if (!empty($title_text)) {
								$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
							}
						$media_string .= '</a>';
					$media_string .= '</div>';
				} else if ($featured_media == 'youtube_custom') {
					if (!empty($featured_image)) {
						$media_image			= wp_get_attachment_image_src($featured_image, 'full');
						$media_image			= $media_image[0];
						$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
						if ($featured_image_alt != "") {
							$alt_attribute		= $featured_image_alt;
						} else {
							$alt_attribute		= basename($media_image, "." . $image_extension);
						}
					} else {
						$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_youtube.jpg');
						$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
						if ($featured_image_alt != "") {
							$alt_attribute		= $featured_image_alt;
						} else {
							$alt_attribute		= basename($media_image, "." . $image_extension);
						}
					}
					$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-youtube" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<a href="' . $featured_youtube_url . '" class="nch-lightbox-media" data-title="' . $title_text . '" data-related="' . $video_related . '" data-videoplay="' . $video_autoplay . '" data-type="youtube" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$media_string .= '<div class="nchgrid-caption"></div>';
							if (!empty($title_text)) {
								$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
							}
						$media_string .= '</a>';
					$media_string .= '</div>';
				} else if ($featured_media == 'youtube_embed') {
					$video_id 					= TS_VCSC_VideoID_Youtube($featured_youtube_url);
					$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<iframe width="100%" height="auto" src="//www.youtube.com/embed/' . $video_id . $video_autoplay . $video_related . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
					$media_string .= '</div>';
				}
			}
			// Featured Media: DailyMotion
			if (($featured_media == 'dailymotion_default') || ($featured_media == 'dailymotion_custom') || ($featured_media == 'dailymotion_embed')) {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $featured_dailymotion_url)) {
					$featured_dailymotion_url	= $featured_dailymotion_url;
				} else {
					$featured_dailymotion_url	= 'http://www.dailymotion.com/video/' . $featured_dailymotion_url;
				}
				if ($featured_dailymotion_play == "true") {
					$video_autoplay				= '?autoplay=1';
				} else {
					$video_autoplay				= '?autoplay=0';
				}
				if (($featured_media == 'dailymotion_default')) {
					$media_image 				= TS_VCSC_VideoImage_Motion($featured_dailymotion_url);
					$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-motion" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<a href="' . $featured_dailymotion_url . '" class="nch-lightbox-media" data-title="' . $title_text . '" data-videoplay="' . $video_autoplay . '" data-type="dailymotion" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$media_string .= '<div class="nchgrid-caption"></div>';
							if (!empty($title_text)) {
								$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
							}
						$media_string .= '</a>';
					$media_string .= '</div>';
				} else if ($featured_media == 'dailymotion_custom') {
					if (!empty($featured_image)) {
						$media_image			= wp_get_attachment_image_src($featured_image, 'full');
						$media_image			= $media_image[0];
						$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
						if ($featured_image_alt != "") {
							$alt_attribute		= $featured_image_alt;
						} else {
							$alt_attribute		= basename($media_image, "." . $image_extension);
						}
					} else {
						$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_motion.jpg');
						$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
						if ($featured_image_alt != "") {
							$alt_attribute		= $featured_image_alt;
						} else {
							$alt_attribute		= basename($media_image, "." . $image_extension);
						}
					}
					$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-motion" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<a href="' . $featured_dailymotion_url . '" class="nch-lightbox-media" data-title="' . $title_text . '" data-videoplay="' . $video_autoplay . '" data-type="dailymotion" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$media_string .= '<div class="nchgrid-caption"></div>';
							if (!empty($title_text)) {
								$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
							}
						$media_string .= '</a>';
					$media_string .= '</div>';
				} else if ($featured_media == 'dailymotion_embed') {
					$video_id 					= TS_VCSC_VideoID_Motion($featured_dailymotion_url);
					$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<iframe width="100%" height="auto" src="http://www.dailymotion.com/embed/video/' . $video_id . $video_autoplay . '&forcedQuality=hq&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
					$media_string .= '</div>';
				}
			}
			// Featured Media: Vimeo
			if (($featured_media == 'vimeo_default') || ($featured_media == 'vimeo_custom') || ($featured_media == 'vimeo_embed')) {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $featured_vimeo_url)) {
					$featured_vimeo_url	= $featured_vimeo_url;
				} else {
					$featured_vimeo_url	= 'http://www.vimeo.com/video/' . $featured_vimeo_url;
				}
				if ($featured_vimeo_play == "true") {
					$video_autoplay				= '?autoplay=1';
				} else {
					$video_autoplay				= '?autoplay=0';
				}
				if (($featured_media == 'vimeo_default')) {
					$media_image 				= TS_VCSC_VideoImage_Vimeo($featured_vimeo_url);
					$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-vimeo" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<a href="' . $featured_vimeo_url . '" class="nch-lightbox-media" data-title="' . $title_text . '" data-videoplay="' . $video_autoplay . '" data-type="vimeo" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$media_string .= '<div class="nchgrid-caption"></div>';
							if (!empty($title_text)) {
								$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
							}
						$media_string .= '</a>';
					$media_string .= '</div>';
				} else if ($featured_media == 'vimeo_custom') {
					if (!empty($featured_image)) {
						$media_image			= wp_get_attachment_image_src($featured_image, 'full');
						$media_image			= $media_image[0];
						$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
						if ($featured_image_alt != "") {
							$alt_attribute		= $featured_image_alt;
						} else {
							$alt_attribute		= basename($media_image, "." . $image_extension);
						}
					} else {
						$media_image			= TS_VCSC_GetResourceURL('images/defaults/default_vimeo.jpg');
						$image_extension		= pathinfo($media_image, PATHINFO_EXTENSION);
						if ($featured_image_alt != "") {
							$alt_attribute		= $featured_image_alt;
						} else {
							$alt_attribute		= basename($media_image, "." . $image_extension);
						}
					}
					$media_string .= '<div class="nch-holder nchgrid-item nchgrid-tile nch-lightbox-vimeo" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<a href="' . $featured_vimeo_url . '" class="nch-lightbox-media" data-title="' . $title_text . '" data-videoplay="' . $video_autoplay . '" data-type="vimeo" rel="' . ($lightbox_group == "true" ? "timelinegroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$media_string .= '<img src="' . $media_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$media_string .= '<div class="nchgrid-caption"></div>';
							if (!empty($title_text)) {
								$media_string .= '<div class="nchgrid-caption-text">' . $title_text . '</div>';
							}
						$media_string .= '</a>';
					$media_string .= '</div>';
				} else if ($featured_media == 'vimeo_embed') {
					$video_id 					= TS_VCSC_VideoID_vimeo($featured_vimeo_url);
					$media_string .= '<div class="ts-video-container" style="' . $parent_dimensions . '; ' . $image_alignment . '">';
						$media_string .= '<iframe width="100%" height="auto" src="//player.vimeo.com/video/' . $video_id . $video_autoplay . '&wmode=opaque" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
					$media_string .= '</div>';
				}
			}
			
			// Link Button
			if (!empty($link)) {
				// Link Values
				$link 							= ($link=='||') ? '' : $link;
				$link 							= vc_build_link($link);
				$a_href							= $link['url'];
				$a_title 						= $link['title'];
				$a_target 						= $link['target'];
				// Tooltip
				if ($tooltip_css == "true") {
					if (strlen($a_title) != 0) {
						$button_tooltipclasses	= " ts-simptip-multiline " . $tooltip_style . " " . $tooltip_position;
						$button_tooltipcontent	= ' data-tstooltip="' . $a_title . '"';
					} else {
						$button_tooltipclasses	= "";
						$button_tooltipcontent	= "";
					}
				} else {
					$button_tooltipclasses		= "";
					if (strlen($a_title) != 0) {
						$button_tooltipcontent	= ' title="' . $a_title . '"';
					} else {
						$button_tooltipcontent	= "";
					}
				}
				// Button Type
				if ($button_type == "square") {
					$button_style				= $button_square;
					$button_font				= '';
				} else if ($button_type == "rounded") {
					$button_style				= $button_rounded;
					$button_font				= '';
				} else if ($button_type == "pill"){
					$button_style				= $button_pill;
					$button_font				= '';
				} else if ($button_type == "circle") {
					$button_style				= $button_circle;
					$button_font				= 'font-size: ' . $button_font . 'px;';
				}
				// Button Alignment
				if ($button_align == "center") {
					$button_align				= 'text-align: center;';
				} else if ($button_align == "left") {
					$button_align				= 'text-align: left';
				} else if ($button_align == "right") {
					$button_align				= 'text-align: right';
				}
				// Button Text Color
				if ($button_change == "true") {
					$button_color				= 'color: ' . $button_color . ';';
				} else {
					$button_color				= '';
				}
				$button_string					= '';
				
				if (!empty($a_href)) {
					$button_string .= '<div class="ts-button-parent ts-button-type-' . $button_type . '" style="' . $button_align . '">';
						if ($button_wrapper == "true") {
							$button_string .= '<div class="ts-button-wrap" style="">';
						}
							$button_string .= '<a href="' . $a_href . '" target="' . trim($a_target) . '" style="' . $button_font . ' width: ' . $button_width . '%;" class="ts-button ' . $button_style . ' ' . $button_size . ' ' . $button_tooltipclasses . '" ' . $button_tooltipcontent . '>';
								$button_string .= '<span class="ts-button-text" style="display: inline; ' . $button_color . '">' . $button_text . '</span>';
							$button_string .= '</a>';
						if ($button_wrapper == "true") {
							$button_string .= '</div>';
						}
					$button_string .= '</div>';
				} else {
					$link						= '';
				}
			} else {
				$button_string					= '';
			}
			// Event Icon	
			if ((empty($icon)) && (empty($content)) && (empty($featured_image))) {
				$title_margin					= 'margin: 0;';
			} else {
				$title_margin					= '';
			}
			// Column Adjustment for Full Width Event
			if (($full_width == "true") && (empty($title_text)) && (empty($icon) || ($icon == "transparent")) && (empty($content)) && (empty($button_string))) {
				$columnA_adjust					= 'width: 100%; margin: 0;';
				$columnB_adjust					= 'display: none; width: 0;';
			} else if (($full_width == "true") && ($featured_media == "none")) {
				$columnA_adjust					= 'display: none; width: 0; margin: 0;';
				$columnB_adjust					= 'width: 100%; margin: 0;';
			} else {
				$columnA_adjust					= '';
				$columnB_adjust					= '';
			}
			// Margin Adjustment for Full Width Element
			if (($full_width == "true") && (!empty($date_text))) {
				$margin_Adjust					= 'margin: 25px 10px 0px 10px;';
			} else if (($full_width == "true") && (empty($date_text))) {
				$margin_Adjust					= 'margin: 0 10px;';
			} else if (!empty($date_text)) {
				$margin_Adjust					= 'margin-top: 25px;';
			} else {
				$margin_Adjust					= 'margin-top: 0;';
			}
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline-list-item ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_Single', $atts);
			} else {
				$css_class	= 'ts-timeline-list-item ' . $el_class;
			}
			
			if ((function_exists('vc_is_inline')) && (vc_is_inline())) {
				$vc_inline_style				= ' display: block;';
			} else {
				$vc_inline_style				= '';
			}
			
            $output .= '<li id="' . $timeline_id . '" class="' . $css_class . ' ' . ($full_width == "true" ? "ts-timeline-full" : "") . ' ' . (!empty($date_text) ? "ts-timeline-date-true" : "ts-timeline-date-false") . '" style="' . ($full_width == "true" ? "width: 100%;" : "") . ' ' . $vc_inline_style . '">';
                $output .= '<div class="ts-timeline-column">';
                    $output .= '<div class="ts-timeline-text-wrap ' . (!empty($date_text) ? "ts-timeline-text-wrap-date" : "ts-timeline-text-wrap-nodate") . '" style="' . $margin_Adjust . '">';
						if (!empty($date_text)) {
							$output .= '<div class="ts-timeline-date"><span class="ts-timeline-date-connect"><span class="ts-timeline-date-text">' . $date_text . '</span></span></div>';
						}
						if ($full_width == "true") {
							$output .= '<div class="ts-timeline-full-colA" style="' . $columnA_adjust . '">';
								$output .= $media_string;
							$output .= '</div>';
							$output .= '<div class="ts-timeline-full-colB" style="' . $columnB_adjust . '">';
								if (!empty($title_text)) {
									$output .= '<h3 class="ts-timeline-title" style="color: ' . $title_color . '; text-align: ' . $title_align . '; ' . (empty($content) && (empty($icon)) ? "border: none; margin-bottom: 0; padding-bottom: 0;" : "") . ' ' . $title_margin . '">' . $title_text . '</h3>';
								}
								if (((!empty($icon)) && (($icon) != "transparent")) || (!empty($content)) || (!empty($link))) {
									$output .= '<div style="width: 100%; display: block; float: left; position: relative; padding-bottom: 15px; ' . (empty($content) && !empty($icon) ? "height: 60px;" : "") . '">';
										if (!empty($content)) {
											$output .= '<div class="ts-timeline-text-wrap-inner" style="' . (empty($icon) ? "width: 100%; height: 100%; left: 0;" : " left: 0;") . '">';
												if (function_exists('wpb_js_remove_wpautop')){
													$output .= '<div class="ts-timeline-text" style="">' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>';
												} else {
													$output .= '<div class="ts-timeline-text" style="">' . do_shortcode($content) . '</div>';
												}
											$output .= '</div>';
										}
										if ((!empty($icon)) && (($icon) != "transparent")) {
											$output .= '<div class="ts-timeline-icon ts-timeline-icon-full" style="' . (empty($content) ? "display: inline-block; width: 100%; left: 0; margin: 0 0 0 2%;" : "left: 80%;") . '"><i class="' . $icon . '" style="color: ' . $icon_color . ';"></i></div>';
										}
										if (!empty($button_string)) {
											$output .= '<div class="ts-timeline-button">';
												$output .= $button_string;
											$output .= '</div>';
										}
									$output .= '</div>';
								}
							$output .= '</div>';
						} else {
							$output .= $media_string;
							if (!empty($title_text)) {
								$output .= '<h3 class="ts-timeline-title" style="color: ' . $title_color . '; text-align: ' . $title_align . '; ' . (empty($content) && (empty($icon)) ? "border: none; margin-bottom: 0; padding-bottom: 0;" : "") . ' ' . $title_margin . '">' . $title_text . '</h3>';
							}
							if (((!empty($icon)) && (($icon) != "transparent")) || (!empty($content))) {
								$output .= '<div style="width: 100%; display: block; float: left; position: relative; padding-bottom: 15px; ' . (empty($content) && !empty($icon) ? "height: 60px;" : "") . '">';
									if ((!empty($icon)) && (($icon) != "transparent")) {
										$output .= '<div class="ts-timeline-icon ts-timeline-icon-half" style="' . (empty($content) ? "display: inline-block; width: 100%; left: 0;" : "") . '"><i class="' . $icon . '" style="color: ' . $icon_color . ';"></i></div>';
									}
									if (!empty($content)) {
										$output .= '<div class="ts-timeline-text-wrap-inner" style="' . (empty($icon) ? "width: 100%; height: 100%; left: 0;" : "") . '">';
											if (function_exists('wpb_js_remove_wpautop')){
												$output .= '<div class="ts-timeline-text" style="">' . wpb_js_remove_wpautop(do_shortcode($content), true) . '</div>';
											} else {
												$output .= '<div class="ts-timeline-text" style="">' . do_shortcode($content) . '</div>';
											}
										$output .= '</div>';
									}
								$output .= '</div>';
								if (!empty($link)) {
									$output .= '<div class="ts-timeline-button">';
										$output .= $button_string;
									$output .= '</div>';
								}
							}
						}
                        $output .= '<div class="clearFixMe"></div>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</li>';
			
			echo $output;
			
			$myvariable = ob_get_clean();
			return $myvariable;
		}		
		// Timeline Break
		function TS_VCSC_Timeline_Function_Break ($atts, $content = null) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			ob_start();

			extract( shortcode_atts( array(
				'color_background'			=> '#ededed',
				'title_text'				=> '',
				'title_align'				=> 'center',
				'title_color'				=> '#7c7979',
				'content_text'				=> '',
				'content_align'				=> 'center',
				'content_color'				=> '#7c7979',
				'el_id' 					=> '',
				'el_class'                  => '',
				'css'						=> '',
			), $atts ));
		
			if (!empty($el_id)) {
				$timeline_id				= $el_id;
			} else {
				$timeline_id				= 'ts-vcsc-timeline-item-' . mt_rand(999999, 9999999);
			}
			
			if (empty($content_text)) {
				$title_margin				= ' margin: 0 !important; padding: 0;';
			} else {
				$title_margin				= '';
			}
			
			$output = '';
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline-list-item ts-timeline-break ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_Break', $atts);
			} else {
				$css_class	= 'ts-timeline-list-item ts-timeline-break ' . $el_class;
			}
			
			if ((function_exists('vc_is_inline')) && (vc_is_inline())) {
				$vc_inline_style				= ' display: block;';
			} else {
				$vc_inline_style				= '';
			}
			
            $output .= '<li id="' . $timeline_id . '" class="' . $css_class . '" style="' . $vc_inline_style . '">';
                $output .= '<div class="ts-timeline-column" style="margin: 0;">';
                    $output .= '<div class="ts-timeline-text-wrap" style="background-color: ' . $color_background . ';">';
                        $output .= '<div class="ts-timeline-text-wrap-inner" style="width: 100%; left: 0; ' . $title_margin . '">';
							if (!empty($title_text)) {
								$output .= '<h3 class="ts-timeline-title" style="text-align: ' . $title_align . '; color: ' . $title_color . ';' . $title_margin . '">' . $title_text . '</h3>';
							}
							if (!empty($content_text)) {
								$output .= '<div class="ts-timeline-text" style="text-align: ' . $content_align . '; color: ' . $content_color . ';">' . rawurldecode(base64_decode(strip_tags($content_text))) . '</div>';
							}
                        $output .= '</div>';
                        $output .= '<div class="clearFixMe"></div>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</li>';
			
			echo $output;
			
			$myvariable = ob_get_clean();
			return $myvariable;
		}
        // Timeline Container
        function TS_VCSC_Timeline_Function_Container ($atts, $content = null){
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
            
            if ((get_option('ts_vcsc_extend_settings_loadHeader', 0) == 0)) {
                $FOOTER = true;
            } else {
                $FOOTER = false;
            }
			if ((function_exists('vc_is_inline')) && (vc_is_inline())) {
				wp_enqueue_style('ts-extend-nacho');
				wp_enqueue_style('ts-extend-owlcarousel2');
				wp_enqueue_style('ts-visual-composer-extend-front');
			} else {
				wp_enqueue_script('ts-extend-hammer');
				wp_enqueue_script('ts-extend-nacho');
				wp_enqueue_style('ts-extend-nacho');
                wp_enqueue_style('ts-extend-owlcarousel2');
                wp_enqueue_script('ts-extend-owlcarousel2');
				wp_enqueue_style('ts-font-ecommerce');
				wp_enqueue_style('dashicons');
				if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
					wp_enqueue_style('ts-extend-simptip');
					wp_enqueue_style('ts-extend-buttons');
					wp_enqueue_style('ts-extend-animations');
					wp_enqueue_style('ts-visual-composer-extend-front');
					wp_enqueue_script('ts-extend-isotope');
					wp_enqueue_script('ts-visual-composer-extend-front');
				}
				add_action('wp_footer', 											array($this, 'TS_VCSC_Timeline_Function_Isotope'), 9999);
			}
            
            extract( shortcode_atts( array(
				'timeline_order'				=> 'asc',
				'timeline_sort'					=> 'true',
				'timeline_lazy'					=> 'false',
				'timeline_trigger'				=> 'scroll',
				'timeline_count'				=> '10',
				'timeline_break'				=> '600',
				
				'timeline_title'				=> '',
				'timeline_title_color'			=> '#7c7979',
				'timeline_load'					=> 'Load More',
				'timeline_start'				=> '',
				'timeline_end'					=> '',
				'timeline_description'			=> '',
				'timeline_description_align'	=> 'center',
				'timeline_description_color'	=> '#7c7979',
				
				'margin_bottom'					=> '0',
				'margin_top' 					=> '0',
				
				'el_id' 						=> '',
				'el_class'                  	=> '',
				'css'							=> '',
            ), $atts ));
            
            $timeline_random                 	= mt_rand(999999, 9999999);
            
            if (!empty($el_id)) {
                $timeline_container_id			= $el_id;
            } else {
                $timeline_container_id			= 'ts-vcsc-timeline-container-' . $timeline_random;
            }
            
            $output 							= '';
			
			if ((function_exists('vc_is_inline')) && (vc_is_inline())) {
				$vcinline_class					= 'ts-timeline-edit';
				$vcinline_note					= '<div class="ts-composer-frontedit-message">' . __( 'This timeline is currently viewed in Visual Composer front-end editor mode. It is advised to edit such a complex element in back-end edit mode in order to avoid potential conflicts with other files loaded on the front-end of your website. The timeline is not functional in order to ensure display compatibility with the front-end editor.', "ts_visual_composer_extend" ) . '</div>';
				$vcinline_controls				= 'false';
				$timeline_lazy					= 'false';
			} else {
				$vcinline_class					= 'ts-timeline-view';
				$vcinline_note					= '';
				$vcinline_controls				= 'true';
				$timeline_lazy					= $timeline_lazy;
			}
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-timeline ts-timeline-' . $timeline_order . ' clearFixMe ' . $el_class . ' ' . $vcinline_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Timeline_Container', $atts);
			} else {
				$css_class	= 'ts-timeline ts-timeline-' . $timeline_order . ' clearFixMe ' . $el_class . ' ' . $vcinline_class;
			}
			
			$output .= '<div id="' . $timeline_container_id . '" class="' . $css_class . '" data-order="' . $timeline_order .'" data-lazy="' . $timeline_lazy . '" data-count="' . $timeline_count . '" data-trigger="' . $timeline_trigger . '" data-break="' . $timeline_break . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; width: 100%;">';
				
				$output .= $vcinline_note;
				
				$output .= '<div class="ts-timeline-controls">';
					$output .= '<div class="ts-timeline-controls-title" style="color: ' . $timeline_title_color . ';">' . $timeline_title . '</div>';
					if (($timeline_sort == "true") && ($vcinline_controls == "true")) {
						$output .= '<div class="ts-button ts-button-flat ts-timeline-controls-desc ' . ($timeline_order == "desc" ? "active" : "") . '"><span class="ts-timeline-controls-desc-image"></span></div>';
						$output .= '<div class="ts-button ts-button-flat ts-timeline-controls-asc ' . ($timeline_order == "asc" ? "active" : "") . '"><span class="ts-timeline-controls-asc-image"></span></div>';
					}
				$output .= '</div>';
				
				if (!empty($timeline_end)) {
					$output .= '<div class="ts-timeline-begin ts-timeline-begin-top">';
						$output .= '<span class="begin-text">' . $timeline_end . '</span>';
					$output .= '</div>';
				}
				if ((!empty($timeline_title)) || (!empty($timeline_description)) || (!empty($timeline_end))) {
					$output .= '<div class="ts-timeline-header-wrap">';
						if ((!empty($timeline_title)) || (!empty($timeline_description))) {
							$output .= '<div class="ts-timeline-header">';
								if (!empty($timeline_title)) {
									$output .= '<h4 class="ts-timeline-header-title" style="color: ' . $timeline_title_color . ';">' . $timeline_title . '</h4>';
								}
								if (!empty($timeline_description)) {
									$output .= '<p class="ts-timeline-header-description" style="color: ' . $timeline_description_color . '; text-align: ' . $timeline_description_align . ';">' . rawurldecode(base64_decode(strip_tags($timeline_description))) . '</p>';
								}
							$output .= '</div>';
						}
						if (!empty($timeline_start)) {
							$output .= '<div class="ts-timeline-end">';
								$output .= '<span class="end-text">' . $timeline_start . '</span>';
							$output .= '</div>';
						}
					$output .= '</div>';
				}
				
				$output .= '<div class="ts-timeline-content">';
					$output .= '<div id="ts-timeline-spine-' . $timeline_random . '" class="ts-timeline-spine"></div>';
					$output .= '<ul class="ts-timeline-list">';
						$output .= do_shortcode($content);
					$output .= '</ul>';
				$output .= '</div>';
				if ($timeline_lazy == "true") {
					$output .= '<div class="ts-load-more-wrap">';
						$output .= '<span class="ts-timeline-load-more">' . $timeline_load . '</span>';
					$output .= '</div>';
				}
				
				if ((!empty($timeline_title)) || (!empty($timeline_description)) || (!empty($timeline_end))) {
					$output .= '<div class="ts-timeline-footer-wrap">';
						if (!empty($timeline_start)) {
							$output .= '<div class="ts-timeline-end">';
								$output .= '<span class="end-text">' . $timeline_start . '</span>';
							$output .= '</div>';
						}
						if ((!empty($timeline_title)) || (!empty($timeline_description))) {
							$output .= '<div class="ts-timeline-footer">';
								if (!empty($timeline_title)) {
									$output .= '<h4 class="ts-timeline-footer-title" style="color: ' . $timeline_title_color . ';">' . $timeline_title . '</h4>';
								}
								if (!empty($timeline_description)) {
									$output .= '<p class="ts-timeline-footer-description" style="color: ' . $timeline_description_color . '; text-align: ' . $timeline_description_align . ';">' . rawurldecode(base64_decode(strip_tags($timeline_description))) . '</p>';
								}
							$output .= '</div>';
						}
					$output .= '</div>';
				}
				if (!empty($timeline_end)) {
					$output .= '<div class="ts-timeline-begin ts-timeline-begin-bottom">';
						$output .= '<span class="begin-text">' . $timeline_end . '</span>';
					$output .= '</div>';
				}

			$output .= '</div>';
            
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
		
		// Add Timeline Elements
        function TS_VCSC_Add_Timeline_Elements() {
			global $VISUAL_COMPOSER_EXTENSIONS;
			// Add Single Timeline Element
			if (function_exists('vc_map')) {
				vc_map( array(
					"name"                      	=> __( "TS Timeline Item", "ts_visual_composer_extend" ),
					"base"                      	=> "TS_VCSC_Timeline_Single",
					"icon" 	                    	=> "icon-wpb-ts_vcsc_timeline_single",
					"class"                     	=> "ts_vcsc_main_isotope_timeline",
					"category"                  	=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               	=> __("Place a timeline item element", "ts_visual_composer_extend"),
                    "content_element"				=> true,
                    "as_child"						=> array('only' => 'TS_VCSC_Timeline_Container'),
					//"admin_enqueue_js"        	=> array(ts_fb_get_resource_url('/Core/JS/jquery.js-composer.fb-album.js')),
					//"admin_enqueue_css"       	=> array(ts_fb_get_resource_url('/Core/CSS/jquery.js-composer.fb-album.css')),
					"params"                    	=> array(
						// Featured Media
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_1",
							"value"             	=> "Featured Media",
							"description"       	=> __( "", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Featured Event", "ts_visual_composer_extend" ),
							"param_name"		    => "full_width",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"admin_label"           => true,
							"description"		    => __( "Switch the toggle if you want this timeline event to be a featured event, displayed full width over both columns.", "ts_visual_composer_extend" ),
							"dependency"            => ""
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Featured Media", "ts_visual_composer_extend" ),
							"param_name"        	=> "featured_media",
							"width"             	=> 200,
							"value"             	=> array(
								__( 'None', "ts_visual_composer_extend" )      							=> "none",
								__( 'Single Image', "ts_visual_composer_extend" )      					=> "image",
								__( 'Image Slider', "ts_visual_composer_extend" )      					=> "slider",
								__( 'YouTube Video (Auto Cover)', "ts_visual_composer_extend" )			=> "youtube_default",
								__( 'YouTube Video (Custom Cover)', "ts_visual_composer_extend" )			=> "youtube_custom",
								__( 'YouTube Video (Direct iFrame)', "ts_visual_composer_extend" )		=> "youtube_embed",
								__( 'DailyMotion Video (Auto Cover)', "ts_visual_composer_extend" )		=> "dailymotion_default",
								__( 'DailyMotion Video (Custom Cover)', "ts_visual_composer_extend" )		=> "dailymotion_custom",
								__( 'DailyMotion Video (Direct iFrame)', "ts_visual_composer_extend" )	=> "dailymotion_embed",
								__( 'Vimeo Video (Auto Cover)', "ts_visual_composer_extend" )				=> "vimeo_default",
								__( 'Vimeo Video (Custom Cover)', "ts_visual_composer_extend" )			=> "vimeo_custom",
								__( 'Vimeo Video (Direct iFrame)', "ts_visual_composer_extend" )			=> "vimeo_embed",
							),
							"admin_label"			=> true,
							"description"       	=> __( "Select the Featured Media Type for the timeline item.", "ts_visual_composer_extend" )
						),
						// Image
						array(
							"type"					=> "attach_image",
							"heading"				=> __( "Select Image", "ts_visual_composer_extend" ),
							"holder"				=> "img",
							"param_name"			=> "featured_image",
							"class"					=> "ts_vcsc_holder_image",
							"value"					=> "",
							"admin_label"			=> false,
							"description"			=> __( "Select an image for the timeline item.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('image','youtube_custom','dailymotion_custom','vimeo_custom') )
						),
						array(
							"type"					=> "textfield",
							"heading"				=> __( "Enter ALT Attribute", "ts_visual_composer_extend" ),
							"param_name"			=> "attribute_alt_value",
							"value"					=> "",
							"description"			=> __( "Enter a custom value for the ALT attribute for the image, otherwise file name will be set.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('image','youtube_custom','dailymotion_custom','vimeo_custom') )
						),
						// Slider
						array(
							"type"                  => "attach_images",
							"heading"               => __( "Select Images", "ts_visual_composer_extend" ),
							"holder"				=> "imagelist",
							"param_name"            => "featured_slider",
							"value"                 => "",
							"admin_label"           => false,
							"description"           => __( "Select the images for the event slider; move images to arrange order in which to display.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => 'slider' )
						),
						array(
							"type"					=> "switch",
							"heading"           	=> __( "Open in Lightbox", "ts_visual_composer_extend" ),
							"param_name"        	=> "lightbox_featured",
							"value"             	=> "true",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"       	=> __( "Switch the toggle if you want to apply a lightbox to the image(s).", "ts_visual_composer_extend" ),
							"dependency"            => array('element' => "featured_media", 'value' => array('image','slider'))
						),
						array(
							"type"					=> "switch",
							"heading"				=> __( "RTL Page", "ts_visual_composer_extend" ),
							"param_name"			=> "page_rtl",
							"value"					=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"			=> __( "Switch the toggle if the slider is used on a page with RTL (Right-To-Left) alignment.", "ts_visual_composer_extend" ),
							"dependency"			=> array('element' => "featured_media", 'value' => array('slider'))
						),
						// YouTube Video
						array(
							"type"                  => "textfield",
							"heading"               => __( "YouTube Video URL", "ts_visual_composer_extend" ),
							"param_name"            => "featured_youtube_url",
							"value"                 => "",
							"admin_label"           => true,
							"description"           => __( "Enter the URL for the YouTube video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('youtube_default','youtube_custom','youtube_embed') )
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Show Related Videos", "ts_visual_composer_extend" ),
							"param_name"		    => "featured_youtube_related",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want to show related videos at the end of the video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('youtube_default','youtube_custom','youtube_embed') )
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    => "featured_youtube_play",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('youtube_default','youtube_custom','youtube_embed') )
						),
						// DailyMotion Video
						array(
							"type"                  => "textfield",
							"heading"               => __( "DailyMotion Video URL", "ts_visual_composer_extend" ),
							"param_name"            => "featured_dailymotion_url",
							"value"                 => "",
							"admin_label"           => true,
							"description"           => __( "Enter the URL for the DailyMotion video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('dailymotion_default','dailymotion_embed','dailymotion_embed') )
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    => "featured_dailymotion_play",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('dailymotion_default','dailymotion_embed','dailymotion_embed') )
						),
						// Vimeo Video
						array(
							"type"                  => "textfield",
							"heading"               => __( "Vimeo Video URL", "ts_visual_composer_extend" ),
							"param_name"            => "featured_vimeo_url",
							"value"                 => "",
							"admin_label"           => true,
							"description"           => __( "Enter the URL for the Vimeo video.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('vimeo_default','vimeo_custom','vimeo_embed') )
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    => "featured_vimeo_play",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('vimeo_default','vimeo_custom','vimeo_embed') )
						),
						// Media Dimensions
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_2",
							"value"             	=> "Media Dimensions",
							"description"       	=> __( "", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('image','youtube_default','youtube_custom','youtube_embed','dailymotion_default','dailymotion_custom','dailymotion_embed','vimeo_default','vimeo_custom','vimeo_embed') )
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Auto Height Setting", "ts_visual_composer_extend" ),
							"param_name"            => "featured_media_height",
							"width"                 => 150,
							"value"                 => array(
								__( '100% Height Setting', "ts_visual_composer_extend" )		=> "height: 100%;",
								__( 'Auto Height Setting', "ts_visual_composer_extend" )     	=> "height: auto;",
							),
							"description"           => __( "Select what height setting should be applied to the media element (change only if image height does not display correctly).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('image','youtube_default','youtube_custom','youtube_embed','dailymotion_default','dailymotion_custom','dailymotion_embed','vimeo_default','vimeo_custom','vimeo_embed') )
						),
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Media Width", "ts_visual_composer_extend" ),
							"param_name"            => "featured_media_width",
							"value"                 => "100",
							"min"                   => "1",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => '%',
							"description"           => __( "Define the media element width in percent (%).", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('image','youtube_default','youtube_custom','youtube_embed','dailymotion_default','dailymotion_custom','dailymotion_embed','vimeo_default','vimeo_custom','vimeo_embed') )
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Media Alignment", "ts_visual_composer_extend" ),
							"param_name"        	=> "featured_media_align",
							"width"             	=> 200,
							"value"             	=> array(
								__( 'Center', "ts_visual_composer_extend" )      	=> "center",
								__( 'Left', "ts_visual_composer_extend" )         => "left",
								__( 'Right', "ts_visual_composer_extend" )       	=> "right",
							),
							"description"       	=> __( "If not full width, select how the media element should be aligned.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "featured_media", 'value' => array('image','youtube_default','youtube_custom','youtube_embed','dailymotion_default','dailymotion_custom','dailymotion_embed','vimeo_default','vimeo_custom','vimeo_embed') )
						),					
						// Main Content
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_3",
							"value"             	=> "Main Content",
							"description"       	=> __( "", "ts_visual_composer_extend" ),
							"group"					=> "Main Content",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Date / Time", "ts_visual_composer_extend" ),
							"param_name"        	=> "date_text",
							"value"             	=> "",
							"admin_label"			=> true,
							"description"       	=> __( "Enter a date and/or time for the timeline item.", "ts_visual_composer_extend" ),
							"group"					=> "Main Content",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Title", "ts_visual_composer_extend" ),
							"param_name"        	=> "title_text",
							"value"             	=> "",
							"admin_label"			=> true,
							"description"       	=> __( "Enter the title for the timeline item.", "ts_visual_composer_extend" ),
							"group"					=> "Main Content",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Alignment", "ts_visual_composer_extend" ),
							"param_name"        	=> "title_align",
							"width"             	=> 200,
							"value"             	=> array(
								__( 'Center', "ts_visual_composer_extend" )      	=> "center",
								__( 'Left', "ts_visual_composer_extend" )         => "left",
								__( 'Right', "ts_visual_composer_extend" )       	=> "right",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"description"       	=> __( "Select how the title in the timeline item should be aligned.", "ts_visual_composer_extend" ),
							"group"					=> "Main Content",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "title_color",
							"value"             	=> "#7c7979",
							"description"       	=> __( "Define the font color for the title in the timeline item.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Main Content",
						),
						array(
							"type"					=> "textarea_html",
							"class"					=> "",
							"heading"				=> __( "Content", "ts_visual_composer_extend" ),
							"param_name"			=> "content",
							"value"					=> "",
							"admin_label"			=> false,
							"description"			=> __( "Enter the main content for the timeline item.", "ts_visual_composer_extend" ),
							"dependency"			=> "",
							"group"					=> "Main Content",
						),
						array(
							"type"              	=> "icons_panel",
							"heading"           	=> __( "Icon", "ts_visual_composer_extend" ),
							"param_name"        	=> "icon",
							"value"             	=> $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_List_Icons_Full,
							"description"       	=> __( "Select an icon for timeline element.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Main Content",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Icon Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "icon_color",
							"value"             	=> "#7c7979",
							"description"       	=> __( "Define the icon color to be used in the timeline item.", "ts_visual_composer_extend" ),
							"dependency"       	 	=> "",
							"group"					=> "Main Content",
						),
						// Link Settings
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_4",
							"value"             	=> "Link Settings",
							"description"       	=> __( "", "ts_visual_composer_extend" ),
							"group"					=> "Link Settings",
						),
						array(
							"type" 					=> "vc_link",
							"heading" 				=> __("Link + Title", "ts_visual_composer_extend"),
							"param_name" 			=> "link",
							"description" 			=> __("Provide a link to another site/page for the Event Link Button.", "ts_visual_composer_extend"),
							"group"					=> "Link Settings",
						),
						array(
							"type"					=> "switch",
							"heading"				=> __( "Use CSS3 Tooltip", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_css",
							"value"					=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"       	=> __( "Switch the toggle if you want to apply a CSS3 tooltip to the image.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Link Settings",
						),
						array(
							"type"					=> "dropdown",
							"class"					=> "",
							"heading"				=> __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_position",
							"value"					=> array(
								__( "Top", "ts_visual_composer_extend" )                            => "ts-simptip-position-top",
								__( "Bottom", "ts_visual_composer_extend" )                         => "ts-simptip-position-bottom",
							),
							"description"			=> __( "Select the tooltip position in relation to the link.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "tooltip_css", 'value' => 'true' ),
							"group"					=> "Link Settings",
						),
						array(
							"type"					=> "dropdown",
							"class"					=> "",
							"heading"				=> __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"			=> "tooltip_style",
							"value"             	=> array(
								__( "Black", "ts_visual_composer_extend" )                          => "",
								__( "Gray", "ts_visual_composer_extend" )                           => "ts-simptip-style-gray",
								__( "Green", "ts_visual_composer_extend" )                          => "ts-simptip-style-green",
								__( "Blue", "ts_visual_composer_extend" )                           => "ts-simptip-style-blue",
								__( "Red", "ts_visual_composer_extend" )                            => "ts-simptip-style-red",
								__( "Orange", "ts_visual_composer_extend" )                         => "ts-simptip-style-orange",
								__( "Yellow", "ts_visual_composer_extend" )                         => "ts-simptip-style-yellow",
								__( "Purple", "ts_visual_composer_extend" )                         => "ts-simptip-style-purple",
								__( "Pink", "ts_visual_composer_extend" )                           => "ts-simptip-style-pink",
								__( "White", "ts_visual_composer_extend" )                          => "ts-simptip-style-white"
							),
							"description"			=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "tooltip_css", 'value' => 'true' ),
							"group"					=> "Link Settings",
						),
						// Button Settings
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_5",
							"value"             	=> "Button Settings",
							"description"       	=> __( "", "ts_visual_composer_extend" ),
							"group"					=> "Link Settings",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Button Align", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_align",
							"width"             	=> 300,
							"value"             	=> array(
								__( 'Center', "ts_visual_composer_extend" )      	=> "center",
								__( 'Left', "ts_visual_composer_extend" )			=> "left",
								__( 'Right', "ts_visual_composer_extend" )  		=> "right",
							),
							"description"       	=> __( "Select how the link button should be aligned.", "ts_visual_composer_extend" ),
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Button Width", "ts_visual_composer_extend" ),
							"param_name"            => "button_width",
							"value"                 => "100",
							"min"                   => "0",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => '%',
							"description"       	=> __( "Define the button width in percent (responsive).", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => array('square', 'rounded', 'pill') ),
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Button Type", "ts_visual_composer_extend" ),
							"param_name"            => "button_type",
							"width"                 => 300,
							"value"                 => array (
								__( "Square", "ts_visual_composer_extend" )                         => "square",
								__( "Rounded", "ts_visual_composer_extend" )                        => "rounded",
								__( "Pill", "ts_visual_composer_extend" )                           => "pill",
								__( "Circle", "ts_visual_composer_extend" )                         => "circle",
							),
							"description"           => __( "Select the general button type for the 'Read More' Link.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Button Style", "ts_visual_composer_extend" ),
							"param_name"            => "button_square",
							"width"                 => 300,
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Button_Square,
							"description"           => __( "Select the actual button style for the 'Read More' Link.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => 'square' ),
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Button Style", "ts_visual_composer_extend" ),
							"param_name"            => "button_rounded",
							"width"                 => 300,
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Button_Rounded,
							"description"           => __( "Select the actual button style for the 'Read More' Link.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => 'rounded' ),
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Button Style", "ts_visual_composer_extend" ),
							"param_name"            => "button_pill",
							"width"                 => 300,
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Button_Pill,
							"description"           => __( "Select the actual button style for the 'Read More' Link.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => 'pill' ),
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Button Style", "ts_visual_composer_extend" ),
							"param_name"            => "button_circle",
							"width"                 => 300,
							"value"                 => $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Button_Circle,
							"description"           => __( "Select the actual button style for the 'Read More' Link.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => 'circle' ),
							"group"					=> "Link Settings",
						),
						array(
							"type"              	=> "dropdown",
							"heading"           	=> __( "Button Size", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_size",
							"width"             	=> 300,
							"value"             	=> array(
								__( 'Normal', "ts_visual_composer_extend" )		=> "ts-button-normal",
								__( 'Small', "ts_visual_composer_extend" )      	=> "ts-button-small",
								__( 'Tiny', "ts_visual_composer_extend" )  		=> "ts-button-tiny",
								__( 'Large', "ts_visual_composer_extend" )  		=> "ts-button-large",
								__( 'Jumbo', "ts_visual_composer_extend" )  		=> "ts-button-jumbo",
							),
							"description"       	=> __( "Select the size for the icon button.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => array('square', 'rounded', 'pill') ),
							"group"					=> "Link Settings",
						),
						array(
							"type"					=> "switch",
							"heading"           	=> __( "Add Button Wrapper", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_wrapper",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"       	=> __( "Switch the toggle to add a wrapper frame around the 'Read More' button (most suited for 'pill' and 'circle' buttons).", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Link Settings",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Button Text", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_text",
							"value"             	=> "Read More",
							"description"       	=> __( "Enter a text for the 'Read More' button.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Link Settings",
						),
						array(
							"type"					=> "switch",
							"heading"           	=> __( "Change Font Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_change",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"       	=> __( "Switch the toggle to apply a custom font color to the button text.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Link Settings",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Text Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "button_color",
							"value"             	=> "#666666",
							"description"       	=> __( "Define the color of the text for the button.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_change", 'value' => 'true' ),
							"group"					=> "Link Settings",
						),
						array(
							"type"                  => "nouislider",
							"heading"               => __( "Font Size", "ts_visual_composer_extend" ),
							"param_name"            => "button_font",
							"value"                 => "18",
							"min"                   => "4",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => 'px',
							"description"       	=> __( "Define the font size for the icon / text in the button.", "ts_visual_composer_extend" ),
							"dependency"			=> array( 'element' => "button_type", 'value' => 'circle' ),
							"group"					=> "Link Settings",
						),
						// Lightbox Settings
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_6",
							"value"             	=> "Lightbox Settings",
							"description"       	=> __( "", "ts_visual_composer_extend" ),
							"group"					=> "Lightbox Settings",
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Create AutoGroup", "ts_visual_composer_extend" ),
							"param_name"		    => "lightbox_group",
							"value"				    => "true",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want the plugin to group this image with all other non-gallery images on the page.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group"					=> "Lightbox Settings",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Group Name", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_group_name",
							"value"                 => "",
							"description"           => __( "Enter a custom group name to manually build group with other non-gallery items.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_group", 'value' => 'false' ),
							"group"					=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Transition Effect", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_effect",
							"width"                 => 150,
							"value"                 => array(
								__( 'Random', "ts_visual_composer_extend" )       => "random",
								__( 'Swipe', "ts_visual_composer_extend" )        => "swipe",
								__( 'Fade', "ts_visual_composer_extend" )         => "fade",
								__( 'Scale', "ts_visual_composer_extend" )        => "scale",
								__( 'Slide Up', "ts_visual_composer_extend" )     => "slideUp",
								__( 'Slide Down', "ts_visual_composer_extend" )   => "slideDown",
								__( 'Flip', "ts_visual_composer_extend" )         => "flip",
								__( 'Skew', "ts_visual_composer_extend" )         => "skew",
								__( 'Bounce Up', "ts_visual_composer_extend" )    => "bounceUp",
								__( 'Bounce Down', "ts_visual_composer_extend" )  => "bounceDown",
								__( 'Break In', "ts_visual_composer_extend" )     => "breakIn",
								__( 'Rotate In', "ts_visual_composer_extend" )    => "rotateIn",
								__( 'Rotate Out', "ts_visual_composer_extend" )   => "rotateOut",
								__( 'Hang Left', "ts_visual_composer_extend" )    => "hangLeft",
								__( 'Hang Right', "ts_visual_composer_extend" )   => "hangRight",
								__( 'Cycle Up', "ts_visual_composer_extend" )     => "cicleUp",
								__( 'Cycle Down', "ts_visual_composer_extend" )   => "cicleDown",
								__( 'Zoom In', "ts_visual_composer_extend" )      => "zoomIn",
								__( 'Throw In', "ts_visual_composer_extend" )     => "throwIn",
								__( 'Fall', "ts_visual_composer_extend" )         => "fall",
								__( 'Jump', "ts_visual_composer_extend" )         => "jump",
							),
							"description"           => __( "Select the transition effect to be used for the image in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group"					=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Backlight Effect", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight",
							"width"                 => 150,
							"value"                 => array(
								__( 'Auto Color', "ts_visual_composer_extend" )       => "auto",
								__( 'Custom Color', "ts_visual_composer_extend" )     => "custom",
								__( 'No Backlight', "ts_visual_composer_extend" )     => "hideit",
							),
							"description"           => __( "Select the backlight effect for the image.", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group"					=> "Lightbox Settings",
						),
						array(
							"type"                  => "colorpicker",
							"heading"               => __( "Custom Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight_color",
							"value"                 => "#ffffff",
							"description"           => __( "Define the backlight color for the lightbox image.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_backlight", 'value' => 'custom' ),
							"group"					=> "Lightbox Settings",
						),
						// Other Settings
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_7",
							"value"             	=> "Other Settings",
							"description"       	=> __( "", "ts_visual_composer_extend" ),
							"group"					=> "Other Settings",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        	=> "el_id",
							"value"             	=> "",
							"description"       	=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group"					=> "Other Settings",
						),
						array(
							"type"              	=> "textfield",
							"heading"           	=> __( "Extra Class Name", "ts_visual_composer_extend" ),
							"param_name"        	=> "el_class",
							"value"             	=> "",
							"description"       	=> __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
							"group"					=> "Other Settings",
						),
						// Load Custom CSS/JS File
						array(
							"type"              	=> "load_file",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"value"             	=> "Timeline Files",
							"param_name"        	=> "el_file",
							"file_type"         	=> "js",
							"file_path"         	=> "js/ts-visual-composer-extend-element.min.js",
							"description"       	=> __( "", "ts_visual_composer_extend" )
						),
					))
				);
			}
			// Add Break Timeline Element
			if (function_exists('vc_map')) {
				vc_map( array(
					"name"                      => __( "TS Timeline Break", "ts_visual_composer_extend" ),
					"base"                      => "TS_VCSC_Timeline_Break",
					"icon" 	                    => "icon-wpb-ts_vcsc_timeline_break",
					"class"                     => "",
					"category"                  => __( 'VC Extensions', "ts_visual_composer_extend" ),
					"description"               => __("Place a timeline break element", "ts_visual_composer_extend"),
                    "content_element"			=> true,
                    "as_child"					=> array('only' => 'TS_VCSC_Timeline_Container'),
					//"admin_enqueue_js"        => array(ts_fb_get_resource_url('/Core/JS/jquery.js-composer.fb-album.js')),
					//"admin_enqueue_css"       => array(ts_fb_get_resource_url('/Core/CSS/jquery.js-composer.fb-album.css')),
					"params"                    => array(
						// Timeline Settings
						array(
							"type"              => "seperator",
							"heading"           => __( "", "ts_visual_composer_extend" ),
							"param_name"        => "seperator_1",
							"value"             => "Timeline Break Settings",
							"description"       => __( "", "ts_visual_composer_extend" )
						),
						array(
							"type"              => "colorpicker",
							"heading"           => __( "Background Color", "ts_visual_composer_extend" ),
							"param_name"        => "color_background",
							"value"             => "#ededed",
							"description"       => __( "Define the background color for the timeline break item.", "ts_visual_composer_extend" ),
							"dependency"        => ""
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Title", "ts_visual_composer_extend" ),
							"param_name"        => "title_text",
							"value"             => "",
							"admin_label"		=> true,
							"description"       => __( "Enter the title for the timeline break item.", "ts_visual_composer_extend" )
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Alignment", "ts_visual_composer_extend" ),
							"param_name"        => "title_align",
							"width"             => 200,
							"value"             => array(
								__( 'Center', "ts_visual_composer_extend" )      	=> "center",
								__( 'Left', "ts_visual_composer_extend" )         => "left",
								__( 'Right', "ts_visual_composer_extend" )       	=> "right",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"description"       => __( "Select how the title in the timeline break item should be aligned.", "ts_visual_composer_extend" )
						),
						array(
							"type"              => "colorpicker",
							"heading"           => __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        => "title_color",
							"value"             => "#7c7979",
							"description"       => __( "Define the font color for the title in the timeline break item.", "ts_visual_composer_extend" ),
							"dependency"        => ""
						),
						array(
							"type"              => "textarea_raw_html",
							"heading"           => __( "Content", "ts_visual_composer_extend" ),
							"param_name"        => "content_text",
							"value"             => base64_encode(""),
							"description"       => __( "Enter the timeline break item content; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        => ""
						),
						array(
							"type"              => "dropdown",
							"heading"           => __( "Alignment", "ts_visual_composer_extend" ),
							"param_name"        => "content_align",
							"width"             => 200,
							"value"             => array(
								__( 'Center', "ts_visual_composer_extend" )      	=> "center",
								__( 'Left', "ts_visual_composer_extend" )         => "left",
								__( 'Right', "ts_visual_composer_extend" )       	=> "right",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"description"       => __( "Select how the content in the timeline break item should be aligned.", "ts_visual_composer_extend" )
						),
						array(
							"type"              => "colorpicker",
							"heading"           => __( "Content Color", "ts_visual_composer_extend" ),
							"param_name"        => "content_color",
							"value"             => "#7c7979",
							"description"       => __( "Define the font color for the content in the timeline break item.", "ts_visual_composer_extend" ),
							"dependency"        => ""
						),
						// Other Settings
						array(
							"type"              => "seperator",
							"heading"           => __( "", "ts_visual_composer_extend" ),
							"param_name"        => "seperator_8",
							"value"             => "Other Box Settings",
							"description"       => __( "", "ts_visual_composer_extend" ),
							"group" 			=> "Other Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"        => "el_id",
							"value"             => "",
							"description"       => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 			=> "Other Settings",
						),
						array(
							"type"              => "textfield",
							"heading"           => __( "Extra Class Name", "ts_visual_composer_extend" ),
							"param_name"        => "el_class",
							"value"             => "",
							"description"       => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
							"group" 			=> "Other Settings",
						),
						// Load Custom CSS/JS File
						array(
							"type"              => "load_file",
							"heading"           => __( "", "ts_visual_composer_extend" ),
							"value"             => "Timeline Files",
							"param_name"        => "el_file",
							"file_type"         => "js",
							"file_path"         => "js/ts-visual-composer-extend-element.min.js",
							"description"       => __( "", "ts_visual_composer_extend" )
						),
					))
				);
			}
			// Add Timeline Container Element
            if (function_exists('vc_map')) {
                vc_map(array(
                   "name"                               => __("TS Isotope Timeline", "ts_visual_composer_extend"),
                   "base"                               => "TS_VCSC_Timeline_Container",
                   "class"                              => "",
                   "icon"                               => "icon-wpb-ts_vcsc_timeline_container",
                   "category"                           => __("VC Extensions", "ts_visual_composer_extend"),
                   "as_parent"                          => array('only' => 'TS_VCSC_Timeline_Single,TS_VCSC_Timeline_Break'),
                   "description"                        => __("Build a custom Timeline with Isotope", "ts_visual_composer_extend"),
                   "content_element"                    => true,
                   "show_settings_on_create"            => false,
                   "params"                             => array(
						// General Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_0",
                            "value"                     => "General Setup",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Initial Order", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_order",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Oldest (Top) to Newest (Bottom)', "ts_visual_composer_extend" )		=> "asc",
								__( 'Newest (Top) to Oldest (Bottom)', "ts_visual_composer_extend" )		=> "desc",
							),
							"admin_label"           	=> true,
							"description"       		=> __( "Select in which order the timeline events are arranged in Visual Composer.", "ts_visual_composer_extend" )
						),
						array(
							"type"              		=> "switch",
							"heading"			    	=> __( "Sort Buttons", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_sort",
							"value"             		=> "true",
							"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"						=> __( 'No', "ts_visual_composer_extend" ),
							"style"						=> "select",
							"design"					=> "toggle-light",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle if you want to provide sort controls (up/down) for the timeline.", "ts_visual_composer_extend" ),
							"dependency"            	=> ""
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Masonry Breakpoint", "ts_visual_composer_extend" ),
                            "param_name"                => "timeline_break",
                            "value"                     => "600",
                            "min"                       => "100",
                            "max"                       => "2048",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Define a breakpoint in pixels at which the timeline should switch to a one column layout.", "ts_visual_composer_extend" )
                        ),
						array(
							"type"              		=> "switch",
							"heading"			    	=> __( "Lazy-Load Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "timeline_lazy",
							"value"             		=> "false",
							"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"						=> __( 'No', "ts_visual_composer_extend" ),
							"style"						=> "select",
							"design"					=> "toggle-light",
							"admin_label"           	=> true,
							"description"		    	=> __( "Switch the toggle if you want to show a limited number of events at a time, showing more the further you scroll.", "ts_visual_composer_extend" ),
							"dependency"            	=> ""
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Number of Events", "ts_visual_composer_extend" ),
                            "param_name"                => "timeline_count",
                            "value"                     => "10",
                            "min"                       => "1",
                            "max"                       => "200",
                            "step"                      => "1",
                            "unit"                      => '',
                            "description"               => __( "Define how many events should be shown per Lazy-Load Event.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_lazy", 'value' => 'true' )
                        ),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Lazy-Load Trigger", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_trigger",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Scroll', "ts_visual_composer_extend" )      		=> "scroll",
								__( 'Click', "ts_visual_composer_extend" )         	=> "click",
							),
							"description"       		=> __( "Select how the Lazy-Load Effect should be triggered for the timeline.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_lazy", 'value' => 'true' )
						),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Text for 'Load More' Button", "ts_visual_composer_extend" ),
                            "param_name"                => "timeline_load",
                            "value"                     => "Load More",
                            "description"               => __( "Enter a text to be shown inside the 'Load More' trigger button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "timeline_lazy", 'value' => 'true' )
                        ),
                        // Additional Info Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "Additional Information",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Additional Info",
                        ),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Timeline Title", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title",
							"value"             		=> "",
							"description"       		=> __( "Enter a title for the Isotope Timeline.", "ts_visual_composer_extend" ),
							"group" 			        => "Additional Info",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Title Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_title_color",
							"value"             		=> "#7c7979",
							"description"       		=> __( "Define the font color for the title in the timeline break item.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 			        => "Additional Info",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "Start Term", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_start",
							"value"             		=> "",
							"description"       		=> __( "Enter an optional start term for the Isotope Timeline.", "ts_visual_composer_extend" ),
							"group" 			        => "Additional Info",
						),
						array(
							"type"              		=> "textfield",
							"heading"           		=> __( "End Term", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_end",
							"value"             		=> "",
							"description"       		=> __( "Enter an optional end term for the Isotope Timeline.", "ts_visual_composer_extend" ),
							"group" 			        => "Additional Info",
						),
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "Description", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description",
							"value"             		=> base64_encode(""),
							"description"       		=> __( "Enter a description for the the overall timeline, shown at the beginning; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 			        => "Additional Info",
						),
						array(
							"type"              		=> "dropdown",
							"heading"           		=> __( "Alignment", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_align",
							"width"             		=> 200,
							"value"             		=> array(
								__( 'Center', "ts_visual_composer_extend" )      	=> "center",
								__( 'Left', "ts_visual_composer_extend" )         => "left",
								__( 'Right', "ts_visual_composer_extend" )       	=> "right",
								__( 'Justify', "ts_visual_composer_extend" )		=> "justify",
							),
							"description"       		=> __( "Select how the description text should be aligned.", "ts_visual_composer_extend" ),
							"group" 			        => "Additional Info",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Content Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "timeline_description_color",
							"value"             		=> "#7c7979",
							"description"       		=> __( "Define the font color for the description text.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 			        => "Additional Info",
						),
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
                            "value"                     => "Other Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_top",
                            "value"                     => "0",
                            "min"                       => "0",
                            "max"                       => "200",
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
                            "min"                       => "0",
                            "max"                       => "200",
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
                    ),
                    "js_view"                           => 'VcColumnView'
                ));
            }
		}
	}
}
// Register Container and Child Shortcode with Visual Composer
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_TS_VCSC_Timeline_Container extends WPBakeryShortCodesContainer {};
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_TS_VCSC_Timeline_Single extends WPBakeryShortCode {
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
				$output .= '<'.$param['holder'].' style="" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="'.$value.'">';
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
                $output .= '<span style="max-width: 100%; display: block;" class="vc_admin_label admin_label_'.$param['param_name'].(empty($value) ? ' hidden-label' : '').'"><label>'. $param['heading'] .'</label>: '.$value.'</span>';
            }

			return $output;
		}
	};
	class WPBakeryShortCode_TS_VCSC_Timeline_Break extends WPBakeryShortCode {};
}
// Initialize "TS Teaser Blocks" Class
if (class_exists('TS_Timelines')) {
	$TS_Timelines = new TS_Timelines;
}