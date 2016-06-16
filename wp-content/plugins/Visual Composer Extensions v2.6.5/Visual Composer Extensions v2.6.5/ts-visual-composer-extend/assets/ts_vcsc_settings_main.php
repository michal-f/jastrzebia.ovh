<?php
	if (isset($_POST['Submit'])) {
		if (trim ($_POST['ts_vcsc_extend_settings_true']) == 1) {
			// Form Data Sent
			// --------------------------------------------------------------------------------------------------
			update_option('ts_vcsc_extend_settings_loadForcable',					$_POST['ts_vcsc_extend_settings_loadForcable']);
			update_option('ts_vcsc_extend_settings_loadLightbox', 					$_POST['ts_vcsc_extend_settings_loadLightbox']);
			update_option('ts_vcsc_extend_settings_loadTooltip', 					$_POST['ts_vcsc_extend_settings_loadTooltip']);
			update_option('ts_vcsc_extend_settings_loadFonts',						$_POST['ts_vcsc_extend_settings_loadFonts']);
			update_option('ts_vcsc_extend_settings_loadHeader', 					$_POST['ts_vcsc_extend_settings_loadHeader']);
			update_option('ts_vcsc_extend_settings_loadModernizr',					$_POST['ts_vcsc_extend_settings_loadModernizr']);
			update_option('ts_vcsc_extend_settings_loadWaypoints',					$_POST['ts_vcsc_extend_settings_loadWaypoints']);
			update_option('ts_vcsc_extend_settings_loadjQuery',						$_POST['ts_vcsc_extend_settings_loadjQuery']);
			update_option('ts_vcsc_extend_settings_loadEnqueue',					$_POST['ts_vcsc_extend_settings_loadEnqueue']);
			update_option('ts_vcsc_extend_settings_loadCountTo',					$_POST['ts_vcsc_extend_settings_loadCountTo']);
			update_option('ts_vcsc_extend_settings_loadDetector',					$_POST['ts_vcsc_extend_settings_loadDetector']);
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || (get_option('ts_vcsc_extend_settings_extended', 0) == 0)) {
				update_option('ts_vcsc_extend_settings_additionsRows',				$_POST['ts_vcsc_extend_settings_additionsRows']);
				update_option('ts_vcsc_extend_settings_additionsColumns',			$_POST['ts_vcsc_extend_settings_additionsColumns']);
				update_option('ts_vcsc_extend_settings_additionsSmoothScroll',		$_POST['ts_vcsc_extend_settings_additionsSmoothScroll']);
			}
			if ($this->TS_VCSC_CustomPostTypesCheckup == "true") {
				update_option('ts_vcsc_extend_settings_customTeam',					$_POST['ts_vcsc_extend_settings_customTeam']);
				update_option('ts_vcsc_extend_settings_customTestimonial',			$_POST['ts_vcsc_extend_settings_customTestimonial']);
				update_option('ts_vcsc_extend_settings_customSkillset',				$_POST['ts_vcsc_extend_settings_customSkillset']);
				update_option('ts_vcsc_extend_settings_customLogo',					$_POST['ts_vcsc_extend_settings_customLogo']);
			}
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
				update_option('ts_vcsc_extend_settings_useIconGenerator',			$_POST['ts_vcsc_extend_settings_useIconGenerator']);
				update_option('ts_vcsc_extend_settings_useTinyMCEMedia',			$_POST['ts_vcsc_extend_settings_useTinyMCEMedia']);
			}
			update_option('ts_vcsc_extend_settings_mainmenu', 						$_POST['ts_vcsc_extend_settings_mainmenu']);
			update_option('ts_vcsc_extend_settings_translationsDomain',				$_POST['ts_vcsc_extend_settings_translationsDomain']);
			
			// Save Settings VC Extensions Elements
			$TS_VCSC_Extension_Elements 		= get_option('ts_vcsc_extend_settings_StandardElements', '');
			if ($TS_VCSC_Extension_Elements == '') {
				$TS_VCSC_Options_CleanUp 		= "true";
			} else {
				$TS_VCSC_Options_CleanUp 		= "false";
			}
			$TS_VCSC_Extension_Elements 		= array();
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				$key 	= $element['setting'];
				$value 	= $_POST['ts_vcsc_extend_settings_custom' . $key];
				if ($element['type'] != 'demos') {
					$TS_VCSC_Extension_Elements[$key] = $value;
					if ($TS_VCSC_Options_CleanUp == "true") {
						delete_option('ts_vcsc_extend_settings_custom' . $key);
					}
				}
				update_option('ts_vcsc_extend_settings_StandardElements',			$TS_VCSC_Extension_Elements);
			}
			
			// Save WooCommerce Settings
			if ($this->TS_VCSC_WooCommerceActive == "true") {
				$TS_VCSC_WooCommerce_Elements 	= get_option('ts_vcsc_extend_settings_WooCommerceElements', '');
				if ($TS_VCSC_WooCommerce_Elements == '') {
					$TS_VCSC_Options_CleanUp 	= "true";
				} else {
					$TS_VCSC_Options_CleanUp 	= "false";
				}
				$TS_VCSC_WooCommerce_Elements 	= array();
				foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					$key 	= $element['setting'];
					$value 	= $_POST['ts_vcsc_extend_settings_woocommerce' . $key];
					$TS_VCSC_WooCommerce_Elements[$key] = $value;
					if ($TS_VCSC_Options_CleanUp == "true") {
						delete_option('ts_vcsc_extend_settings_woocommerce' . $key);
					}
				}
				update_option('ts_vcsc_extend_settings_WooCommerceElements',		$TS_VCSC_WooCommerce_Elements);
			}
			
			// Language Settings: Google Map
			$TS_VCSC_Google_Map_Language = array (
				'TextCalcShow' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextCalcShow']),
				'TextCalcHide' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextCalcHide']),
				'TextDirectionShow'	=> trim ($_POST['ts_vcsc_extend_settings_languageTextDirectionShow']),
				'TextDirectionHide'	=> trim ($_POST['ts_vcsc_extend_settings_languageTextDirectionHide']),
				'TextViewOnGoogle' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextViewOnGoogle']),
				'TextResetMap' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextResetMap']),
				'PrintRouteText' 	=> trim ($_POST['ts_vcsc_extend_settings_languagePrintRouteText']),
				'TextButtonCalc' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextButtonCalc']),
				'TextSetTarget' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextSetTarget']),
				'TextGeoLocation'	=> trim ($_POST['ts_vcsc_extend_settings_languageTextGeoLocation']),
				'TextTravelMode' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextTravelMode']),
				'TextDriving' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextDriving']),
				'TextWalking' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextWalking']),
				'TextBicy' 			=> trim ($_POST['ts_vcsc_extend_settings_languageTextBicy']),
				'TextWP' 			=> trim ($_POST['ts_vcsc_extend_settings_languageTextWP']),
				'TextButtonAdd' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextButtonAdd']),
				'TextDistance'		=> trim ($_POST['ts_vcsc_extend_settings_languageTextDistance']),
				'TextMapHome' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapHome']),
				'TextMapBikes' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapBikes']),
				'TextMapTraffic' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapTraffic']),
				'TextMapSpeedMiles'	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapSpeedMiles']),
				'TextMapSpeedKM' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapSpeedKM']),
				'TextMapNoData' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapNoData']),
				'TextMapMiles' 		=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapMiles']),
				'TextMapKilometes' 	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapKilometes']),
				'TextMapActivate'	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapActivate']),
				'TextMapDeactivate'	=> trim ($_POST['ts_vcsc_extend_settings_languageTextMapDeactivate']),
			);
			update_option('ts_vcsc_extend_settings_translationsGoogleMap', 			$TS_VCSC_Google_Map_Language);
			
			// Language Settings: Countdown
			$TS_VCSC_Countdown_Language = array (
				'DayPlural'			=> trim ($_POST['ts_vcsc_extend_settings_languageDayPlural']),
				'DaySingular'		=> trim ($_POST['ts_vcsc_extend_settings_languageDaySingular']),
				'HourPlural'		=> trim ($_POST['ts_vcsc_extend_settings_languageHourPlural']),
				'HourSingular'		=> trim ($_POST['ts_vcsc_extend_settings_languageHourSingular']),
				'MinutePlural'		=> trim ($_POST['ts_vcsc_extend_settings_languageMinutePlural']),
				'MinuteSingular'	=> trim ($_POST['ts_vcsc_extend_settings_languageMinuteSingular']),
				'SecondPlural'		=> trim ($_POST['ts_vcsc_extend_settings_languageSecondPlural']),
				'SecondSingular'	=> trim ($_POST['ts_vcsc_extend_settings_languageSecondSingular']),
			);
			update_option('ts_vcsc_extend_settings_translationsCountdown', 			$TS_VCSC_Countdown_Language);
			
			// Language Settings: Isotope Posts
			$TS_VCSC_Isotope_Posts_Language = array(
				'ButtonFilter'		=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsButtonFilter']),
				'ButtonLayout'		=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsButtonLayout']),
				'ButtonSort'		=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsButtonSort']),
				'SeeAll'			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsSeeAll']),
				'Timeline' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsTimeline']),
				'Masonry' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsMasonry']),
				'FitRows'			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsFitRows']),
				'StraightDown' 		=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsStraightDown']),
				'Date' 				=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsDate']),
				'Modified' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsModified']),
				'Title' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsTitle']),
				'Author' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsAuthor']),
				'PostID' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsPostID']),
				'Comments' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsComments']),
				'Categories' 		=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsCategories']),
				'Tags' 				=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsTags']),
				// WooCommerce
				'WooFilterProducts'	=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsWooFilterProducts']),
				'WooTitle' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsWooTitle']),
				'WooPrice' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsWooPrice']),
				'WooRating'			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsWooRating']),
				'WooDate' 			=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsWooDate']),
				'WooModified'		=> trim ($_POST['ts_vcsc_extend_settings_languageIsotopePostsWooModified']),
			);
			update_option('ts_vcsc_extend_settings_translationsIsotopePosts',		$TS_VCSC_Isotope_Posts_Language);
			
			// Lightbox Settings
			$backlight	= (trim ($_POST['ts_vcsc_extend_settings_defaultLightboxBacklight']) == 1 ? '' : '#ffffff');
			$TS_VCSC_Lightbox_Defaults = array(
				'thumbs'			=> 'bottom',
				'thumbsize'			=> 50,
				'animation'			=> 'random',
				'captions'			=> 'data-title',
				'duration'			=> 5000,
				'share'				=> 1, // true/false
				'social'			=> 'fb,tw,gp,pin',
				
				'notouch'			=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxNoTouch']), // true/false
				'bgclose'			=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxBGClose']), // true/false
				'nohashes'			=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxHashtag']), // true/false
				'keyboard'			=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxKeyboard']), // 0/1
				'fullscreen'		=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxFullScreen']), // 0/1
				'zoom'				=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxZoom']), // 0/1
				
				'fxspeed'			=> 300,
				'scheme'			=> 'dark',
				'removelight'		=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxBacklight']),
				'backlight'			=> $backlight,
				'usecolor'			=> trim ($_POST['ts_vcsc_extend_settings_defaultLightboxBacklight']), // true/false
			);
			update_option('ts_vcsc_extend_settings_defaultLightboxSettings',		$TS_VCSC_Lightbox_Defaults);
			
			// Save Settings for each Installed Icon Font
			foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
				if (($iconfont['setting'] == 'Custom') && (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '')) {
					update_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'],		$_POST['ts_vcsc_extend_settings_tinymce' . $iconfont['setting']]);
					update_option('ts_vcsc_extend_settings_load' . $iconfont['setting'],		$_POST['ts_vcsc_extend_settings_load' . $iconfont['setting']]);
				} else if ($iconfont['setting'] != 'Custom'){
					update_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'],		$_POST['ts_vcsc_extend_settings_tinymce' . $iconfont['setting']]);
					update_option('ts_vcsc_extend_settings_load' . $iconfont['setting'],		$_POST['ts_vcsc_extend_settings_load' . $iconfont['setting']]);
				}
			}			
			
			// Save Settings for Social Network Default Values
			foreach ($this->TS_VCSC_Social_Networks_Array as $Social_Network => $social) {
				update_option('ts_vcsc_social_link_' . $Social_Network,					trim ($_POST['ts_vcsc_social_link_' . $Social_Network]));
				update_option('ts_vcsc_social_order_' . $Social_Network,				trim ($_POST['ts_vcsc_social_order_' . $Social_Network]));
			}

			update_option('ts_vcsc_extend_settings_updated',							1);

			echo '<script>';
				echo 'window.location="' . $_SERVER['REQUEST_URI'] . '";';
			echo '</script>';
			//Header('Location: '.$_SERVER['REQUEST_URI']);
			Exit();
		}
	} else {
		if ((TS_VCSC_CurrentPageName() == "admin.php") && (get_option('ts_vcsc_extend_settings_mainmenu', 1) == 0)) {
			echo '<script>';
				echo 'window.location="' . site_url() . '/wp-admin/options-general.php?page=TS_VCSC_Extender";';
			echo '</script>';
			Exit();
		} else if ((TS_VCSC_CurrentPageName() == "options-general.php") && (get_option('ts_vcsc_extend_settings_mainmenu', 1) == 1)) {
			echo '<script>';
				echo 'window.location="' . site_url() . '/wp-admin/admin.php?page=TS_VCSC_Extender";';
			echo '</script>';
			Exit();
		}
		
		// Display a Normal Page
		// --------------------------------------------------------------------------------------------------
		$ts_vcsc_extend_settings_tinymceIcon 						= get_option('ts_vcsc_extend_settings_tinymceIcon',					1);
		$ts_vcsc_extend_settings_loadForcable						= get_option('ts_vcsc_extend_settings_loadForcable', 				0);
		$ts_vcsc_extend_settings_loadLightbox						= get_option('ts_vcsc_extend_settings_loadLightbox', 				0);
		$ts_vcsc_extend_settings_loadFonts							= get_option('ts_vcsc_extend_settings_loadFonts', 					0);
		$ts_vcsc_extend_settings_loadTooltip						= get_option('ts_vcsc_extend_settings_loadTooltip', 				0);
		$ts_vcsc_extend_settings_loadHeader							= get_option('ts_vcsc_extend_settings_loadHeader',					0);
		$ts_vcsc_extend_settings_loadModernizr						= get_option('ts_vcsc_extend_settings_loadModernizr',				1);
		$ts_vcsc_extend_settings_loadWaypoints						= get_option('ts_vcsc_extend_settings_loadWaypoints',				1);
		$ts_vcsc_extend_settings_loadMagnific						= get_option('ts_vcsc_extend_settings_loadMagnific',				1);
		$ts_vcsc_extend_settings_loadjQuery							= get_option('ts_vcsc_extend_settings_loadjQuery',					0);
		$ts_vcsc_extend_settings_loadEnqueue						= get_option('ts_vcsc_extend_settings_loadEnqueue',					1);
		$ts_vcsc_extend_settings_loadCountTo						= get_option('ts_vcsc_extend_settings_loadCountTo', 				1);
		$ts_vcsc_extend_settings_loadDetector						= get_option('ts_vcsc_extend_settings_loadDetector', 				1);		
		$ts_vcsc_extend_settings_additionsRows						= get_option('ts_vcsc_extend_settings_additionsRows',				0);
		$ts_vcsc_extend_settings_additionsColumns					= get_option('ts_vcsc_extend_settings_additionsColumns',			0);
		$ts_vcsc_extend_settings_additionsSmoothScroll				= get_option('ts_vcsc_extend_settings_additionsSmoothScroll',		0);
		$ts_vcsc_extend_settings_customTeam							= get_option('ts_vcsc_extend_settings_customTeam',					0);
		$ts_vcsc_extend_settings_customTestimonial					= get_option('ts_vcsc_extend_settings_customTestimonial',			0);
		$ts_vcsc_extend_settings_customSkillset						= get_option('ts_vcsc_extend_settings_customSkillset',				0);
		$ts_vcsc_extend_settings_customLogo							= get_option('ts_vcsc_extend_settings_customLogo',					0);
		$ts_vcsc_extend_settings_useIconGenerator					= get_option('ts_vcsc_extend_settings_useIconGenerator',			0);
		$ts_vcsc_extend_settings_useTinyMCEMedia					= get_option('ts_vcsc_extend_settings_useTinyMCEMedia',				1);
		$ts_vcsc_extend_settings_mainmenu							= get_option('ts_vcsc_extend_settings_mainmenu', 					1);
		$ts_vcsc_extend_settings_translationsDomain					= get_option('ts_vcsc_extend_settings_translationsDomain', 			1);
		
		// VC Extensions Elements Settings
		$TS_VCSC_Extension_Elements 								= get_option('ts_vcsc_extend_settings_StandardElements', 			'');
		
		// WooCommerce Settings
		if ($this->TS_VCSC_WooCommerceActive == "true") {
			$TS_VCSC_WooCommerce_Elements							= get_option('ts_vcsc_extend_settings_WooCommerceElements', 		'');
		}
		
		// Language Settings: Google Map
		$TS_VCSC_Google_Map_Language 								= get_option('ts_vcsc_extend_settings_translationsGoogleMap',		'');
		
		// Language Settings: Countdown
		$TS_VCSC_Countdown_Language 								= get_option('ts_vcsc_extend_settings_translationsCountdown',		'');
		
		// Language Settings: Isotope Posts
		$TS_VCSC_Isotope_Posts_Language 							= get_option('ts_vcsc_extend_settings_translationsIsotopePosts',	'');
		
		// Default Settings: Lightbox
		$TS_VCSC_Lightbox_Defaults 									= get_option('ts_vcsc_extend_settings_defaultLightboxSettings',		'');
		
		// Retrieve Setting for each Installed Icon Font
		foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
			$default = ($iconfont['default'] == "true" ? 1 : 0);
			${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''}	= get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'],		$default);
			${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''}		= get_option('ts_vcsc_extend_settings_load' . $iconfont['setting'],			0);
		}

		// Basic Form Validation
		if (get_option('ts_vcsc_extend_settings_updated') == 1) {
			echo "\n";
			echo "<script type='text/javascript'>" . "\n";
				echo "var SettingsSaved = true;" . "\n";
			echo "</script>" . "\n";
		} else {
			echo "\n";
			echo "<script type='text/javascript'>" . "\n";
				echo "var SettingsSaved = false;" . "\n";
			echo "</script>" . "\n";
		}
		update_option('ts_vcsc_extend_settings_updated',	0);
	}

	if (get_option('ts_vcsc_extend_settings_demo', 1) == 1) {
		echo '<div class="clearFixMe" style="font-weight: bold; text-align: justify; color: green; margin: 20px 0 10px 0; padding: 10px; background: #ffffff; border: 1px solid #dddddd;">Please enter your License Key in order to activate the Auto-Update and the bonus tinyMCE Font Icon Generator features of the plugin!</div>';
	}
