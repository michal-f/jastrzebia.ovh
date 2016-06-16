<?php
	$ts_vcsc_extend_settings_licenseKeyed 									= '';
	$ts_vcsc_extend_settings_licenseRemove									= 'false';
	
	if (isset($_POST['License'])) {
		$ts_vcsc_extend_settings_license 						= trim ($_POST['ts_vcsc_extend_settings_license']);
		$ts_vcsc_extend_settings_licenseKeyed 					= get_option('ts_vcsc_extend_settings_licenseKeyed',				'emptydelimiterfix');
		$ts_vcsc_extend_settings_licenseInfo 					= get_option('ts_vcsc_extend_settings_licenseInfo',					'');
		update_option('ts_vcsc_extend_settings_license', 		$ts_vcsc_extend_settings_license);
		update_option('ts_vcsc_extend_settings_licenseUpdate', 	1);
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		//Header('Location: '.$_SERVER['REQUEST_URI']);
		Exit();
	} else if (isset($_POST['Unlicense'])) {
		update_option('ts_vcsc_extend_settings_license', 		'');
		update_option('ts_vcsc_extend_settings_licenseKeyed', 	'unlicenseinprogress');
		update_option('ts_vcsc_extend_settings_licenseUpdate', 	1);
		echo '<script> window.location="' . $_SERVER['REQUEST_URI'] . '"; </script> ';
		//Header('Location: '.$_SERVER['REQUEST_URI']);
		Exit();
	} else {
		TS_VCSC_ShowInformation('7190695');
		$ts_vcsc_extend_settings_license 						= get_option('ts_vcsc_extend_settings_license',						'');
		$ts_vcsc_extend_settings_licenseKeyed 					= get_option('ts_vcsc_extend_settings_licenseKeyed',				'emptydelimiterfix');
		$ts_vcsc_extend_settings_licenseInfo 					= get_option('ts_vcsc_extend_settings_licenseInfo',					'');

		if ($ts_vcsc_extend_settings_licenseKeyed == 'unlicenseinprogress') {
			update_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix');
			$ts_vcsc_extend_settings_licenseRemove 				= 'true';
		}
		
		if (get_option('ts_vcsc_extend_settings_licenseUpdate') == 1) {
			TS_VCSC_checkEnvatoAPI();
			echo "\n";
			echo "<script type='text/javascript'>" . "\n";
			echo "SettingsLicenseUpdate = true;" . "\n";
			if (get_option('ts_vcsc_extend_settings_licenseValid') == 1) {
				echo 'VC_Extension_Demo = false;' . "\n";
			} else {
				echo 'VC_Extension_Demo = true;' . "\n";
			}
			if (strlen(get_option('ts_vcsc_extend_settings_license')) != 0) {
				echo "SettingsLicenseKey = true;" . "\n";
			} else {
				echo "SettingsLicenseKey = false;" . "\n";
			}
			if ($ts_vcsc_extend_settings_licenseRemove == 'true') {
				echo "SettingsUnLicensing = true;" . "\n";
			} else {
				echo "SettingsUnLicensing = false;" . "\n";
			}
			echo "</script>" . "\n";
		} else {
			echo "\n";
			echo "<script type='text/javascript'>" . "\n";
			echo "SettingsLicenseUpdate = false;" . "\n";
			if (get_option('ts_vcsc_extend_settings_licenseValid') == 1) {
				echo 'VC_Extension_Demo = false;' . "\n";
			} else {
				echo 'VC_Extension_Demo = true;' . "\n";
			}
			if (strlen(get_option('ts_vcsc_extend_settings_license')) != 0) {
				echo "SettingsLicenseKey = true;" . "\n";
			} else {
				echo "SettingsLicenseKey = false;" . "\n";
			}
			if ($ts_vcsc_extend_settings_licenseRemove == 'true') {
				echo "SettingsUnLicensing = true;" . "\n";
			} else {
				echo "SettingsUnLicensing = false;" . "\n";
			}
			echo "</script>" . "\n";
		}
		update_option('ts_vcsc_extend_settings_licenseUpdate', 	0);
		
		$LicenseCheckStatus = "";
	}
	
	// Check License Key with Envato API
	function TS_VCSC_checkEnvatoAPI() {
		global $VISUAL_COMPOSER_EXTENSIONS;
		if (strlen(get_option('ts_vcsc_extend_settings_license')) != 0) {
			$envato_code 													= get_option('ts_vcsc_extend_settings_license');
		} else {
			$envato_code 													= "";
		}
		$ts_vcsc_extend_settings_licenseKeyed 								= get_option('ts_vcsc_extend_settings_licenseKeyed',				'emptydelimiterfix');
		if (($ts_vcsc_extend_settings_licenseKeyed != $envato_code) || ($envato_code == "")) {
			if ((function_exists('wp_remote_get')) && (strlen($envato_code) != 0)) {
				$remoteResponse = wp_remote_get($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_External_URL . $envato_code . '&clienturl=' . site_url());
				$responseText 	= wp_remote_retrieve_body($remoteResponse);
				$responseCode 	= wp_remote_retrieve_response_code($remoteResponse);
			} else if ((function_exists('wp_remote_post')) && (strlen($envato_code) != 0)) {
				$remoteResponse = wp_remote_post($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_External_URL . $envato_code . '&clienturl=' . site_url());
				$responseText 	= wp_remote_retrieve_body($remoteResponse);
				$responseCode 	= wp_remote_retrieve_response_code($remoteResponse);
			} else {
				$remoteResponse = "";
				$responseText	= "";
				$responseCode 	= "";
			}
			
			if (($responseCode == 200) && (strlen($responseText) != 0)) {
				if ((strlen($envato_code) == 0) || (strpos($responseText, $envato_code) === FALSE)) {
					update_option('ts_vcsc_extend_settings_licenseValid', 	0);
					update_option('ts_vcsc_extend_settings_licenseKeyed', 	'emptydelimiterfix');
					update_option('ts_vcsc_extend_settings_licenseInfo', 	((strlen($envato_code) != 0) ? $responseText : ''));
					update_option('ts_vcsc_extend_settings_demo', 			1);
					$LicenseCheckStatus = '<div class="clearFixMe" style="color: red; font-weight: bold; padding-bottom: 10px;">License Check has been initiated but was unsuccessful!</div>';
					$LicenseCheckSuccess = 0;
				} else if ((strlen($envato_code) != 0) && (strpos($responseText, $envato_code) != FALSE)) {
					update_option('ts_vcsc_extend_settings_licenseValid', 	1);
					update_option('ts_vcsc_extend_settings_licenseKeyed', 	$envato_code);
					update_option('ts_vcsc_extend_settings_licenseInfo', 	str_replace("Link_To_Envato_Image", TS_VCSC_GetResourceURL('images/envato/envato_logo.png'), $responseText));
					update_option('ts_vcsc_extend_settings_demo', 			0);
					$LicenseCheckStatus = '<div class="clearFixMe" style="color: green; font-weight: bold; padding-bottom: 10px;">License Check has been succesfully completed!</div>';
					$LicenseCheckSuccess = 1;
				} else {
					update_option('ts_vcsc_extend_settings_licenseValid', 	0);
					update_option('ts_vcsc_extend_settings_licenseKeyed', 	'emptydelimiterfix');
					update_option('ts_vcsc_extend_settings_licenseInfo', 	((strlen($envato_code) != 0) ? $responseText : ''));
					update_option('ts_vcsc_extend_settings_demo', 			1);
					$LicenseCheckStatus = '<div class="clearFixMe" style="color: red; font-weight: bold; padding-bottom: 10px;">License Check has been initiated but was unsuccessful!</div>';
					$LicenseCheckSuccess = 0;
				}
			} else {
				update_option('ts_vcsc_extend_settings_licenseValid', 		0);
				update_option('ts_vcsc_extend_settings_licenseKeyed', 		'emptydelimiterfix');
				update_option('ts_vcsc_extend_settings_licenseInfo', 		'');
				update_option('ts_vcsc_extend_settings_demo', 				1);
				$LicenseCheckStatus = '<div class="clearFixMe" style="color: red; font-weight: bold; padding-bottom: 10px;">License Check could not be initiated - Missing License Key!</div>';
				$LicenseCheckSuccess = 0;
			}
		} else {
			$LicenseCheckSuccess = 0;
			$LicenseCheckStatus = '<div class="clearFixMe" style="color: green; font-weight: bold; padding-bottom: 10px;">License has been validated already!</div>';
		}
	}
