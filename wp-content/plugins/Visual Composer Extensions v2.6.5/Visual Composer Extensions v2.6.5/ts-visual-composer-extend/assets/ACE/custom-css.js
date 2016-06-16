// Function for Custom CSS Editor
(function(TS_VCSC_CSS_Global, $) {
	var TS_VCSC_CSS_Editor,
		TS_VCSC_syncCSS = function() {
			$('#ts_vcsc_extend_custom_css_textarea').val(TS_VCSC_CSS_Editor.getSession().getValue());
		},
		loadAce = function() {
			TS_VCSC_CSS_Editor = ace.edit('ts_vcsc_extend_custom_css');
			TS_VCSC_CSS_Global.safecss_editor = TS_VCSC_CSS_Editor;
			TS_VCSC_CSS_Editor.getSession().setUseWrapMode(true);
			TS_VCSC_CSS_Editor.getSession().setTabSize(4);
			TS_VCSC_CSS_Editor.getSession().setUseSoftTabs(true);
			TS_VCSC_CSS_Editor.setShowPrintMargin( false );
			TS_VCSC_CSS_Editor.getSession().setValue( $('#ts_vcsc_extend_custom_css_textarea').val() );
			TS_VCSC_CSS_Editor.getSession().setMode("ace/mode/css");
			jQuery.fn.spin&&$('#ts_vcsc_extend_custom_css_container').spin(false);
			$('#ts_vcsc_extend_custom_css_form').submit(TS_VCSC_syncCSS);
		};
	$(TS_VCSC_CSS_Global).load(loadAce);
	TS_VCSC_CSS_Global.aceSyncCSS = TS_VCSC_syncCSS;
} )(this, jQuery);
