<?php
function bgu_about_us_page(){
?>
<div class="wrap">
  <?php
  
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
	
	
	$bgu_button_last=get_option('bgu_button_last');

	?>
  <h2><?php echo wp_specialchars( $title ); ?></h2>
<?php echo $message; ?>

  <div id="bgu_about_us" style="display:inherit" >  
  
    <h3>Useful information</h3>
	<p style="color:#666">You can add the button as a shortcode on your posts and pages. Check the <a target="_blank" href='http://freelance-gur.us/wordpress-plugins/buttons-generator-ultimate'>documentation</a> for additional information.</p>  
    <p style="color:#666">Check out tips, advices and new versions at <a target="_blank" href='http://freelance-gur.us/wordpress-plugins/buttons-generator-ultimate'>Freelance Gurus</a>.</p>
	<p style="color:#666">Checkout our <a target="_blank" href='http://freelance-gur.us/store'>Store</a> and our <a target="_blank" href='http://www.facebook.com/FreelanceGurus'>Facebook page</a>.</p>
	<p style="color:#666">@Copyright <a target="_blank" href='http://freelance-gur.us/'>Freelance Gurus</a>.</p>
	  </div><br><br>
	<div style="width:616px;">  <a href="http://codecanyon.net/user/Webpro-mk/follow" style="float:left"><img src="http://freelance-gur.us/site/wp-content/uploads/follow-cc.png" border="0"></a>
<a href="http://www.facebook.com/FreelanceGurus" style="float:right"><img src="http://freelance-gur.us/site/wp-content/uploads/follow-fb.png" border="0"></a>
</div>

  
  

</div>
<?php } ?>