<?php
	add_shortcode('TS-VCSC-Spacer', 'TS_VCSC_Spacer_Function');
	add_shortcode('TS_VCSC_Spacer', 'TS_VCSC_Spacer_Function');
	function TS_VCSC_Spacer_Function ($atts) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-visual-composer-extend-front');
		}
	
		extract( shortcode_atts( array(
			'height'					=> '10',
			'css'						=> '',
		), $atts ));
		
		$output = $notice = $visible = '';
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Spacer', $atts);
		} else {
			$css_class	= '';
		}
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode == "true") {
			$notice 	.= '<span style="text-align: center; color: #D10000; font-weight: bold;">TS Spacer / Clear</span>';
			$visible 	.= 'text-align: center; min-height: 30px; height: ' . absint($height + 10) . 'px; visibility: visible; border-top: 1px solid #ededed; border-bottom: 1px solid #ededed; padding: 5px 0;';
		} else {
			$visible 	.= 'text-align: center; line-height: ' . absint($height) . 'px; height: ' . absint($height) . 'px;';
		}
	
		$output = '<div class="ts-spacer clearboth ' . $css_class . '" style="' . $visible . '">' . $notice . '</div>';
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>