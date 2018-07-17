<div id="ts-settings-lightbox" class="tab-content">
    <h2>Lightbox Settings</h2>
    <p>These settings will be used as global settings for the included lightbox, used in a variety of elements. Most elements include additional lightbox settings and/or options to override the global settings below.</p>
	
	<div style="margin-top: 20px;">
		<h4>Touch & Swipe Navigation:</h4>
		<p style="font-size: 10px;">Define if the lightbox can be navigated via touch and swipe gestures (on supported devices):</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['notouch'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxNoTouch" class="toggle-check ts_vcsc_extend_settings_defaultLightboxNoTouch" name="ts_vcsc_extend_settings_defaultLightboxNoTouch" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['notouch']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['notouch'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['notouch'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxNoTouch">Enable Touch & Swipe Navigation</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Keyboard Navigation:</h4>
		<p style="font-size: 10px;">Define if the lightbox can be operated via keyboard navigation (on supported devices):</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['keyboard'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxKeyboard" class="toggle-check ts_vcsc_extend_settings_defaultLightboxKeyboard" name="ts_vcsc_extend_settings_defaultLightboxKeyboard" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['keyboard']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['keyboard'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['keyboard'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxKeyboard">Enable Keyboard Navigation</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Zoom Feature:</h4>
		<p style="font-size: 10px;">Define if the lightbox should provide a zoom option for over-sized images:</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['zoom'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxZoom" class="toggle-check ts_vcsc_extend_settings_defaultLightboxZoom" name="ts_vcsc_extend_settings_defaultLightboxZoom" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['zoom']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['zoom'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['zoom'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxZoom">Enable Zoom Button</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Full Screen Feature:</h4>
		<p style="font-size: 10px;">Define if the lightbox should provide a full screen option:</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['fullscreen'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxFullScreen" class="toggle-check ts_vcsc_extend_settings_defaultLightboxFullScreen" name="ts_vcsc_extend_settings_defaultLightboxFullScreen" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['fullscreen']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['fullscreen'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['fullscreen'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxFullScreen">Enable Full Screen Button</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Background Close Feature:</h4>
		<p style="font-size: 10px;">Define if the lightbox can be closed by clicking on the lightbox background:</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['bgclose'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxBGClose" class="toggle-check ts_vcsc_extend_settings_defaultLightboxBGClose" name="ts_vcsc_extend_settings_defaultLightboxBGClose" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['bgclose']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['bgclose'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['bgclose'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxBGClose">Enable Background Close</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Remove Hashtag Navigation:</h4>
		<p style="font-size: 10px;">Define if the lightbox should remove hashtags from media elements (otherwise added for navigation purposes and deeplinking):</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['nohashes'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxHashtag" class="toggle-check ts_vcsc_extend_settings_defaultLightboxHashtag" name="ts_vcsc_extend_settings_defaultLightboxHashtag" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['nohashes']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['nohashes'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['nohashes'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxHashtag">Remove Hashtag Navigation</label>
	</div>
	
	<div style="margin-top: 20px;">
		<h4>Remove Backlight Effect:</h4>
		<p style="font-size: 10px;">Define if the lightbox should remove the backlight effect for all elements (will overwrite individual element settings):</p>
		<div class="ts-switch-button ts-composer-switch" data-value="<?php echo ($TS_VCSC_Lightbox_Defaults['removelight'] == 1 ? 'true' : 'false'); ?>" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">
			<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_defaultLightboxBacklight" class="toggle-check ts_vcsc_extend_settings_defaultLightboxBacklight" name="ts_vcsc_extend_settings_defaultLightboxBacklight" value="1" <?php echo checked('1', $TS_VCSC_Lightbox_Defaults['removelight']); ?>/>
			<div class="toggle toggle-light" style="width: 80px; height: 20px;">
				<div class="toggle-slide">
					<div class="toggle-inner">
						<div class="toggle-on <?php echo ($TS_VCSC_Lightbox_Defaults['removelight'] == 1 ? 'active' : ''); ?>">Yes</div>
						<div class="toggle-blob"></div>
						<div class="toggle-off <?php echo ($TS_VCSC_Lightbox_Defaults['removelight'] == 0 ? 'active' : ''); ?>">No</div>
					</div>
				</div>
			</div>
		</div>
		<label class="labelToggleBox" for="ts_vcsc_extend_settings_defaultLightboxBacklight">Remove Backlight Effect</label>
	</div>
</div>