?>

<?php
	if (get_option('ts_vcsc_extend_settings_demo', 1) == 1) {
		echo '<div class="clearFixMe" style="font-weight: bold; text-align: justify; color: green; margin-bottom: 10px; padding: 10px; background: #ffffff; border: 1px solid #dddddd;">Please enter your License Key in order to activate the Auto-Update and the bonus tinyMCE Font Icon Generator features of the plugin!</div>';
		if (is_multisite()) {
			echo '<div class="clearFixMe" style="font-weight: bold; text-align: justify; color: red; margin-bottom: 10px; padding: 10px; background: #ffffff; border: 1px solid #dddddd;">In a multi-site setup, you MUST enter the license code on the ACTUAL site the plugin is used on and NOT in the Super-Admin section.</div>';
		}
	}
?>

<form id="ts_facebook_gallery_license" class="ts_facebook_gallery_license" name="oscimp_form" style="width: 100%;" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

	<a class="button-secondary" style="width: 200px; margin: 10px auto 10px auto; text-align: center;" href="<?php echo $this->settingsLink; ?>" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Back to Plugin Settings</a>

	<div class="wrapper" style="min-height: 100px; width: 100%; margin-top: 20px;">
		<table style="border: 1px solid #DDDDDD; min-height: 100px; width: 100%;">
			<tr>
				<td style="width: 210px; padding: 0px 20px 0px 20px; border-right: 1px solid #DDDDDD; background: #FFFFFF;"><?php echo get_option('ts_vcsc_extend_settings_envatoInfo'); ?></td>
				<td>
					<div>
						<h4 style="margin-top: 20px;"><span style="margin-left: 10px;">Envato Purchase License Key:</span></h4>
						<p style="margin-top: 5px; margin-left: 10px; margin-bottom: 15px;">Please enter your Envato Purchase License Key here:</p>
						<?php echo $LicenseCheckStatus; ?>
						<label style="margin-left: 10px;" class="Uniform" for="ts_vcsc_extend_settings_license">Envato License Key:</label>
						<input class="<?php echo ((get_option('ts_vcsc_extend_settings_licenseValid') == 0) ? "Required" : ""); ?>" type="password" style="width: 20%; height: 30px; margin: 0 10px;" id="ts_vcsc_extend_settings_license" name="ts_vcsc_extend_settings_license" value="<?php echo $ts_vcsc_extend_settings_license; ?>" size="100">
						<?php
							if (strlen(get_option('ts_vcsc_extend_settings_license')) != 0) {
								echo get_option('ts_vcsc_extend_settings_licenseInfo');
								if (get_option('ts_vcsc_extend_settings_licenseValid') == 0) {
									echo '<div class="ts_vcsc_extend_messi_link clearFixMe" data-title="Retrieve your Envato License Code" data-source="' . TS_VCSC_GetResourceURL('images/envato/envato_find_license_key.png') .'" style="cursor: pointer; margin-left: 10px; margin-top: 10px;">';
										echo '<img style="float: left; border: 1px solid #CCCCCC; margin: 0px auto; max-width: 125px; height: auto;" src="' . TS_VCSC_GetResourceURL('images/envato/envato_find_license_key.png') .'">';
									echo '</div>';
									echo '<div style="margin-left: 10px; margin: 10px 0 20px 10px; width: 100%; float: left;">Click on Image to get Directions to retrieve your Envato License Key.</div>';
								}
							} else {
								echo '<span id="Envato_Key_Missing" style="color: red;">Please enter your Purchase/License Key!</span>';
								echo '<div class="ts_vcsc_extend_messi_link clearFixMe" data-title="Retrieve your Envato License Code" data-source="' . TS_VCSC_GetResourceURL('images/envato/envato_find_license_key.png') .'" style="cursor: pointer; margin-left: 10px; margin-top: 10px;">';
									echo '<img style="float: left; border: 1px solid #CCCCCC; margin: 0px auto; max-width: 125px; height: auto;" src="' . TS_VCSC_GetResourceURL('images/envato/envato_find_license_key.png') .'">';
								echo '</div>';
								echo '<div style="margin-left: 10px; margin: 10px 0 20px 10px; width: 100%; float: left;">Click on Image to get Directions to retrieve your Envato License Key.</div>';
							}
						?>
						<div style="height: 20px; display: block;"></div>
					</div>
				</td>
			</tr>
		</table>
		<div id="ts-settings-summary" style="display: none;" data-extended="<?php echo get_option('ts_vcsc_extend_settings_extended', 0); ?>" data-summary="<?php echo get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix'); ?>"><?php echo get_option('ts_vcsc_extend_settings_licenseInfo', ''); ?></div>
    </div>

	<span class="submit">
		<input title="Click here to check your Envato License." style="width: 200px; margin-top: 20px;" class="button-primary ButtonSubmit TS_Tooltip" type="submit" name="License" value="Check License" />
		<?php if (get_option('ts_vcsc_extend_settings_demo', 1) == 0) { ?>
			<input title="Click here to unlicense this installation of Visual Composer Extensions." style="width: 200px; margin-top: 20px; float: right;" class="button-secondary ButtonUnLicense TS_Tooltip" type="submit" name="Unlicense" value="Unlicense Plugin" />
		<?php } ?>
	</span>
</form>
