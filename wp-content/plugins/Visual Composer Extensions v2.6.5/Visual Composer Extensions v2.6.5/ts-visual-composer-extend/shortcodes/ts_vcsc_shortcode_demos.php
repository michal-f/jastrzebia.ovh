<?php
	// Shortcode to Build Icon Preview for Specific Font
	add_shortcode('TS_VCSC_Icon_Preview', 'TS_VCSC_Icon_Font_Preview');
	function TS_VCSC_Icon_Font_Preview ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-extend-simptip');
			wp_enqueue_style('ts-extend-animations');
			wp_enqueue_style('ts-visual-composer-extend-front');
		}
		
		extract(shortcode_atts(array(
			'font' 						=> 'Awesome',
			'size'           			=> 16,
			
			'color'						=> '#000000',
			'background'				=> '',
	
			'animation'					=> '',
		), $atts));
		
		// Load CSS for Selected Font
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
			if (($iconfont != "Custom") && ($iconfont == $font)) {
				wp_enqueue_style('ts-font-' . strtolower($iconfont));
			}
		}
		
		// Rebuild Font Data Array in Case Font is Disabled
		$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_tinymceFontsAll = "true";
		$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconFontArrays();
		$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_tinymceFontsAll = "false";
		
		// Define Size for Element
		if ($size != 16) {
			$icon_size					= "height:" . $size . "px; width:" . $size . "px; line-height:" . $size . "px; font-size:" . $size . "px; ";
		} else {
			$icon_size					= "";
		}
		
		// Define Color for Element
		if ($color != "#000000") {
			$icon_color					= "color: " . $color . "; ";
		} else {
			$icon_color					= "";
		}
		
		// Define Background for Element
		if (strlen($background) > 0) {
			$icon_background 			= " background-color: " . $background . "; ";
		} else {
			$icon_background			= "";
		}
	
		// Define Class for Animation
		if (strlen($animation) > 0) {
			$icon_animation				= $animation;
		} else {
			$icon_animation				= "";
		}
		
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
			if (($iconfont != "Custom") && ($iconfont == $font)){
				$output = '';
				$output .= '<div id="ts-vcsc-extend-preview-' . $iconfont . '" class="ts-vcsc-extend-preview" data-font="' . $Icon_Font . '">';
					$output .= '<div id="ts-vcsc-extend-preview-list-' . $Icon_Font . '" class="ts-vcsc-extend-preview-list" data-font="' . $Icon_Font . '">';
						$icon_counter = 0;
						foreach ($VISUAL_COMPOSER_EXTENSIONS->{'TS_VCSC_List_Icons_' . $iconfont . ''} as $key => $option ) {
							$output .= '<div class="ts-vcsc-icon-preview ts-simptip-multiline ts-simptip-position-top" data-tstooltip="ts-' . $key . '" data-name="ts-' . $key . '" data-code="' . $option . '" data-font="' . strtolower($font) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i class="ts-font-icon ts-font-icon ts-' . $key . ' ' . $icon_animation . '" style="' . $icon_size . $icon_color . $icon_background . ' "></i></span></div>';
							$icon_counter = $icon_counter + 1;
						}
					$output .= '</div>';
				$output .= '</div>';
			}
		}

		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
	// Shortcode to get Icon Count for Specific Font
	add_shortcode('TS_VCSC_Icon_Font_IconCount', 'TS_VCSC_Icon_Font_IconCount');
	function TS_VCSC_Icon_Font_IconCount ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();		
		extract(shortcode_atts(array(
			'font' 						=> 'Awesome',
		), $atts));		
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
			if (($iconfont['setting'] != "Custom") && ($iconfont['setting'] == $font)) {
				echo $iconfont['count'];
			}
		}		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
	// Shortcode to get Total Number of all Icons
	add_shortcode('TS_VCSC_Icon_Font_IconsTotal', 'TS_VCSC_Icon_Font_IconsTotal');
	function TS_VCSC_Icon_Font_IconsTotal ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();		
		extract(shortcode_atts(array(), $atts));		
		if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
			echo $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Total_Icon_Count - $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_tinymceCustomCount;
		} else {
			echo $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Total_Icon_Count;
		}		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
	// Shortcode to get Share of Icons in Specific Font in Relation to all Icons
	add_shortcode('TS_VCSC_Icon_Font_IconShare', 'TS_VCSC_Icon_Font_IconShare');
	function TS_VCSC_Icon_Font_IconShare ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();		
		extract(shortcode_atts(array(
			'font' 						=> 'Awesome',
		), $atts));		
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
			if (($iconfont['setting'] != "Custom") && ($iconfont['setting'] == $font)) {
				if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
					echo $iconfont['count'] / ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Total_Icon_Count - $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_tinymceCustomCount);
				} else {
					echo $iconfont['count'] / $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Total_Icon_Count;
				}
			}
		}		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
	// Shortcode to get Total Number of all Fonts
	add_shortcode('TS_VCSC_Icon_Font_FontCount', 'TS_VCSC_Icon_Font_FontCount');
	function TS_VCSC_Icon_Font_FontCount ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();		
		extract(shortcode_atts(array(), $atts));		
		if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
			echo count($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts) - 1;
		} else {
			echo count($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts);
		}		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
	// Shortcode to Build Table with CSS3 Animations Preview
	add_shortcode('TS_VCSC_Icon_Animations', 'TS_VCSC_Icon_Font_Animations');
	function TS_VCSC_Icon_Font_Animations ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-extend-simptip');
			wp_enqueue_style('ts-extend-animations');
			wp_enqueue_style('ts-visual-composer-extend-front');
			wp_enqueue_script('ts-visual-composer-extend-front');
		}
		
		extract(shortcode_atts(array(
			'font' 						=> 'Awesome',
			'size'           			=> 16,
			
			'color'						=> '#000000',
			'background'				=> '',
	
			'animationtype'				=> 'Default',
		), $atts));
		
		// Load CSS for Selected Font
		foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
			if (($iconfont != "Custom") && ($iconfont == $font)) {
				wp_enqueue_style('ts-font-' . strtolower($iconfont));
			}
		}
		
		// Rebuild Font Data Array in Case Font is Disabled
		$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_tinymceFontsAll = "true";
		$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_IconFontArrays();
		$VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_tinymceFontsAll = "false";
		
		// Define Size for Element
		if ($size != 16) {
			$icon_size					= "height:" . $size . "px; width:" . $size . "px; line-height:" . $size . "px; font-size:" . $size . "px; ";
		} else {
			$icon_size					= "";
		}
		
		// Define Color for Element
		if ($color != "#000000") {
			$icon_color					= "color: " . $color . "; ";
		} else {
			$icon_color					= "";
		}
		
		// Define Background for Element
		if (strlen($background) > 0) {
			$icon_background 			= " background-color: " . $background . "; ";
		} else {
			$icon_background			= "";
		}
	
		// Define Animation Array
		$animationloop 	= array();
		$animationname 	= array();
		$animationgroup = array();
		if (strlen($animationtype) > 0) {
			if ($animationtype == "Hover") {
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
					if (($Animation_Class) && ($animations['group'] != "Standard Visual Composer")) {
						$animationloop[] 	= "ts-hover-css-" . $animations['class'];
						$animationname[] 	= $Animation_Class;
						$animationgroup[]	= $animations['group'];
					}
				}
			} else if ($animationtype == "Default") {
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
					if (($Animation_Class) && ($animations['group'] != "Standard Visual Composer")) {
						$animationloop[] 	= "ts-infinite-css-" . $animations['class'];
						$animationname[] 	= $Animation_Class;
						$animationgroup[]	= $animations['group'];
					}
				}
			} else if ($animationtype == "Viewport") {
				foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
					if (($Animation_Class) && ($animations['group'] != "Standard Visual Composer")) {
						$animationloop[] 	= "ts-viewport-css-" . $animations['class'];
						$animationname[] 	= $Animation_Class;
						$animationgroup[]	= $animations['group'];
					}
				}
			}
		}
		$animationcount = count($animationloop) - 1;
		
		$output = '';
		$output .= '<div id="ts-vcsc-extend-preview-' . $font . '" class="ts-vcsc-extend-preview" data-font="' . $font . '">';
			$output .= '<div id="ts-vcsc-extend-preview-list-' . $font . '" class="ts-vcsc-extend-preview-list" data-font="' . $font . '">';
				$outputcount = 1;
				$output .= '<table class="ts-vcsc-icon-animations" style="width: 100%;">';
				$output .= '<thead>';
				$output .= '<tr><th>#</th><th>Animation Name</th><th>Default (Class Name)</th><th>Hover (Class Name)</th><th>Viewport (Class Name)</th><th>Animation Effect</th></tr>';
				$output .= '</thead>';
				$output .= '<tbody>';
					$effectgroups	= array();
					foreach ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
						foreach ($VISUAL_COMPOSER_EXTENSIONS->{'TS_VCSC_List_Icons_' . $iconfont . ''} as $key => $option ) {
							if (($iconfont != "Custom") && ($iconfont == $font)){
								if ($outputcount <= $animationcount) {									
									if (!in_array($animationgroup[$outputcount], $effectgroups)) {
										array_push($effectgroups, $animationgroup[$outputcount]);
										$output .= '<tr style="background: #ededed;"><td colspan="6" style="font-size: 12px; font-weight: bold; text-align: center;">' . $animationgroup[$outputcount] . '</td></tr>';
									}									
									$output .= '<tr>';
									$output .= '<td>' . $outputcount . '</td>';
									$output .= '<td style="font-size: 14px; font-weight: bold;">' . $animationname[$outputcount] . '</td>';
									if ($animationtype == "Hover") {
										$output .= '<td>' . str_replace("hover", "infinite", $animationloop[$outputcount]) . '</td>';
										$output .= '<td>' . $animationloop[$outputcount] . '</td>';
										$output .= '<td>' . str_replace("hover", "viewport", $animationloop[$outputcount]) . '</td>';
									} else if ($animationtype == "Default") {
										$output .= '<td>' . $animationloop[$outputcount] . '</td>';
										$output .= '<td>' . str_replace("infinite", "hover", $animationloop[$outputcount]) . '</td>';
										$output .= '<td>' . str_replace("infinite", "viewport", $animationloop[$outputcount]) . '</td>';
									} else if ($animationtype == "Viewport") {
										$output .= '<td>' . str_replace("viewport", "infinite", $animationloop[$outputcount]) . '</td>';
										$output .= '<td>' . str_replace("viewport", "hover", $animationloop[$outputcount]) . '</td>';
										$output .= '<td>' . $animationloop[$outputcount] . '</td>';
									}
									if ($animationtype == "Viewport") {
										$output .= '<td><div class="ts-vcsc-icon-preview" data-name="ts-' . $key . '" data-code="' . $option . '" data-font="' . strtolower($font) . '" data-count="' . $outputcount . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i data-viewport="' . $animationloop[$outputcount] . '" class="ts-font-icon ts-' . $key . '" style="' . $icon_size . $icon_color . $icon_background . ' " title="' . $key . '"></i></span></div></td>';
									} else if ($animationtype == "Default") {
										$output .= '<td><div class="ts-vcsc-icon-preview" data-name="ts-' . $key . '" data-code="' . $option . '" data-font="' . strtolower($font) . '" data-count="' . $outputcount . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i data-viewport="" class="ts-font-icon ts-' . $key . ' ' . $animationloop[$outputcount] . '" style="' . $icon_size . $icon_color . $icon_background . ' " title="' . $key . '"></i></span></div></td>';
									} else if ($animationtype == "Hover") {
										$output .= '<td><div class="ts-vcsc-icon-preview" data-name="ts-' . $key . '" data-code="' . $option . '" data-font="' . strtolower($font) . '" data-count="' . $outputcount . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i data-viewport="" class="ts-font-icon ts-' . $key . ' ' . $animationloop[$outputcount] . '" style="' . $icon_size . $icon_color . $icon_background . ' " title="' . $key . '"></i></span></div></td>';
									}
									$output .= '</tr>';
								} else {
									break;
								}
								$outputcount = $outputcount + 1;
							}
						}
					}
				$output .= '</tbody>';
				$output .= '</table>';
			$output .= '</div>';
		$output .= '</div>';

		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>