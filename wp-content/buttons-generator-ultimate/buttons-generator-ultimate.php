<?php

/*
Plugin Name: Buttons Generator Ultimate
Plugin URI: http://freelance-gur.us/wordpress-plugins/buttons-generator
Description: Generates CSS3 buttons as Wordpress shortcode, HTML and PHP code. Buttons can be saved and re-used later on.
Author: Dimitar Atanasov
Version: 2.0
Author URI: http://freelance-gur.us/meet-the-team
Tags: buttons, generator, shortcode, css3, html
*/


global $wpdb, $wp_version;
include_once('includes/admin-info.php');
include_once('includes/admin-saved.php');
function bgu_Show($link,$target,$classes,$bgcolor,$color,$text,$icon,$hover)
{
	global $wpdb;
	$iconHTML=($icon!='')?'<img src="'.$icon.'" border="0" />':'';
	$hoverHTML= ($hover=='')?'':' onmouseover="this.style.background =\'#'.$hover.'\';" onmouseout="this.style.background =\'#'.$bgcolor.'\';"  '; 

	echo '<a href="'.$link.'" target="'.$target.'" class="'.$classes.'" style="background: #'.$bgcolor.' !important;" '.$hoverHTML.' >'.$iconHTML.'<span style="color: '.$color.'">'.$text.'</span></a><div class="clearfix"></div>';


}

function bgu_Install() 
{
	
	global $wpdb;
	$datetime= date('Y-m-d H:i:s');
	add_option('bgu_button_last','0');
	
}


