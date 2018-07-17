jQuery(document).ready( function( $ ) {
	
// Google Font preview for admin area
function cf7_style_font_preview( select, area ) {
    $.ajaxSetup( { cache: false } );
    
	$("."+select).on( "click", function() {
        var opt  = $(this).attr( "value" ).split( ":" );
		$("."+area).css( "font-family", opt[0] );
		return false;
    });	
}

	cf7_style_font_preview( "font-load", "font-viewer3" );
	cf7_style_font_preview( "font-load", "font-viewer" );
	cf7_style_font_preview( "font-load2", "font-viewer2" );
	
});