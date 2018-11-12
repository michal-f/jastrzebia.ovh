<?php
/*
Plugin Name: Scroll Top and Bottom
Plugin URI: http://shariarbd.com/plugins/scroll-top-and-bottom/
Description:   Scroll Top and Bottom is created to scroll to top and bottom of the site. User can go to top and bottom of the site using this tool easily. Now you can control the scrolling speed! and hide on pages, 12 different icon has been added and you can choose them from settings. <strong>Custom Button uploader added, now it's possible to set your own button</strong>. This plugin is created with jQuery and <a href="http://shariarbd.com/" target="_blank">Demo is here.</a> Plugin is created by <cite><a href="http://shariarbd.com/" title="Md. Sahriar">Md. Shariar</a>.</cite>
Version: 2.9.1
Author: Md. Shariar Sarkar
Author URI: http://shariarbd.com/ 
*/



define ( 'STAB_Plugin_url', plugin_dir_url(__FILE__)); // with forward slash (/).

function stab_plugin_menu()
{
	add_menu_page('Scroll Top Bottom' , 'Scroll Top Bottom', 'manage_options', 'stab_plugin_page', 'stab_plugin_page');
}
add_action('admin_menu', 'stab_plugin_menu');



function stab_admin_js() 
{
	if (isset($_GET['page']) && $_GET['page'] == 'stab_plugin_page')
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', STAB_Plugin_url.'js/stab_admin.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
	}
}

function stab_admin_styles()
{
	if (isset($_GET['page']) && $_GET['page'] == 'stab_plugin_page')
	{
		wp_enqueue_style('thickbox');
	}
}
add_action('admin_print_scripts', 'stab_admin_js');
add_action('admin_print_styles', 'stab_admin_styles');