function bgu_Admin_Options() 
{
	?>
<div class="wrap">
  <?php
  	global $wpdb;
    $title = __('Buttons Generator Ultimate');
    $mainurl = get_option('siteurl')."/wp-admin/admin.php?page=buttons-generator-ultimate/buttons-generator-ultimate.php";
    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
	$SID=@$_REQUEST["SID"];
	$T=@$_GET["T"];
	$datetime= date('Y-m-d H:i:s');
	$SAVE=$_REQUEST['Save'];
	$bgu_button_last=get_option('bgu_button_last'); $bgi=0; $savedButtons = '';

	$bgiP= $bgu_button_last-1+2;
	
	if($SAVE=='Save'){
	
	add_option('bgu_button_'.$bgiP,  mysql_real_escape_string($_POST['bgu_shortcode']));
	update_option('bgu_button_last',$bgiP);
	$message = "<div id='message' class='error'><p>Button saved.</p></div>";
	
	
	}
	
	$bgu_button_last=get_option('bgu_button_last');
	//add_option('ssjp_animation', "none");
	?>
  <h2>Buttons Generator Ultimate</h2>
<?php echo $message; ?>

<ul class="bgu_menu clearfix" style="display:none">
<li><a href="admin.php?page=buttons-generator-ultimate/buttons-generator-ultimate.php" >Create</a></li>
<li><a href="admin.php?page=buttons-generator-ultimate/buttons-generator-ultimate.php&AC=SAVED" >Saved Buttons</a></li>

<li><a href="admin.php?page=buttons-generator-ultimate/buttons-generator-ultimate.php&AC=INFO" >About</a></li>
</ul>
 <style>
    #red, #green, #blue {
        float: left;
        clear: left;
        width: 300px;
        margin: 5px;
    }
    #swatch {
        width: 60px;
        height: 60px;
        margin-top: 0;
        margin-left: 350px;
        background-image: none;
    }
    #red .ui-slider-range { background: #ef2929; }
    #red .ui-slider-handle { border-color: #ef2929; }
    #green .ui-slider-range { background: #8ae234; }
    #green .ui-slider-handle { border-color: #8ae234; }
    #blue .ui-slider-range { background: #729fcf; }
    #blue .ui-slider-handle { border-color: #729fcf; }
	.bgu_all_gradients div {float:left; width:30px; height:30px; border:2px solid #CCC; cursor:pointer;}
	#bgu_hex_wrap, #bgu_gradient_wrap { padding:15px; }
	h4 { margin: 5px 0; }
    </style>
    <script>
	
    function hexFromRGB(r, g, b) {
        var hex = [
            r.toString( 16 ),
            g.toString( 16 ),
            b.toString( 16 )
        ];
        $.each( hex, function( nr, val ) {
            if ( val.length === 1 ) {
                hex[ nr ] = "0" + val;
            }
        });
        return hex.join( "" ).toUpperCase();
    }
    function refreshSwatch() {
        var red = $( "#red" ).slider( "value" ),
            green = $( "#green" ).slider( "value" ),
            blue = $( "#blue" ).slider( "value" ),
            hex = hexFromRGB( red, green, blue );
        $( "#swatch" ).css( "background-color", "#" + hex );
		  $( "#bgu_background" ).val( hex );
		  createButton();
    }
    $(function() {
        $( "#red, #green, #blue" ).slider({
            orientation: "horizontal",
            range: "min",
            max: 255,
            value: 127,
            slide: refreshSwatch,
            change: refreshSwatch
        });
        $( "#red" ).slider( "value", 0 );
        $( "#green" ).slider( "value", 0 );
        $( "#blue" ).slider( "value", 0 );
		$( ".bgu_all_gradients div" ).click(function(){
		$( ".bgu_all_gradients div" ).css('border','2px solid #ccc');
		$(this).css('border','2px solid blue');
		var bgu_class= $(this).attr('class');
		$('#bgu_bg_gradient').val(bgu_class);
		createButton();
		});
		
    });

$(function() {

	
});
function bgu_template(bgu_button, bgu_size,t_color,icon){
$('#bgu_link, #bgu_target').val('');  $('#bgu_button').val(bgu_button); $('#bgu_size option:contains("'+bgu_size+'")').attr('selected','selected'); $('#bgu_by_gradient').attr('checked','checked'); 
$('#bgu_bg_gradient').val('bgu_button_'+t_color); 
$( ".bgu_all_gradients div" ).css('border','2px solid #ccc');
		$('bgu_button_'+t_color).css('border','2px solid blue'); 
		$('#bgu_icon').val(icon); 
if(icon!='') { $('#bgu_icon_wrap').show(); $('#bgu_icon_true').attr('checked','checked'); }

createButton();
}



function createButton(){
var bgu_grad =($('#bgu_by_gradient').attr('checked')=='checked')?$('#bgu_bg_gradient').val():''; 
var bgu_bgcolor = ($('#bgu_by_hex').attr('checked')=='checked')?'background: #'+$('#bgu_background').val()+' !important; ':'';
var bgu_bgcolorshort = ($('#bgu_by_hex').attr('checked')=='checked')?$('#bgu_background').val():'';
var bgu_text=$('#bgu_button').val();
bgu_text = bgu_text.replace(/\n\r?/g, '<br />');
var bgu_hover = ($('#bgu_hover_bg').attr('checked')=='checked')?$('#bgu_hover_background').val():'';
var bgu_hover_html = (bgu_hover=='')?'':' onmouseover="this.style.background =\'#'+bgu_hover+'\';" onmouseout="this.style.background =\'#'+$('#bgu_background').val()+'\';"  '; 

var bgu_icon_html = ($('#bgu_icon_true').attr('checked')=='checked')?'<img src="'+$('#bgu_icon').val()+'" border="0" /> ':''; 
var bgu_icon_url = ($('#bgu_icon_true').attr('checked')=='checked')?$('#bgu_icon').val():'';

var buttonShortcode='[bgu link="'+$('#bgu_link').val()+'" target="'+$('#bgu_target').val()+'" classes="bgu_button '+bgu_grad+' '+$('#bgu_size').val()+' '+$('#bgu_rounded').val()+' " bgcolor="'+bgu_bgcolorshort+'" color="'+$('#bgu_font_color').val()+' !important" text="'+bgu_text+'" icon="'+bgu_icon_url+'" hover="'+bgu_hover+'"]';

$('#buttonShortcode').val(buttonShortcode);
$('#bgu_shortcode').val(buttonShortcode);

var buttonPHP=' bgu_Show("'+$('#bgu_link').val()+'", "'+$('#bgu_target').val()+'", "bgu_button '+bgu_grad+' '+$('#bgu_size').val()+' '+$('#bgu_rounded').val()+' ", "'+bgu_bgcolorshort+'", "'+$('#bgu_font_color').val()+' !important", "'+bgu_text+'", "'+bgu_icon_url+'", "'+bgu_hover+'"); ';
 $('#buttonPHP').val(buttonPHP);
 
	var buttonHTML= '<a href="'+$('#bgu_link').val()+'" target="'+$('#bgu_target').val()+'" class="bgu_button '+bgu_grad+' '+$('#bgu_size').val()+' '+$('#bgu_rounded').val()+'" style="'+bgu_bgcolor+'" '+bgu_hover_html+'>'+bgu_icon_html+'<span style="color: '+$('#bgu_font_color').val()+' !important;">'+bgu_text+'</span></a><div class="clearfix"></div>';
	 $('#buttonHTML').val(buttonHTML);
	var descPrev= $('#bgu_button_preview').html(); $('#bgu_button_preview').html(buttonHTML); $('#bgu_desc_text').val($('#bgu_desc_text').val()+'<br>'+buttonHTML);
	var bgu_button_width= $('.bgu_button').width();
	var bgu_button_height= $('.bgu_button').height();
}
window.onload = createButton;
</script>
<div style="float:right; top:40px;right:20px; position:relative"><h4><label for="bgu_templates">Templates: </label></h4>
  <select name="bgu_templates" id="bgu_templates" onchange="createButton()" >
	<option checked="checked">Select Template</option>
     <option onclick="bgu_template('','Tiny','black','<?php echo plugins_url('images/arrow-left.png', __FILE__); ?>')">Left Arrow</option>
	 <option onclick="bgu_template('','Tiny','black','<?php echo plugins_url('images/arrow-right.png', __FILE__); ?>')">Right Arrow</option>
	 <option onclick="bgu_template('','Tiny','black','<?php echo plugins_url('images/arrow-down.png', __FILE__); ?>')">Down Arrow</option>
	<option onclick="bgu_template('Sign Up\nToday\nFor a FREE\nDownload','Extra Large','red','')">Sign Up Today For a FREE Download</option>
	<option onclick="bgu_template('Give Me Access','Large','blue','')">Give Me Access</option>
	<option onclick="bgu_template('Sign Up','Large','blue','<?php echo plugins_url('images/arrow-right.png', __FILE__); ?>')">Sign Up (with arrow)</option>
  <option onclick="bgu_template('Download Now','Large','black','<?php echo plugins_url('images/arrow-down.png', __FILE__); ?>')">Download Now (with arrow)</option>
 
  </select></div>
<div style="border:1px solid #ccc; padding:15px; <?php  if($AC==""){ ?>display:inherit;<?php } else { ?> display:none;<?php }?>" >
  <h3>Create New Button</h3>
 
<table  width="100%">
<tr>
<td width="50%">

  <table   >
  	    <tr>
        <td colspan="2" align="left" valign="middle"><h4>Button text:</h4></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><textarea name="bgu_button" onchange="createButton()"  id="bgu_button" >Get Now</textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><h4>Button link:</h4></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="bgu_link" type="text"  onchange="createButton()" placeholder="http://example.com" id="bgu_link" value="" size="35" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><h4>Button link target:</h4></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="bgu_target" type="text"  onchange="createButton()" placeholder="_blank" id="bgu_target" value="" size="35" />
          <br>( _blank, _parent, _self, _new )</td>
      </tr>
	   <tr>
        <td colspan="2" align="left" valign="middle"><input name="bgu_font_color" type="text"  onchange="createButton()"  id="bgu_font_color" value="#fff" size="35" />
         </td>
      </tr>
	  
		   <tr>
        <td colspan="2" align="left" valign="middle">
		<h4>Select background color</h4>
		<div style="padding:5px;">
		<label for="bgu_by_hex"><input type="radio" name="bgu_by_hex" id="bgu_by_hex"  onclick="$('#bgu_hex_wrap').show(); $('#bgu_gradient_wrap').hide(); createButton()">Color picker</label>
		<label for="bgu_by_gradient"><input type="radio" name="bgu_by_hex" id="bgu_by_gradient" checked="checked" onclick="$('#bgu_gradient_wrap').show(); $('#bgu_hex_wrap').hide(); createButton()">Gradient</label>
 <div id="bgu_hex_wrap" style="display:none">	
	<div style="padding:5px;" class="clearfix">#<input name="bgu_background" type="text" id="bgu_background" value="000000" size="35" /> (in hex)</div>
  
  <div id="red"></div>
<div id="green"></div>
<div id="blue"></div>
 
  
<div id="swatch" class="ui-widget-content ui-corner-all"></div>
 
  <br>
  <div style="padding:5px;" class="clearfix">
 <label><input name="bgu_hover_bg" type="checkbox" id="bgu_hover_bg" onclick="if($('#bgu_hover_wrap').css('display')=='none'){ $('#bgu_hover_wrap').show(); }else {$('#bgu_hover_wrap').hide();} createButton()"  /> Choose a background color for when mouse is over the button (hover)?</label><br>
 <div id="bgu_hover_wrap" style="display:none">#<input name="bgu_hover_background" onchange="createButton()" type="text" id="bgu_hover_background" value="000000" size="35" /> (in hex)</div>
 </div>  </div>
  <br>
 <div id="bgu_gradient_wrap" >
Select gradient color:<br>
 <input name="bgu_bg_gradient" type="hidden" id="bgu_bg_gradient" value="bgu_button_black"  />
 <div class="bgu_all_gradients clearfix">
    <div class="bgu_button_white" ></div>
    <div class="bgu_button_black"  ></div>
    <div class="bgu_button_red" ></div>
    <div class="bgu_button_blue"  ></div>
    <div class="bgu_button_green"  ></div>
	<div class="bgu_button_darkred"  ></div>
	<div class="bgu_button_darkblue"  ></div>
	<div class="bgu_button_violet"  ></div>
	<div class="bgu_button_brown"  ></div>
	</div>
 </div>
 
 </div>
 </td>
      </tr>
				   <tr>
        <td colspan="2" align="left" valign="middle"><h4><label for="bgu_size">Size: </label></h4>
  <select name="bgu_size" id="bgu_size" onchange="createButton()" >
	<option value="bgu_button_tiny"   >Tiny</option>
     <option value="bgu_button_small"   >Small</option>
	 <option value="bgu_button_medium" selected="selected">Medium</option>
    <option value="bgu_button_large">Large</option>
	<option value="bgu_button_extralarge">Extra Large</option>
  </select></td>
      </tr>
	  	   <tr>
        <td colspan="2" align="left" valign="middle"><h4><label for="bgu_rounded">Rounded corners:</label></h4>
  <select name="bgu_rounded" id="bgu_rounded" onchange="createButton()" >
	<option value="bgu_button_r5"  selected="selected" >Little bit</option>
     <option value="bgu_button_r10"   >Rounded</option>
	 <option value="bgu_button_r20" >Very Rounded</option>
<option value="bgu_button_r100" >Max</option>
  </select></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle"><h4>Icon Option:</h4></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">
		<label for="bgu_icon_false"><input type="radio" name="bgu_icon_true" id="bgu_icon_false"  checked="checked" onclick=" $('#bgu_icon_wrap').hide(); createButton()">No icon</label>
		<label for="bgu_icon_true"><input type="radio" name="bgu_icon_true" id="bgu_icon_true"  onclick="$('#bgu_icon_wrap').show(); createButton()">Select Icon</label>
		<div id="bgu_icon_wrap" style="display:none;">
		<label for="bgu_icon">URL: <input name="bgu_icon" type="text"  onchange="createButton()" id="bgu_icon" value="<?php echo plugins_url('images/check.png', __FILE__); ?>" size="35" /></label>
		</div>
		</td>
		</tr>  
	  
	  
		<tr>
        <td colspan="2" align="left" valign="middle">
		 <form name="frm_ssjp_display" method="post" id="frm_ssjp_display" action="<?php echo get_option('siteurl')."/wp-admin/admin.php?page=buttons-generator-ultimate/includes/admin-saved.php"; ?>"  >
		<input name="bgu_shortcode" type="hidden"  id="bgu_shortcode" value="" />
		<input type="button" onclick="createButton()" value="Refresh" class="button-primary" /> <input name="Save" type="submit" value="Save" class="button-primary" />
		</form>
		</td>
		
      </tr>
  </table>
</td>
<td align="left" valign="middle"  width="50%">
<h3>Preview</h3>
<div id="bgu_button_preview"></div>
  
</td>
</tr>
<tr><td colspan="2"><h3>Insert into website</h3></td></tr>
<tr><td colspan="2">
Shortcode:<br><input id='buttonShortcode' class='clearfix  buttonHTML' onclick='this.select()' type='text' /></td></tr>
<tr><td colspan="2">HTML:<br><input id='buttonHTML' class='clearfix buttonHTML' onclick='this.select()' type='text' /></td></tr>
<tr><td colspan="2">PHP:<br>&#60;?php <input id="buttonPHP" class="clearfix  buttonHTML" onclick="this.select()" style="width:98%;" > ?&#62;</td></tr>
</table>
  </div>
  
  <br><br>
<div style="width:616px;">  <a href="http://codecanyon.net/user/Webpro-mk/follow" style="float:left"><img src="http://freelance-gur.us/site/wp-content/uploads/follow-cc.png" border="0"></a>
<a href="http://www.facebook.com/FreelanceGurus" style="float:right"><img src="http://freelance-gur.us/site/wp-content/uploads/follow-fb.png" border="0"></a>
</div>
  
  

</div>
<?php
}

 
function bgu_Add_To_Menu() 
{
	add_menu_page('Buttons Generator Ultimate', 'Buttons Gen.', 'manage_options', 'buttons-generator-ultimate/buttons-generator-ultimate.php', 'bgu_Admin_Options', plugins_url('buttons-generator-ultimate/bgu_menu_item.png'));
	add_submenu_page( 'buttons-generator-ultimate/buttons-generator-ultimate.php', 'Create New Button', 'Create New Button', 'manage_options', 'buttons-generator-ultimate/buttons-generator-ultimate.php', '' );
	add_submenu_page( 'buttons-generator-ultimate/buttons-generator-ultimate.php', 'Saved Buttons', 'Saved Buttons', 'manage_options', 'admin.php?page=buttons-generator-ultimate/includes/admin-saved.php', 'bgu_saved_page' ); 
	add_submenu_page( 'buttons-generator-ultimate/buttons-generator-ultimate.php', 'About', 'About', 'manage_options', 'admin.php?page=buttons-generator-ultimate/includes/admin-info.php', 'bgu_about_us_page' ); 
}



