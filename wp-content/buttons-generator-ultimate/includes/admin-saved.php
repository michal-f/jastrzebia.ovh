<?php
function bgu_saved_page(){
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
	
	if($SAVE=='Save'){
	
	add_option('bgu_button_'.$bgiP,  mysql_real_escape_string($_POST['bgu_shortcode']));
	update_option('bgu_button_last',$bgiP);
	$message = "<div id='message' class='error'><p>Button saved.</p></div>";
	
	
	}
	
	$bgu_button_last=get_option('bgu_button_last');

	?>
  <h2>Buttons Generator Ultimate</h2>
<?php echo $message; ?>

 

    <div id="bgu_saved"  style="display:inherit">  
    <h3>Saved Buttons</h3>
	<?php 
		if($bgu_button_last>0){
	do{
	$bgu_option = str_replace('\\','',get_option('bgu_button_'.$bgi));
	if($bgu_option!=''){
	do_shortcode($bgu_option);
	echo '<br>Shortcode:<br> <input class=\'clearfix  buttonHTML\' onclick=\'this.select()\' type=\'text\' value=\''.$bgu_option.'\' />';
	}
	$bgi++;
	}while($bgi<$bgu_button_last+1);
	}
	else { $savedButtons ='There are no saved buttons yet..'; }
	
	echo $savedButtons; ?>
	
	</div>
	<br><br>
	<div style="width:616px;">  <a href="http://codecanyon.net/user/Webpro-mk/follow" style="float:left"><img src="http://freelance-gur.us/site/wp-content/uploads/follow-cc.png" border="0"></a>
<a href="http://www.facebook.com/FreelanceGurus" style="float:right"><img src="http://freelance-gur.us/site/wp-content/uploads/follow-fb.png" border="0"></a>
</div>
</div>
<?php } ?>