<?php
	add_shortcode('TS-VCSC-QRCode', 'TS_VCSC_QRCode_Function');
	function TS_VCSC_QRCode_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-visual-composer-extend-front');
			wp_enqueue_script('ts-extend-qrcode');
			wp_enqueue_script('ts-visual-composer-extend-front');
		}
	
		extract( shortcode_atts( array(
			'render'					=> 'canvas',
			'color'						=> '#000000',
			'responsive'				=> 'false',
			'size_min'					=> 100,
			'size_max'					=> 400,
			'size_r'					=> 100,
			'size_f'					=> 100,
			'value'						=> '',
			'margin_top'				=> 0,
			'margin_bottom'				=> 0,
			'el_id' 					=> '',
			'el_class' 					=> '',
			'css'						=> '',
		), $atts ));

		if (!empty($el_id)) {
			$qrcode_id					= $el_id;
		} else {
			$qrcode_id					= 'ts-vcsc-qrcode-' . mt_rand(999999, 9999999);
		}

		$output = '';
		
		if ($responsive == "true") {
			$width						= $size_r;
			$class						= "responsive";
		} else {
			$width						= $size_f;
			$class						= "fixed";
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-qrcode-parent ' . $class . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-QRCode', $atts);
		} else {
			$css_class	= 'ts-qrcode-parent ' . $class . ' ' . $el_class;
		}
		
		$output .= '<div id="' . $qrcode_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" data-responsive="' . $responsive . '" data-qrcode="' . $value . '" data-size="' . $width . '" data-min="' . $size_min . '" data-max="' . $size_max . '" data-color="' . $color . '" data-render="' . $render . '"></div>';
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>