?>

<div id="ts_vcsc_extend_errors" style="display: none;"></div>

<form id="ts_vcsc_extend_settings" data-type="settings" class="ts_vcsc_extend_global_settings" name="oscimp_form" style="margin-top: 20px; width: 100%;" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

	<span id="gallery_settings_true" style="display: none !important; margin-bottom: 20px;">
		<input type="text" style="width: 20%;" id="ts_vcsc_extend_settings_true" name="ts_vcsc_extend_settings_true" value="0" size="100">
		<input type="text" style="width: 20%;" id="ts_vcsc_extend_settings_count" name="ts_vcsc_extend_settings_count" value="0" size="100">
	</span>

	<div class="wrapper">
		<div id="v-nav">
			<ul id="v-nav-main" data-type="settings">
				<li id="link-ts-settings-logo" class="first" style="border-bottom: 1px solid #DDD; height: 76px;">
					<img style="width: 210px; height: auto; margin: 0 auto;" src="<?php echo TS_VCSC_GetResourceURL('images/logos/tekanewa_scripts.png'); ?>">
				</li>
				<li id="link-ts-settings-submit" style="border-bottom: 1px solid #DDD;">
					<div class="submit" style="width: 175px; margin: 0px auto;">
						<input id="ts_vcsc_extend_settings_validate_1" title="Click here to validate your global Settings." style="margin: 10px auto;" class="ts_vcsc_extend_settings_validate button-primary ButtonSubmit TS_Tooltip" type="submit" name="Submit" value="Save Settings" />
						<input id="ts_vcsc_extend_settings_submit_1" title="Click here to save global Settings" style="margin: 10px auto; display: none;" class=" ts_vcsc_extend_settings_submit button-primary ButtonSubmit TS_Tooltip" type="submit" name="Submit" value="Save Settings" />
					</div>
				</li>
				<li id="link-ts-settings-general" 		data-tab="ts-settings-general" 			data-order="1"		data-name="General Settings"		class="link-data current"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-composer.png'); ?>">General Settings<span id="errorTab1" class="errorMarker"></span></li>
				<li id="link-ts-settings-language" 		data-tab="ts-settings-language" 		data-order="2"		data-name="Language Settings"		class="link-data"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-translate.png'); ?>">Language Settings<span id="errorTab2" class="errorMarker"></span></li>
				<li id="link-ts-settings-iconfont" 		data-tab="ts-settings-iconfont" 		data-order="3"		data-name="Icon Font Settings"		class="link-data"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-tinymce.png'); ?>">Font Manager<span id="errorTab3" class="errorMarker"></span></li>
				<?php
					if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_fontimport', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
						echo '<a href="admin.php?page=TS_VCSC_Uploader" target="_parent" style="color: #000000;">';
							echo '<li id="link-ts-settings-import" 				data-tab="ts-settings-import" 			data-order="4"		data-name="Import Font"				class="link-url"><img style="width: 16px; height: 15px; margin-right: 5px;" src="' . TS_VCSC_GetResourceURL('css/settings/settings-import.png') . '">Import Font<span id="errorTab4" class="errorMarker"></span></li>';
						echo '</a>';
					}
				?>
				<li id="link-ts-settings-social" 		data-tab="ts-settings-social" 			data-order="5"		data-name="Social Defaults"			class="link-data"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-share.png'); ?>">Social Networks<span id="errorTab5" class="errorMarker"></span></li>
				<li id="link-ts-settings-lightbox" 		data-tab="ts-settings-lightbox" 		data-order="6"		data-name="Lightbox Settings"		class="link-data"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-lightbox.png'); ?>">Lightbox Settings<span id="errorTab6" class="errorMarker"></span></li>
				<li id="link-ts-settings-files" 		data-tab="ts-settings-files" 			data-order="7"		data-name="External Files"			class="link-data"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-external.png'); ?>">External Files<span id="errorTab7" class="errorMarker"></span></li>
				
				<?php
					if ($this->TS_VCSC_WooCommerceActive == "true") {
						echo '<li id="link-ts-settings-woocommerce" 	data-tab="ts-settings-woocommerce" 		data-order="8"		data-name="WooCommerce"				class="link-data"><img style="width: 16px; height: 15px; margin-right: 5px;" src="' . TS_VCSC_GetResourceURL('css/settings/settings-woocommerce.png') . '">WooCommerce<span id="errorTab8" class="errorMarker"></span></li>';
					}
				?>
				<a href="admin.php?page=TS_VCSC_Previews" target="_parent" style="color: #000000;">
					<li id="link-ts-settings-iconview" 					data-tab="ts-settings-iconview" 		data-order="8"		data-name="Icon Preview"			class="link-url"><img style="width: 16px; height: 15px; margin-right: 5px;" src="<?php echo TS_VCSC_GetResourceURL('css/settings/settings-preview.png'); ?>">Icon Previews<span id="errorTab8" class="errorMarker"></span></li>
				</a>
				<?php
					if ($this->TS_VCSC_IconicumStandard == "false") {
						if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
							echo '<a href="admin.php?page=TS_VCSC_Generator" target="_parent" style="color: #000000;">';
								echo '<li id="link-ts-settings-generator" 		data-tab="ts-settings-generator"	data-order="9"		data-name="Icon Generator"			class="link-url"><img style="width: 16px; height: 15px; margin-right: 5px;" src="' . TS_VCSC_GetResourceURL('css/settings/settings-other.png') . '">Icon Generator<span id="errorTab9" class="errorMarker"></span></li>';
							echo '</a>';
						}
					}
					if (current_user_can('manage_options')) {
						if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
							echo '<a href="admin.php?page=TS_VCSC_CSS" target="_parent" style="color: #000000;">';
								echo '<li id="link-ts-settings-customcss" 	data-tab="ts-settings-customcss"	data-order="10"		data-name="Add Custom CSS"			class="link-url"><img style="width: 16px; height: 15px; margin-right: 5px;" src="' . TS_VCSC_GetResourceURL('css/settings/settings-css.png') . '">Custom CSS<span id="errorTab10" class="errorMarker"></span></li>';
							echo '</a>';
							echo '<a href="admin.php?page=TS_VCSC_JS" target="_parent" style="color: #000000;">';
								echo '<li id="link-ts-settings-customjs" 	data-tab="ts-settings-customjs" 	data-order="11"		data-name="Add Custom JS"			class="link-url"><img style="width: 16px; height: 15px; margin-right: 5px;" src="' . TS_VCSC_GetResourceURL('css/settings/settings-js.png') . '">Custom JS<span id="errorTab11" class="errorMarker"></span></li>';
							echo '</a>';
						}
						if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
							echo '<a href="admin.php?page=TS_VCSC_License" target="_parent" style="color: #000000;">';
								echo '<li id="link-ts-settings-license" 	data-tab="ts-settings-license"		data-order="12"		data-name="Licence Key"				class="link-url last"><img style="width: 16px; height: 15px; margin-right: 5px;" src="' . TS_VCSC_GetResourceURL('css/settings/settings-license.png') . '">License Key<span id="errorTab12" class="errorMarker"></span></li>';
							echo '</a>';
						}
					}
				?>
			</ul>

			<?php
				include('ts_vcsc_settings_general.php');
				include('ts_vcsc_settings_language.php');
				include('ts_vcsc_settings_iconfont.php');
				include('ts_vcsc_settings_social.php');
				include('ts_vcsc_settings_lightbox.php');
				include('ts_vcsc_settings_external.php');
				if ($this->TS_VCSC_WooCommerceActive == "true") {
					include('ts_vcsc_settings_woocommerce.php');
				}
			?>
        </div>
    </div>
</form>