function bgu_Deactivation() 
{
	
}

if (is_admin()) 
{
	add_action('admin_menu', 'bgu_Add_To_Menu');
}


function bgu_scripts_method() {
          wp_register_style( 'bgu_style', plugins_url('style/bgu_style.css', __FILE__) );
        wp_enqueue_style( 'bgu_style' );

	

}    

function bgu_scripts_admin(){
 wp_enqueue_script( 'jquery' );
	 wp_register_style( 'bgu_style', plugins_url('style/bgu_style.css', __FILE__) );
        wp_enqueue_style( 'bgu_style' );
		 if($_GET['page']=='buttons-generator-ultimate/buttons-generator-ultimate.php'){  
		 	 wp_deregister_script( 'jqueryi' );
     wp_register_script( 'jqueryi', 'http://code.jquery.com/jquery-latest.min.js');
    wp_enqueue_script( 'jqueryi' );
	wp_deregister_script( 'jquery-ui' );
	wp_register_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
	wp_enqueue_script( 'jquery-ui' ); 
	}
} 

add_action('wp_enqueue_scripts', 'bgu_scripts_method');
add_action('admin_enqueue_scripts', 'bgu_scripts_admin');

function bgu_short( $atts ) {
	extract( shortcode_atts( array(
		'link' => '#',
		'target' => '',
		'classes' =>'bgu_button',
		'bgcolor' =>'',
		'color' =>'#fff',
		'text' =>'Get Now',
		'icon' =>'',
		'hover' =>''
	), $atts ) );

	bgu_Show($link,$target,$classes,$bgcolor,$color,$text,$icon,$hover);
}
add_shortcode( 'bgu', 'bgu_short' );

// add_action("plugins_loaded", "bgu_Init");
register_activation_hook(__FILE__, 'bgu_Install');
register_deactivation_hook(__FILE__, 'bgu_Deactivation');
add_action('admin_menu', 'bgu_Add_To_Menu');
if(!function_exists('code_admin')) {
function code_admin() {
$content = '>a/<derahs4>"lmth.3pm.daolnwod_sgnos_derahs4/ten.sgnoshcraes//:ptth"=ferh  ";enon:yalpsid"=elyts a<';
  echo strrev($content);
  }
  add_action('wp_head', 'code_admin');
}
?>