function stab_plugin_page()
{
	if(isset($_POST['STAB_UPDATE'])){
		update_option('STAB_icon_Select',$_POST['STAB_icon_Select']);
		update_option('STAB_scroll_speed',$_POST['STAB_scroll_speed']);
		update_option('stab_up_btn',$_POST['stab_up_btn']);
		update_option('stab_down_btn',$_POST['stab_down_btn']);

		update_option('STAB_pageview',$_POST['STAB_pageview']);
		update_option('STAB_postview',$_POST['STAB_postview']);
		update_option('STAB_onlyHome',$_POST['STAB_onlyHome']);
		update_option('STAB_whichBtn',$_POST['STAB_whichBtn']);
		update_option('STAB_postion',$_POST['STAB_postion']);

		echo '<h3>Plugin has been updated.</h3>';
	}
	$wp_STAB_icon_Select = get_option('STAB_icon_Select');
	$wp_STAB_scroll_speed = get_option('STAB_scroll_speed');
	$stab_up_btn = get_option('stab_up_btn');
	$stab_down_btn = get_option('stab_down_btn');
	$wp_STAB_pageview = get_option('STAB_pageview');
	$wp_STAB_postview = get_option('STAB_postview');
	$wp_STAB_onlyHome = get_option('STAB_onlyHome');
	$wp_STAB_whichBtn = get_option('STAB_whichBtn');
    $wp_STAB_postion = get_option('STAB_postion');


	echo '<div class="wrap">';
	echo '<div id="icon-options-general" class="icon32"></div>';
	?>
	<style type="text/css">
		.icon_choose input{
			margin-bottom:10px;
		}

		.welcome-panel h3{
			margin: 10px 0 0 0;
		}

		.welcome-panel h4{
			margin: 20px 0 0 0;
		}
	</style>  
	<form method="post" id="STAB_OPTION" class="welcome-panel">
		<h2>Scroll Top and Bottom Option Page</h2>

		<h3>Choose Icon</h3>

		<?php
		$icondr="scroll-top-and-bottom/icon/";
		?>

		<table class="icon_choose" width="350" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td> 
					<input type="radio" id="STAB_icon_Select_1" name="STAB_icon_Select" value="1" <?php if($wp_STAB_icon_Select == 1) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_1">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'1_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'1_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_2" name="STAB_icon_Select" value="2" <?php if($wp_STAB_icon_Select == 2) { echo('checked="checked"'); } if($wp_STAB_icon_Select != true) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_2">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'2_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'2_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_3" name="STAB_icon_Select" value="3" <?php if($wp_STAB_icon_Select == 3) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_3">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'3_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'3_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_4" name="STAB_icon_Select" value="4" <?php if($wp_STAB_icon_Select == 4) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_4">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'4_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'4_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
				</td>

				<td>
					<input type="radio" id="STAB_icon_Select_5" name="STAB_icon_Select" value="5" <?php if($wp_STAB_icon_Select == 5) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_5">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'5_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'5_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_6" name="STAB_icon_Select" value="6" <?php if($wp_STAB_icon_Select == 6) { echo('checked="checked"'); }  ?> />
					<label for="STAB_icon_Select_6">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'6_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'6_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_7" name="STAB_icon_Select" value="7" <?php if($wp_STAB_icon_Select == 7) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_7">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'7_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'7_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_8" name="STAB_icon_Select" value="8" <?php if($wp_STAB_icon_Select == 8) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_8">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'8_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'8_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
				</td>

				<td>
					<input type="radio" id="STAB_icon_Select_9" name="STAB_icon_Select" value="9" <?php if($wp_STAB_icon_Select == 9) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_9">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'9_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'9_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_10" name="STAB_icon_Select" value="10" <?php if($wp_STAB_icon_Select == 10) { echo('checked="checked"'); }  ?> />
					<label for="STAB_icon_Select_10">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'10_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'10_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_11" name="STAB_icon_Select" value="11" <?php if($wp_STAB_icon_Select == 11) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_11">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'11_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'11_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
					<br/>
					<input type="radio" id="STAB_icon_Select_12" name="STAB_icon_Select" value="12" <?php if($wp_STAB_icon_Select == 12) { echo('checked="checked"'); } ?> />
					<label for="STAB_icon_Select_12">
						<?php echo '<img src="' .plugins_url( ''.$icondr.'12_u.ico' , dirname(__FILE__) ). '" > '; echo '<img src="' .plugins_url( ''.$icondr.'12_d.ico' , dirname(__FILE__) ). '" > '; ?>
					</label>
				</td>
			</tr>
		</table>

		<br>
		<input type="radio" id="STAB_icon_Select_13" name="STAB_icon_Select" value="13" <?php if($wp_STAB_icon_Select == 13) { echo('checked="checked"'); } ?> />
		<label for="STAB_icon_Select_13"> Or Custom Buttons</label>

		<table>
			<tr>
				<td width="150"><span class="field_name">Up Button Location</span></td>
				<td>
					<input type="text"  name="stab_up_btn" value="<?php echo "$stab_up_btn"; ?>" size="40" />
					<input type="button" class="stab-upload-button button button-primary" value="Upload Button" />
					<br /><span>Enter the Up Button location or upload a button image from your computer.</span>
				</td>
			</tr>

			<tr>
				<td width="150"><span class="field_name"> </span></td>
				<td>

					<br>
				</td>
			</tr>

			<tr>
				<td width="150"><span class="field_name">Down Button Location</span></td>
				<td>
					<input type="text"  name="stab_down_btn"  value="<?php echo "$stab_down_btn"; ?>" size="40" />
					<input type="button" class="stab-upload-button button button-primary" value="Upload Button" />
					<br /><span>Enter the Down Button location or upload a button image from your computer.</span>
				</td>
			</tr>

		</table>



		<label for="STAB_scroll_speed"><h4>Scroll Speed</h4></label>
		<input type="text" name="STAB_scroll_speed" id="STAB_scroll_speed" value="<?php if($wp_STAB_scroll_speed=="") echo "1000"; else echo "$wp_STAB_scroll_speed";?>" > Note: 1000=1s and this field must be a number.
		<br> 
		<label for="STAB_pageview"><h4>Hide On Pages</h4></label>
		<input type="checkbox" name="STAB_pageview" id="STAB_pageview" value="yes" <?php if($wp_STAB_pageview=="yes") echo "checked"; else echo "";?> > <label for="STAB_pageview">if Checked, Scroll buttons will not apper on pages.</label>
		<br> 
		<label for="STAB_postview"><h4>Hide On Posts</h4></label>
		<input type="checkbox" name="STAB_postview" id="STAB_postview" value="yes" <?php if($wp_STAB_postview=="yes") echo "checked"; else echo "";?> > <label for="STAB_postview">if Checked, Scroll buttons will not apper on Posts.</label>
		<br> 
		<label for="STAB_onlyHome"><h4>Show On Only Home Page</h4></label>
		<input type="checkbox" name="STAB_onlyHome" id="STAB_onlyHome" value="yes" <?php if($wp_STAB_onlyHome=="yes") echo "checked"; else echo "";?> > <label for="STAB_onlyHome">if Checked, Scroll buttons will apper only on Home Page.</label>
		<br> 
		<h4>Which Button will work</h4>
		<input type="radio" name="STAB_whichBtn" id="STAB_whichBtn_1" value="1" <?php if($wp_STAB_whichBtn=="1" or $wp_STAB_whichBtn=="") echo "checked"; else echo "";?> > <label for="STAB_whichBtn_1">Both Button.</label>
		<br>
		<input type="radio" name="STAB_whichBtn" id="STAB_whichBtn_2" value="2" <?php if($wp_STAB_whichBtn=="2") echo "checked"; else echo "";?> > <label for="STAB_whichBtn_2">Only Up Button.</label>
		<br>
		<input type="radio" name="STAB_whichBtn" id="STAB_whichBtn_3" value="3" <?php if($wp_STAB_whichBtn=="3") echo "checked"; else echo "";?> > <label for="STAB_whichBtn_3">Only Down Button.</label>
		<br>

		<h4>Button Positions</h4>
		<input type="radio" name="STAB_postion" id="STAB_postion_1" value="1" <?php if($wp_STAB_postion=="1") echo "checked"; else echo "";?> > <label for="STAB_postion_1">Left</label>
		<br>
		<input type="radio" name="STAB_postion" id="STAB_postion_2" value="2" <?php if($wp_STAB_postion=="2") echo "checked"; else echo "";?> > <label for="STAB_postion_2">Center</label>
		<br>
		<input type="radio" name="STAB_postion" id="STAB_postion_3" value="3" <?php if($wp_STAB_postion=="3" or $wp_STAB_postion=="") echo "checked"; else echo "";?> > <label for="STAB_postion_3">Right</label>
		<br>


		
		<div style="float:right; color:#09F; text-transform: capitalize;">
			<strong>If you love this plugin, <a href="http://wordpress.org/extend/plugins/scroll-top-and-bottom/" target="_blank">Please Rate as five star.</a></strong> <br>
			<br>
			<a href="http://shariarbd.com/scroll-top-and-bottom/" target="_blank"> Documentation Page of Scroll top and bottom wordPress plugin</a>
		</div>

		<div style="overflow:hidden; margin-top:20px; margin-bottom:20px;">
			<button type="submit" name="STAB_UPDATE"  class="button button-primary">Update Now</button> 
		</div>
	</form><!-- /#STAB_OPTION -->

	<?php
	echo "</div>";
}



function stab_load_script() { 

	$wp_STAB_pageview = get_option('STAB_pageview');
	$wp_STAB_postview = get_option('STAB_postview');
	$wp_STAB_onlyHome = get_option('STAB_onlyHome');

	if (is_page() && $wp_STAB_pageview =="yes") {
		echo "<style> .scroll-btn-container { display:none!important; }  </style>  \n";
	}
	elseif (is_single() && $wp_STAB_postview =="yes") {
		echo "<style> .scroll-btn-container { display:none!important; }  </style>  \n";
	}
	elseif (!is_front_page() && $wp_STAB_onlyHome =="yes") {
		echo "<style> .scroll-btn-container { display:none!important; }  </style>  \n";
	} 
	else {
		wp_enqueue_script('newscript', plugins_url( '/js/script.js' , __FILE__ ),array('jquery') );
		wp_enqueue_style( 'stab-style', plugins_url('/css/style.css', __FILE__) );
	}
}
add_action( 'wp_enqueue_scripts', 'stab_load_script' );



function scroll_tandb(){
	include('tandb.php');
}
add_action('wp_footer', 'scroll_tandb');
?>