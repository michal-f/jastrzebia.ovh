<?php
if (extension_loaded('gd') && function_exists('gd_info')) {
echo "Yes, GD library is INSTALLED on your server";
}
else {
echo "GD library is NOT installed on your server";
}
?>
