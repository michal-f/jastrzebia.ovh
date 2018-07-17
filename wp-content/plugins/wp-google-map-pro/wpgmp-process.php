<?php
if($_POST)
{
	$output = '<h1 class="marker-heading">'.$_POST['title'].'</h1><p>'.$_POST['description'].'</p>';
	$output .= '<button name="remove-marker" class="remove-marker" title="Remove Marker">Remove Marker</button>';
	echo $output;
}
?>