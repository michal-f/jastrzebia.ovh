<div id="ts-settings-general" class="tab-content">
    <h2>General Information</h2>

	<div style="margin-top: 0px; font-size: 13px; text-align: justify;">
		In order to use this plugin, you MUST have the Visual Composer Plugin installed; either as a normal plugin or as part of your theme. If Visual Composer is part of your theme, please ensure that it has not been modified;
		some theme developers heavily modify Visual Composer in order to allow for certain theme functions. Unfortunately, some of these modification prevent this extension pack from working correctly.
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Visual Composer Extensions</h4>
		<div style="margin-top: 10px;">If you are using the "User Groups Access Rules" provided by Visual Composer itself, you MUST enable the new elements in the <a href="options-general.php?page=vc_settings" target="_parent">settings</a> for the actual Visual Composer Plugin.</div>
		<div style="margin-top: 20px;">
			<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="http://codecanyon.net/item/visual-composer-extensions/7190695" target="_blank"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Buy Plugin</a>
			<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="http://tekanewascripts.info/composer/manual/" target="_blank"><img src="<?php echo TS_VCSC_GetResourceURL('images/other/ts_vcsc_manual_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Manual</a>
			<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="http://support.tekanewascripts.info/forums/forum/wordpress-plugins/visual-composer-extensions/" target="_blank"><img src="<?php echo TS_VCSC_GetResourceURL('images/other/ts_vcsc_support_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Support Forum</a>
			<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="http://support.tekanewascripts.info/category/visual-composer-extensions/" target="_blank"><img src="<?php echo TS_VCSC_GetResourceURL('images/other/ts_vcsc_knowledge_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Knowledge Base</a>
			<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="admin.php?page=TS_VCSC_CSS" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/other/ts_vcsc_customcss_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Custom CSS</a>
			<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="admin.php?page=TS_VCSC_JS" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/other/ts_vcsc_customjs_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Custom JS</a>
			<?php
				if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
					echo '<a class="button-secondary" style="width: 150px; margin: 0px auto; text-align: center;" href="admin.php?page=TS_VCSC_License" target="_parent"><img src="' . TS_VCSC_GetResourceURL('images/other/ts_vcsc_license_icon_16x16.png') . '" style="width: 16px; height: 16px; margin-right: 10px;">License</a>';
				}
			?>
		</div>
	</div>
	
	<h2 style="margin-top: 40px;">Basic Settings</h2>
	
	<div style="margin-top: 20px;">
		<h4>Placement of Visual Composer Extensions Menu:</h4>
		<p style="font-size: 10px;">Define where the menu for this plugin should be placed in WordPress; if disabled, the main menu will be placed in the 'Settings' section:</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_mainmenu == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_mainmenu" class="toggle-check ts_vcsc_extend_settings_mainmenu" name="ts_vcsc_extend_settings_mainmenu" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_mainmenu); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_mainmenu == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_mainmenu == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_mainmenu">Give Visual Composer Extensions its own menu</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Use of Language Domain Translations:</h4>
		<p style="font-size: 10px;">Define if the plugin can use its language domain files (stored in the 'locale' folder) in order to automatically be translated into available languages:</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_translationsDomain == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_translationsDomain" class="toggle-check ts_vcsc_extend_settings_translationsDomain" name="ts_vcsc_extend_settings_translationsDomain" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_translationsDomain); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_translationsDomain == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_translationsDomain == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_translationsDomain">Use Plugin Language Files</label>
	</div>
	
	<?php
	if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
		if ($this->TS_VCSC_IconicumStandard == "false") { ?>
			<h2 style="margin-top: 40px;">Iconicum - Font Icon Generator</h2>
			
			<div style="margin-top: 20px;">
				<h4>Provide Shortcode Generator for Font Icons:</h4>
				<p style="font-size: 10px;">Adds a shortcode generator button to the tinyMCE menu to embed font icons directly into the text editor:</p>
				<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_useIconGenerator == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
					<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_useIconGenerator" class="toggle-check ts_vcsc_extend_settings_useIconGenerator" name="ts_vcsc_extend_settings_useIconGenerator" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_useIconGenerator); ?>/>
					<div class="toggle toggle-light" style="width: 80px; height: 20px;">
						<div class="toggle-slide">
							<div class="toggle-inner">
								<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_useIconGenerator == 1 ? 'active' : ''); ?>">Yes</div>
								<div class="toggle-blob"></div>
								<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_useIconGenerator == 0 ? 'active' : ''); ?>">No</div>
							</div>
						</div>
					</div>
				</div>
				<label class="labelToggleBox" for="ts_vcsc_extend_settings_useIconGenerator">Enable Font Icon Generator</label>
			</div>
			
			<div id="ts_vcsc_extend_settings_useIconGenerator_true" style="margin-top: 10px; margin-left: 25px; <?php echo ($ts_vcsc_extend_settings_useIconGenerator == 0 ? 'display: none;' : 'display: block;'); ?>">
				<h4>Placement of Shortcode Generator Button:</h4>
				<p style="font-size: 10px;">If the option is disabled, the button will be placed into the tinyMCE menu bar instead:</p>
				<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_useTinyMCEMedia == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
					<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_useTinyMCEMedia" class="toggle-check ts_vcsc_extend_settings_useTinyMCEMedia" name="ts_vcsc_extend_settings_useTinyMCEMedia" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_useTinyMCEMedia); ?>/>
					<div class="toggle toggle-light" style="width: 80px; height: 20px;">
						<div class="toggle-slide">
							<div class="toggle-inner">
								<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_useTinyMCEMedia == 1 ? 'active' : ''); ?>">Yes</div>
								<div class="toggle-blob"></div>
								<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_useTinyMCEMedia == 0 ? 'active' : ''); ?>">No</div>
							</div>
						</div>
					</div>
				</div>
				<label class="labelToggleBox" for="ts_vcsc_extend_settings_useTinyMCEMedia">Place Generator Button next to "Add Media" Button</span></label>
			</div>
	<?php } else { ?>
		<h2 style="margin-top: 40px;">Iconicum - Font Icon Generator</h2>
		
		<div style="margin-top: 20px; font-size: 13px; text-align: justify;">
			"Iconicum - WordPress Icon Fonts" is already installed and activated as standalone plugin. Therefore, the version that is included with "Visual Composer Extensions" has been disabled in order to prevent conflicts.
		</div>
	<?php }} ?>
	
	<?php if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) { ?>
	
		<h2 style="margin-top: 40px;">Extended Rows & Columns</h2>
		
		<div style="margin-top: 20px; font-size: 13px; text-align: justify;">
			Visual Composer Extensions allows you to extend the available options for Row and Column settings, adding features such as viewport animations (row + column) and a variety of background effects (row). If you already use other
			plugins that provide the same or similiar options you should decide for either one but not use both at the same time as they can cause contradicting settings. Also, if your theme incorporates Visual Composer by itself, some
			themes already provide you with similiar options. In these cases, you should disable the settings below in order to avoid any conflicts.
		</div>
		
		<div style="margin-top: 20px; font-weight: bold;">The extended Row and Column Options require a Visual Composer version of 4.1 or higher, in order to function correctly!</div>
		
		<div style="margin-top: 20px;">
			<h4>Extend Options for Visual Composer Rows:</h4>
			<p style="font-size: 10px;">Extend Row Options with Background Effects and Viewport Animation Settings:</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_additionsRows == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_additionsRows" class="toggle-check ts_vcsc_extend_settings_additionsRows" name="ts_vcsc_extend_settings_additionsRows" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_additionsRows); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_additionsRows == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_additionsRows == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_additionsRows">Extend Row Options</label>
		</div>
		
		<div style="margin-top: 20px;">
			<h4>Extend Options for Visual Composer Columns:</h4>
			<p style="font-size: 10px;">Extend Column Options with Viewport Animation Settings:</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_additionsColumns == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_additionsColumns" class="toggle-check ts_vcsc_extend_settings_additionsColumns" name="ts_vcsc_extend_settings_additionsColumns" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_additionsColumns); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_additionsColumns == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_additionsColumns == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_additionsColumns">Extend Column Options</label>
		</div>
		
		<div style="margin-top: 20px;">
			<h4>Smooth Scroll for Pages:</h4>
			<p style="font-size: 10px;">Extend all pages with Smooth Scroll Feature (will not be applied on mobilde devices); do not use if your theme or another plugin is already implementing a smooth scroll feature:</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_additionsSmoothScroll == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_additionsSmoothScroll" class="toggle-check ts_vcsc_extend_settings_additionsSmoothScroll" name="ts_vcsc_extend_settings_additionsSmoothScroll" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_additionsSmoothScroll); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_additionsSmoothScroll == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_additionsSmoothScroll == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_additionsColumns">Extend Pages with Smooth Scroll</label>
		</div>
	
	<?php } ?>
	
	<?php if ($this->TS_VCSC_CustomPostTypesCheckup == "true") {  ?>
	
		<h2 style="margin-top: 40px;">Manage Custom Post Types</h2>
		
		<div style="margin-top: 20px; font-size: 13px; text-align: justify;">
			Starting with version 2.0, Visual Composer Extensions introduced custom post types, to be used for some of the elements and for more complex layouts. If your theme or another plugin already provides a similiar post
			type (i.e. a post type for "teams"), you can disable the corresponding custom post type that comes with Visual Composer Extensions. Disabling a custom post type will also disable the corresponding Visual Composer elements
			and shortcodes associated with the post type.
		</div>
		
		<div style="margin-top: 20px; display: <?php echo (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeTeam', 1) == 0)) ? "none;" : "block;"); ?>">
			<h4>Visual Composer Team:</h4>
			<p style="font-size: 10px;">Enable or disable the custom post type "VC Team":</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_customTeam == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_customTeam" class="toggle-check ts_vcsc_extend_settings_customTeam" name="ts_vcsc_extend_settings_customTeam" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_customTeam); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_customTeam == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_customTeam == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_customTeam">Enable "VC Team" Post Type</label>
		</div>
		
		<div style="margin-top: 20px; display: <?php echo (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeTestimonial', 1) == 0)) ? "none;" : "block;"); ?>>
			<h4>Visual Composer Testimonials:</h4>
			<p style="font-size: 10px;">Enable or disable the custom post type "VC Testimonials":</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_customTestimonial == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_customTestimonial" class="toggle-check ts_vcsc_extend_settings_customTestimonial" name="ts_vcsc_extend_settings_customTestimonial" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_customTestimonial); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_customTestimonial == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_customTestimonial == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_customTestimonial">Enable "VC Testimonials" Post Type</label>
		</div>
		
		<div style="margin-top: 20px; display: <?php echo (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeSkillset', 1) == 0)) ? "none;" : "block;"); ?>>
			<h4>Visual Composer Skillsets:</h4>
			<p style="font-size: 10px;">Enable or disable the custom post type "VC Skillsets":</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_customSkillset == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_customSkillset" class="toggle-check ts_vcsc_extend_settings_customSkillset" name="ts_vcsc_extend_settings_customSkillset" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_customSkillset); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_customSkillset == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_customSkillset == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_customLogo">Enable "VC Skillsets" Post Type</label>			
		</div>
	
		<div style="margin-top: 20px; display: <?php echo (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeLogo', 1) == 0)) ? "none;" : "none;"); ?>>
			<h4>Visual Composer Logos:</h4>
			<p style="font-size: 10px;">Enable or disable the custom post type "VC Logos":</p>
			<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($ts_vcsc_extend_settings_customLogo == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
				<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_customLogo" class="toggle-check ts_vcsc_extend_settings_customLogo" name="ts_vcsc_extend_settings_customLogo" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_customLogo); ?>/>
				<div class="toggle toggle-light" style="width: 80px; height: 20px;">
					<div class="toggle-slide">
						<div class="toggle-inner">
							<div class="toggle-on <?php echo ($ts_vcsc_extend_settings_customLogo == 1 ? 'active' : ''); ?>">Yes</div>
							<div class="toggle-blob"></div>
							<div class="toggle-off <?php echo ($ts_vcsc_extend_settings_customLogo == 0 ? 'active' : ''); ?>">No</div>
						</div>
					</div>
				</div>
			</div>
			<label class="labelToggleBox" for="ts_vcsc_extend_settings_customLogo">Enable "VC Logos" Post Type</label>			
		</div>
	
	<?php } ?>
	
	<h2 style="margin-top: 40px;">Manage Composer Elements</h2>
	
	<div style="margin-top: 20px; font-size: 13px; text-align: justify;">
		While you can prevent individual elements from becoming available to certain user groups (using the "User Group Access Rules" in the settings for the original Visual Composer Plugin), the elements are technically still
		loaded in the background. In order to allow for an improved overall site performance, you can completely disable unwanted elements that are part of Visual Composer Extensions here. Once disabled, the element and its
		associated shortcode will not be loaded anymore.
	</div>
	
	<div style="margin-top: 20px; margin-bottom: 20px; font-size: 11px; font-weight: bold; color: red; text-align: justify;">
		The original Visual Composer Plugin still requires you to enable the elements based on available user roles using the <a href="options-general.php?page=vc_settings">settings panel</a> for Visual Composer. That settings panel controls
		which users have access to which Visual Composer elements but doesn't stop them from being loaded.
	</div>
	
	<?php
		echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 5%;">';
			echo '<h4>Standard Shortcodes</h4>';
			echo '<p style="font-size: 10px; text-align: justify;">These are the elements that are currently fully supported.</p>';
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				if (($element['type'] != 'demos') && ($element['deprecated'] == 'false') && ($element['type'] != 'external')) {
					echo '<div style="margin: 0 0 10px 0;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . $element['active'] . '" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_custom' . $element['setting'] .'" class="toggle-check ts_vcsc_extend_settings_custom' . $element['setting'] . '" name="ts_vcsc_extend_settings_custom' . $element['setting'] . '" value="1" ' . ($element['active'] == "true" ? ' checked="checked"' : '') . '/>';
							echo '<div class="toggle toggle-light" style="width: 80px; height: 20px;">';
								echo '<div class="toggle-slide">';
									echo '<div class="toggle-inner">';
										echo '<div class="toggle-on ' . ($element['active'] == 'true' ? 'active' : '') . '">Yes</div>';
										echo '<div class="toggle-blob"></div>';
										echo '<div class="toggle-off ' . ($element['active'] == 'false' ? 'active' : '') . '">No</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						echo '<label class="labelToggleBox" for="ts_vcsc_extend_settings_custom' . $element['setting'] . '">Enable "' . $ElementName . '"</label>';				
					echo '</div>';
				}
			}
		echo '</div>';
		echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 5%;">';
			echo '<h4>Deprecated Shortcodes</h4>';
			echo '<p style="font-size: 10px; text-align: justify;">These elements have been deprecated in favor of other elements.</p>';
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				if (($element['type'] != 'demos') && ($element['deprecated'] == 'true') && ($element['type'] != 'external')) {
					echo '<div style="margin: 0 0 10px 0;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . $element['active'] . '" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_custom' . $element['setting'] .'" class="toggle-check ts_vcsc_extend_settings_custom' . $element['setting'] . '" name="ts_vcsc_extend_settings_custom' . $element['setting'] . '" value="1" ' . ($element['active'] == "true" ? ' checked="checked"' : '') . '/>';
							echo '<div class="toggle toggle-light" style="width: 80px; height: 20px;">';
								echo '<div class="toggle-slide">';
									echo '<div class="toggle-inner">';
										echo '<div class="toggle-on ' . ($element['active'] == 'true' ? 'active' : '') . '">Yes</div>';
										echo '<div class="toggle-blob"></div>';
										echo '<div class="toggle-off ' . ($element['active'] == 'false' ? 'active' : '') . '">No</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						echo '<label class="labelToggleBox" for="ts_vcsc_extend_settings_custom' . $element['setting'] . '">Enable "' . $ElementName . '"</label>';	
					echo '</div>';
				}
			}
		echo '</div>';
		echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 0%;">';
			echo '<h4>3rd Party Shortcodes</h4>';
			echo '<p style="font-size: 10px; text-align: justify;">These elements require additional (not included) plugins.</p>';
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				if (($element['type'] != 'demos')  && ($element['type'] == 'external')) {
					echo '<div style="margin: 0 0 10px 0;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . $element['active'] . '" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_custom' . $element['setting'] .'" class="toggle-check ts_vcsc_extend_settings_custom' . $element['setting'] . '" name="ts_vcsc_extend_settings_custom' . $element['setting'] . '" value="1" ' . ($element['active'] == "true" ? ' checked="checked"' : '') . '/>';
							echo '<div class="toggle toggle-light" style="width: 80px; height: 20px;">';
								echo '<div class="toggle-slide">';
									echo '<div class="toggle-inner">';
										echo '<div class="toggle-on ' . ($element['active'] == 'true' ? 'active' : '') . '">Yes</div>';
										echo '<div class="toggle-blob"></div>';
										echo '<div class="toggle-off ' . ($element['active'] == 'false' ? 'active' : '') . '">No</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						echo '<label class="labelToggleBox" for="ts_vcsc_extend_settings_custom' . $element['setting'] . '">Enable "' . $ElementName . '"</label>';	
					echo '</div>';
				}
			}
		echo '</div>';
	?>
	
	<div class="clear clearFixMe"></div>
	
    <h2 style="margin-top: 40px; display: none;">Other Settings</h2>
    
    <div style="margin-top: 20px; display: none;">
        <h4>Viewing Device Detection:</h4>
        <p style="font-size: 10px;">Enable or disable the use of the Device Detection:</p>
        <input type="hidden" name="ts_vcsc_extend_settings_loadDetector" value="0" />
        <input type="checkbox" name="ts_vcsc_extend_settings_loadDetector" id="ts_vcsc_extend_settings_loadDetector" value="1" <?php echo checked('1', $ts_vcsc_extend_settings_loadDetector); ?> />
        <label class="labelCheckBox" for="ts_vcsc_extend_settings_loadDetector">Use Device Detection</label>
    </div>
</div>
