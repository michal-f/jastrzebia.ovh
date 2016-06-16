<div id="ts-settings-iconfont" class="tab-content">
    <h2>Icon Font Settings</h2>
    <p>Here you will find settings that relate to the utilized icon fonts.</p>
	
	<div class="" style="font-weight: bold; font-size: 13px; text-align: justify; color: #E00000; margin-top: 10px; padding: 10px; background: #F1F1F1; border: 1px solid #dddddd;">
		Please be aware that the more icons Visual Composer is handling, the longer the setting panels for elements utilizing said icons will take to load.
	</div>

	<div style="margin-top: 20px; width: 100%; color: #005DA0; font-size: 13px;">
		<?php
			if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
				echo '<div>Installed Fonts: ' . count($this->TS_VCSC_Installed_Icon_Fonts) . ' / Active Fonts: ' . $this->TS_VCSC_Active_Icon_Fonts . '</div>';
			} else {
				echo '<div>Installed Fonts: ' . (count($this->TS_VCSC_Installed_Icon_Fonts) - 1) . ' / Active Fonts: ' . $this->TS_VCSC_Active_Icon_Fonts . '</div>';
			}
			echo '<div>Installed Icons: ' . $this->TS_VCSC_Total_Icon_Count . ' / Active Icons: ' . $this->TS_VCSC_Active_Icon_Count . '</div>';
		?>
	</div>
	
    <hr class='style-six' style='margin-top: 20px;'>

    <h4>Include the following Icon Fonts:</h4>
	
    <div id="ts_vcsc_extend_settings_tinymceIconFontError" style="display: none;">
		<span id="ts_vcsc_extend_settings_tinymceIconFontCheck">You must select at least one allowable Icon Font!</span>
    </div>
	
    <div style="margin-top: 20px;" class="ts_vcsc_extend_font_selector_container clearFixMe">
	<?php
	    foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
			if ($iconfont['setting'] != "Custom") {
				$output = '';
				echo '<div class="ts_vcsc_extend_font_selector ' . $iconfont['type'] . '" data-active="' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? 'true' : 'false') . '" data-icons="' . $iconfont['count'] . '" data-name="' . $iconfont['setting'] . '" data-type="' . $iconfont['type'] . '">';
					echo '<img id="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" data-toggle="ts-switch-toggle-' . $iconfont['setting'] . '" data-load="ts-load-toggle-' . $iconfont['setting'] . '" class="ts_vcsc_check_image' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? " checked" : "") .'" style="" src=' . TS_VCSC_GetResourceURL('images/fonts/font_' . strtolower($iconfont['setting']) . '.jpg') . '>';
					echo '<div class="ts_vcsc_extend_font_summary" style="margin-bottom: 10px;">Created by ' . $iconfont['author'] . '</div>';				
					echo '<div class="ts-switch-button ts-composer-switch" data-value="' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? 'true' : 'false') . '" data-width="60" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
						echo '<input style="display: none; " type="checkbox" data-load="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '" data-image="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" data-check="ts_vcsc_extend_settings_tinymceIconFont" name="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '" id="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '" class="validate[funcCall[checkIconFontSelect]] toggle-check ts_vcsc_extend_settings_font" data-error="Allowable Icon Fonts Selection" data-order="2" value="1" ' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? ' checked="checked"' : '') . ' />';
						echo '<div id="ts-switch-toggle-' . $iconfont['setting'] . '" data-load="ts-load-toggle-' . $iconfont['setting'] . '" data-image="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" class="toggle toggle-light ts-switch-toggle" style="width: 60px; height: 20px;">';
							echo '<div class="toggle-slide">';
								echo '<div class="toggle-inner">';
									echo '<div class="toggle-on ' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? 'active' : '') . '">Yes</div>';
									echo '<div class="toggle-blob"></div>';
									echo '<div class="toggle-off ' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 0 ? 'active' : '') . '">No</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<label style="font-weight: bold;" class="labelToggleBox" for="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '">' . $Icon_Font . ' (' . $iconfont['count'] . ' Icons)</label>';
					echo '<span class="ts_tiny_check_load ts_vcsc_extend_settings_load' . $iconfont['setting'] . '_span" style="width: 100%; display: block; margin-top: 5px; margin-bottom: 10px; margin-left: 20px;">';
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 1 ? 'true' : 'false') . '" data-width="60" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input style="display: none; " type="checkbox" data-font="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '" name="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '" id="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '" class="toggle-check ts_vcsc_extend_settings_load" value="1" ' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 1 ? ' checked="checked"' : '') . ' />';
							echo '<div id="ts-load-toggle-' . $iconfont['setting'] . '" data-toggle="ts-switch-toggle-' . $iconfont['setting'] . '" data-image="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" class="toggle toggle-light ts-load-toggle" style="width: 60px; height: 20px;">';
								echo '<div class="toggle-slide">';
									echo '<div class="toggle-inner">';
										echo '<div class="toggle-on ' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 1 ? 'active' : '') . '">Yes</div>';
										echo '<div class="toggle-blob"></div>';
										echo '<div class="toggle-off ' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 0 ? 'active' : '') . '">No</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
						echo '<label style="font-weight: normal;" class="labelToggleBox" for="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '">Always Load ' . $Icon_Font . '</label>';
					echo '</span>';
				echo '</div>';
			} else {
				if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
					$output = '';
					echo '<hr class="style-six" style="margin-top: 20px; margin-bottom: 20px; float: left; width: 100%;">';
					echo '<div class="ts_vcsc_extend_font_selector ' . $iconfont['type'] . '" data-active="' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? 'true' : 'false') . '" data-icons="' . $iconfont['count'] . '" data-name="' . $iconfont['setting'] . '" data-type="' . $iconfont['type'] . '">';
						echo '<img id="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" data-toggle="ts-switch-toggle-' . $iconfont['setting'] . '" data-load="ts-load-toggle-' . $iconfont['setting'] . '" class="ts_vcsc_check_image' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? " checked" : "") .'" style="" src=' . TS_VCSC_GetResourceURL('images/fonts/font_' . strtolower($iconfont['setting']) . '.jpg') . '>';
						echo '<div class="ts_vcsc_extend_font_summary" style="margin-bottom: 10px;">Created by ' . get_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 'Custom User') . '</div>';		
						echo '<div class="ts-switch-button ts-composer-switch" data-value="' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? 'true' : 'false') . '" data-width="60" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
							echo '<input style="display: none; " type="checkbox" data-load="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '" data-image="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" data-check="ts_vcsc_extend_settings_tinymceIconFont" name="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '" id="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '" class="validate[funcCall[checkIconFontSelect]] toggle-check ts_vcsc_extend_settings_font" data-error="Allowable Icon Fonts Selection" data-order="2" value="1" ' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? ' checked="checked"' : '') . ' />';
							echo '<div id="ts-switch-toggle-' . $iconfont['setting'] . '" data-load="ts-load-toggle-' . $iconfont['setting'] . '" data-image="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" class="toggle toggle-light ts-switch-toggle" style="width: 60px; height: 20px;">';
								echo '<div class="toggle-slide">';
									echo '<div class="toggle-inner">';
										echo '<div class="toggle-on ' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 1 ? 'active' : '') . '">Yes</div>';
										echo '<div class="toggle-blob"></div>';
										echo '<div class="toggle-off ' . (${'ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . ''} == 0 ? 'active' : '') . '">No</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';				
						echo '<label style="font-weight: bold;" class="labelToggleBox" for="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '">' . get_option('ts_vcsc_extend_settings_tinymceCustomName', 'Custom User Font') . ' (' . $iconfont['count'] . ' Icons)</label>';
						echo '<span class="ts_tiny_check_load ts_vcsc_extend_settings_load' . $iconfont['setting'] . '_span" style="width: 100%; display: block; margin-top: 5px; margin-bottom: 10px; margin-left: 20px;">';
							echo '<div class="ts-switch-button ts-composer-switch" data-value="' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 1 ? 'true' : 'false') . '" data-width="60" data-style="compact" data-on="Yes" data-off="No" style="float: left; margin-right: 10px;">';
								echo '<input style="display: none; " type="checkbox" data-font="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '" name="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '" id="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '" class="toggle-check ts_vcsc_extend_settings_load" value="1" ' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 1 ? ' checked="checked"' : '') . ' />';
								echo '<div id="ts-load-toggle-' . $iconfont['setting'] . '" data-toggle="ts-switch-toggle-' . $iconfont['setting'] . '" data-image="ts_vcsc_extend_settings_tinymce' . $iconfont['setting'] . '_image" class="toggle toggle-light ts-load-toggle" style="width: 60px; height: 20px;">';
									echo '<div class="toggle-slide">';
										echo '<div class="toggle-inner">';
											echo '<div class="toggle-on ' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 1 ? 'active' : '') . '">Yes</div>';
											echo '<div class="toggle-blob"></div>';
											echo '<div class="toggle-off ' . (${'ts_vcsc_extend_settings_load' . $iconfont['setting'] . ''} == 0 ? 'active' : '') . '">No</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
							echo '<label style="font-weight: normal;" class="labelToggleBox" for="ts_vcsc_extend_settings_load' . $iconfont['setting'] . '">Always Load ' . get_option('ts_vcsc_extend_settings_tinymceCustomName', 'Custom User Font') . '</label>';
						echo '</span>';
					echo '</div>';
				}
			}
	    };
	?>
    </div>
</div>
