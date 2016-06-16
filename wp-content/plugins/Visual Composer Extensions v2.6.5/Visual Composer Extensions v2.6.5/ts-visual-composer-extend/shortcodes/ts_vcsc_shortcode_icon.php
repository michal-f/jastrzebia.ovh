<?php
	add_shortcode('TS-VCSC-Font-Icons', 'TS_VCSC_Font_Icons_Function');
	function TS_VCSC_Font_Icons_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-extend-simptip');
			wp_enqueue_style('ts-extend-animations');
			wp_enqueue_style('ts-visual-composer-extend-front');
			wp_enqueue_script('ts-visual-composer-extend-front');
		}
		
		extract(shortcode_atts(array(
			'icon_replace'				=> 'false',
			'icon' 						=> '',
			'icon_image'				=> '',
			'icon_color'				=> '#000000',
			'icon_background'			=> '',
			'icon_size_slide'           => 16,
			'icon_frame_type' 			=> '',
			'icon_frame_thick'			=> 1,
			'icon_frame_radius'			=> '',
			'icon_frame_color'			=> '#000000',
			'padding' 					=> 'false',
			'icon_padding' 				=> 0,
			'icon_align' 				=> '',
			'link' 						=> '',
			'link_target'				=> '_parent',
			'tooltip_css'				=> 'false',
			'tooltip_content'			=> '',
			'tooltip_position'			=> 'ts-simptip-position-top',
			'tooltip_style'				=> '',
			'animation_icon'			=> '',
			'animation_view' 			=> '',
			'animation_delay' 			=> 0,
			'el_id' 					=> '',
			'el_class' 					=> '',
			'css'						=> '',
		), $atts));
		
		$icon_color = !empty($icon_color) ? ('color:' . $icon_color .';') : '';
		$output = $icon_frame_class = $icon_frame_style = $animation_css = '';
		
		if (!empty($el_id)) {
			$icon_font_id				= $el_id;
		} else {
			$icon_font_id				= 'ts-vcsc-font-icon-' . mt_rand(999999, 9999999);
		}
		
		if (!empty($icon_image)) {
			$icon_image_path 			= wp_get_attachment_image_src($icon_image, 'large');
		}
		
		if ($padding == "true") {
			$icon_frame_padding			= 'padding: ' . $icon_padding . 'px; ';
		} else {
			$icon_frame_padding			= '';
		}
		
		$icon_style                     = '' . $icon_frame_padding . 'background-color:' . $icon_background . '; width:' . $icon_size_slide . 'px; height:' . $icon_size_slide . 'px; font-size:' . $icon_size_slide . 'px; line-height:' . $icon_size_slide . 'px;';
		$icon_image_style				= '' . $icon_frame_padding . 'background-color:' . $icon_background . '; width: ' . $icon_size_slide . 'px; height: ' . $icon_size_slide . 'px; ';
		
		if ($icon_frame_type != '') {
			$icon_frame_class 	        = 'frame-enabled';
			$icon_frame_style 	        = 'border: ' . $icon_frame_thick . 'px ' . $icon_frame_type . ' ' . $icon_frame_color . ';';
		}
		
		if ($animation_view != '') {
			$animation_css				= 'ts-vcsc-font-icon-viewport ' . TS_VCSC_GetCSSAnimation($animation_view);
		}
		
		// Tooltip
		if ($tooltip_css == "true") {
			if (strlen($tooltip_content) != 0) {
				$icon_tooltipclasses	= " ts-simptip-multiline " . $tooltip_style . " " . $tooltip_position;
				$icon_tooltipcontent	= ' data-tstooltip="' . $tooltip_content . '"';
			} else {
				$icon_tooltipclasses	= "";
				$icon_tooltipcontent	= "";
			}
		} else {
			$icon_tooltipclasses		= "";
			if (strlen($tooltip_content) != 0) {
				$icon_tooltipcontent	= ' title="' . $tooltip_content . '"';
			} else {
				$icon_tooltipcontent	= "";
			}
		}	
		
		$output = '';
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-' . $icon_align . ' ' . $animation_css . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Font-Icons', $atts);
		} else {
			$css_class	= 'ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-' . $icon_align . ' ' . $animation_css . ' ' . $el_class;
		}
		
		$output .= '<div id="' . $icon_font_id . '" style="" class="' . $css_class . '" data-viewport="' . $animation_css . ' wpb_start_animation" data-delay="' . $animation_delay . '">';
		
			if ($link) {
				$output .= '<a class="ts-font-icons-link" href="' . $link . '" target="' . $link_target . '">';
			}
			
				$output .= '<span class="' . $icon_tooltipclasses . '" ' . $icon_tooltipcontent . '>';
				
					if ($icon_replace == "false") {
						$output .= '<i class="ts-font-icon ' . $icon . ' ' . $icon_frame_class . ' ' . $animation_icon . ' ' . $icon_frame_radius . '" style="' . $icon_style . $icon_frame_style . $icon_color . '"></i>';
					} else {
						$output .= '<img class="ts-font-icon ' . $icon_frame_class . ' ' . $animation_icon . ' ' . $icon_frame_radius . ' ' . $icon_frame_radius . '" src="' . $icon_image_path[0] . '" style="' . $icon_frame_style . ' ' . $icon_image_style . ' display: inline-block !important; margin-bottom: ' . $icon_margin . 'px;">';
					}
					
				$output .= '</span>';
				
			if ($link) {
				$output .= '</a>';
			}
			
		$output .= '</div>';
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>
