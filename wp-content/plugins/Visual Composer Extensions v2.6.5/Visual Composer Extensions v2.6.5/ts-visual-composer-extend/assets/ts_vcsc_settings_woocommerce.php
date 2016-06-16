<div id="ts-settings-woocommerce" class="tab-content">
    <h2>General Information</h2>

	<div style="margin-top: 0px; font-size: 13px; text-align: justify;">
		Visual Composer Extensions includes a set of elements that can be used to embed the shortcodes that are part of WooCommerce with Visual Composer. No extra styling will be applied; all standard shortcodes will be processed
		by WooCommerce directly. If you encounter errors or styling issues with any of the standard shortcodes, please turn to WooCommerce for a solution as Visual Composer Extensions does not handle the shortcodes.
	</div>
	
	<h2 style="margin-top: 40px;">Manage WooCommerce Elements</h2>
	
	<div style="margin-top: 20px; margin-bottom: 20px; font-size: 13px; text-align: justify;">
		While you can prevent individual elements from becoming available to certain user groups (using the "User Group Access Rules" in the settings for the original Visual Composer Plugin), the elements are technically still
		loaded in the background. In order to allow for an improved overall site performance, you can completely disable unwanted elements that are part of Visual Composer Extensions here. Once disabled, the element itself will
		not be loaded anymore. The WooCommerce plugin will still load the associated shortcode, however.
	</div>
	
	<?php
		echo '<div style="width: 30%; float: left; min-width: 275px; margin-right: 5%;">';
			echo '<h4>Standard Shortcodes</h4>';
			echo '<p style="font-size: 10px; text-align: justify;">These elements reflect the standard shortcodes already included in WooCommerce.</p>';
			foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
				if (($element['type'] == 'internal') && ($element['deprecated'] == 'false')) {
					echo '<div style="margin: 0 0 10px 0;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . $element['active'] . '" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_woocommerce' . $element['setting'] .'" class="toggle-check ts_vcsc_extend_settings_woocommerce' . $element['setting'] . '" name="ts_vcsc_extend_settings_woocommerce' . $element['setting'] . '" value="1" ' . ($element['active'] == "true" ? ' checked="checked"' : '') . '/>';
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
			echo '<h4>Custom Shortcodes</h4>';
			echo '<p style="font-size: 10px; text-align: justify;">These elements reflect custom shortcodes that are part of Visual Composer Extensions.</p>';
			foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
				if (($element['type'] == 'class') && ($element['deprecated'] == 'false')) {
					echo '<div style="margin: 0 0 10px 0;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . $element['active'] . '" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_woocommerce' . $element['setting'] .'" class="toggle-check ts_vcsc_extend_settings_woocommerce' . $element['setting'] . '" name="ts_vcsc_extend_settings_woocommerce' . $element['setting'] . '" value="1" ' . ($element['active'] == "true" ? ' checked="checked"' : '') . '/>';
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
			echo '<h4>Deprecated Shortcodes</h4>';
			echo '<p style="font-size: 10px; text-align: justify;">These elements have been deprecated in favor of other elements.</p>';
			foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
				if (($element['type'] == 'class') && ($element['deprecated'] == 'true')) {
					echo '<div style="margin: 0 0 10px 0;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . $element['active'] . '" data-width="80" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input type="checkbox" style="display: none; " id="ts_vcsc_extend_settings_woocommerce' . $element['setting'] .'" class="toggle-check ts_vcsc_extend_settings_woocommerce' . $element['setting'] . '" name="ts_vcsc_extend_settings_woocommerce' . $element['setting'] . '" value="1" ' . ($element['active'] == "true" ? ' checked="checked"' : '') . '/>';
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
</div>
