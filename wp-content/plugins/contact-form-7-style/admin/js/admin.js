/*
jQuery functions for the Admin area
*/
function changeFont( value, $ ) {
	$(".google-fontos").remove();
	if( "none" != value && "undefined" != typeof value ) {
		$("head").append('<link class="google-fontos" rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + value + ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese" />');
		$(".cf7-style.preview-zone p").css("font-family", "'" + value + "', sans-serif" );
	}
}
jQuery ( document ).ready( function( $ ) {
	var selectVar = $('select[name=cf7_style_font_selector]');
	if( $("#manual-style").length > 0 ){
		var editor = CodeMirror.fromTextArea( document.getElementById( "manual-style" ), {
			lineNumbers: true,
			theme: "default",
			mode:  "text/css",
                        indentUnit: 4,
                        styleActiveLine: true
		});
	}
	$('.cf7-style-color-field').wpColorPicker();

	$("#select_all").on("click", function(){
		$(".cf7style_body_select_all input").prop('checked', ( $(this).is(":checked") ) ? true : false );
	});

	changeFont( selectVar.val(), $ );
	selectVar.on( "change", function(){
		changeFont( $( this ).val(), $ );
	});

});

