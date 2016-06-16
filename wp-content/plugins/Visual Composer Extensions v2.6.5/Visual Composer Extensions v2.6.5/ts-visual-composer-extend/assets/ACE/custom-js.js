// Function for Custom JS Editor
(function(TS_VCSC_JS_Global, $) {
	var TS_VCSC_JS_Editor,
		TS_VCSC_syncJS = function() {
			$('#ts_vcsc_extend_custom_js_textarea').val(TS_VCSC_JS_Editor.getSession().getValue());
		},
		loadAce = function() {
			TS_VCSC_JS_Editor = ace.edit('ts_vcsc_extend_custom_js');
			TS_VCSC_JS_Global.safecss_editor = TS_VCSC_JS_Editor;
			TS_VCSC_JS_Editor.getSession().setUseWrapMode(true);
			TS_VCSC_JS_Editor.getSession().setTabSize(4);
			TS_VCSC_JS_Editor.getSession().setUseSoftTabs(true);
			TS_VCSC_JS_Editor.setShowPrintMargin( false );
			TS_VCSC_JS_Editor.getSession().setValue( $('#ts_vcsc_extend_custom_js_textarea').val() );
			TS_VCSC_JS_Editor.getSession().setMode("ace/mode/javascript");
			jQuery.fn.spin&&$('#ts_vcsc_extend_custom_js_container').spin(false);
			$('#ts_vcsc_extend_custom_js_form').submit(TS_VCSC_syncJS);
		};
	$(TS_VCSC_JS_Global).load(loadAce);
	TS_VCSC_JS_Global.aceSyncJS = TS_VCSC_syncJS;
} )(this, jQuery);
