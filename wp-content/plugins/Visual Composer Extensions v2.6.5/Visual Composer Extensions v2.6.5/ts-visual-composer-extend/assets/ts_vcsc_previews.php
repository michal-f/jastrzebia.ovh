<?php
	$output = '';
?>

<div id="ts-settings-generator" style="display: block;">
	
	<a class="button-secondary" style="width: 200px; margin: 20px auto 10px auto; text-align: center;" href="<?php echo $this->settingsLink; ?>" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Back to Plugin Settings</a>
	
    <p>Here you can generate a preview of all items included per enabled Font</p>

    <hr class='style-six' style='margin-top: 20px;'>

	<div id="ts_vcsc_fonts_container" style="display: none;">
		<?php
			// Create Hidden List with all Icons per enabled Font
			foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
				$default = ($iconfont['default'] == "true" ? 1 : 0);
				if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) && ($iconfont['setting'] != "Custom")){
					$output = '';
					$output .= '<div id="ts-vcsc-icons-' . strtolower($iconfont['setting']) . '" data-font="' . $Icon_Font . '" class="">';
						$icon_counter = 0;
						foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
							$output .= '<div class="ts-vcsc-icon-preview" data-name="ts-' . $key . '" data-code="' . $option . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i class="ts-' . $key . '"></i></span><span class="ts-vcsc-icon-preview-name">' . str_replace((strtolower($iconfont['setting']) . "-"), "", $key) . '</span></div>';
							$icon_counter = $icon_counter + 1;
						}
					$output .= '</div>';
					echo $output;
				} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) && ($iconfont['setting'] == "Custom")){
					$output = '';
					$output .= '<div id="ts-vcsc-icons-' . strtolower($iconfont['setting']) . '" data-font="' . $Icon_Font . '" class="">';
						$icon_counter = 0;
						foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
							$output .= '<div class="ts-vcsc-icon-preview" data-name="' . $key . '" data-code="' . $option . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i class="' . $key . '"></i></span><span class="ts-vcsc-icon-preview-name">' . str_replace((strtolower($iconfont['setting']) . "-"), "", $key) . '</span></div>';
							$icon_counter = $icon_counter + 1;
						}
					$output .= '</div>';
					echo $output;
				}
			}
		?>
	</div>
	
	<div id="ts_vcsc_preview_container">
		<div style="width: 100%; display: inline-block; margin-bottom: 20px; border-bottom: 1px solid #DDDDDD; padding-bottom: 20px;">
			<div style="float: left; margin-right: 20px;">
				<img src="<?php echo TS_VCSC_GetResourceURL('images/other/icon_fonts.png'); ?>" style="width: 200px; height: 71px;">
			</div>
		
			<div style="float: left; width: 100%;">
				<button style="height: 28px; <?php echo ($this->TS_VCSC_Active_Icon_Fonts > 1 ? "display: block;" : "display: none;"); ?>" type="button" value="Dropdown" data-dropdown="#ts-dropdown-fonts" class="dropDownFont button-secondary">Switch Icon Font</button>
				<div id="ts-dropdown-fonts" class="ts-dropdown ts-dropdown-anchor-left ts-dropdown-tip ts-dropdown-relative">
					<ul class="ts-dropdown-menu">
						<?php
							$activeFonts = 0;
							foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
								$default = ($iconfont['default'] == "true" ? 1 : 0);
								if (get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) {
									if ($iconfont['setting'] != "Custom") {
										$output = '';
										$activeFonts++;
										$output .= '<li class="ts-font-dropdown-item' . ($activeFonts == 1 ? " active" : "") . '" data-name="' . $Icon_Font . '" data-author="' . $iconfont['author'] . '" data-count="' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . '" data-code="' . strtolower($iconfont['setting']) . '" title="' . $Icon_Font . '"><a href="#">' . $Icon_Font . ' (' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . ' Icons)</a></li>';
										if ($activeFonts < $this->TS_VCSC_Active_Icon_Fonts) {
											$output .= '<li class="ts-dropdown-divider"></li>';
										}
									} else {
										$output = '';
										$activeFonts++;
										$output .= '<li class="ts-font-dropdown-item' . ($activeFonts == 1 ? " active" : "") . '" data-name="' . get_option('ts_vcsc_extend_settings_tinymceCustomName', 'Custom User Font') . ' (Upload)" data-author="' . get_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 'Custom User') . ' (Upload)" data-count="' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . '" data-code="' . strtolower($iconfont['setting']) . '" title="' . $Icon_Font . '"><a href="#">' . $Icon_Font . ' (' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . ' Icons)</a></li>';
										if ($activeFonts < $this->TS_VCSC_Active_Icon_Fonts) {
											$output .= '<li class="ts-dropdown-divider"></li>';
										}
									}
									echo $output;
								}
							}
						?>
					</ul>
				</div>
			</div>
			
			<div id="ts-settings-summary" style="display: none;" data-extended="<?php echo get_option('ts_vcsc_extend_settings_extended', 0); ?>" data-summary="<?php echo get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix'); ?>"><?php echo get_option('ts_vcsc_extend_settings_licenseInfo', ''); ?></div>

			<div id="ts-vcsc-extend-preview-syntax" style="width: 100%; display: inline-block; float: left; margin-top: 20px;">
				Click on an icon to view the full class name for that icon.
			</div>
			
			<div style="width: 100%; display: inline-block; float: left; margin-top: 20px;"><span style="float: left;">Icon Class Name:</span><span id="ts-vcsc-extend-preview-code">...</span></div>
		</div>


		<?php
			$output = '';
			$output .= '<div id="ts-vcsc-extend-preview" class="">';
				foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
					$default = ($iconfont['default'] == "true" ? 1 : 0);
					if (get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) {
						if ($iconfont['setting'] != "Custom") {
							$output .= '<div id="ts-vcsc-extend-preview-name" class="">Font Name: ' . $Icon_Font . '</div>';
							$output .= '<div id="ts-vcsc-extend-preview-author" class="">Font Author: ' . $iconfont['author'] . '</div>';
							$output .= '<div id="ts-vcsc-extend-preview-count" class="">Icon Count: ' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . '</div>';
							break;
						} else {
							$output .= '<div id="ts-vcsc-extend-preview-name" class="">Font Name: ' . get_option('ts_vcsc_extend_settings_tinymceCustomName', 'Custom User Font') . ' (Upload)</div>';
							$output .= '<div id="ts-vcsc-extend-preview-author" class="">Font Author: ' . get_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 'Custom User') . ' (Upload)</div>';
							$output .= '<div id="ts-vcsc-extend-preview-count" class="">Icon Count: ' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . '</div>';
							break;
						}
					}
				}
				$output .= '<div id="ts-vcsc-extend-preview-divider" class=""></div>';
				$output .= '<div id="ts-vcsc-extend-preview-list" class="">';
					foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
						$default = ($iconfont['default'] == "true" ? 1 : 0);
						if (get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) {
							if ($iconfont['setting'] != "Custom") {
								$icon_counter = 0;
								foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
									$output .= '<div class="ts-vcsc-icon-preview" data-name="ts-' . $key . '" data-code="' . $option . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i class="ts-' . $key . '"></i></span><span class="ts-vcsc-icon-preview-name">' . str_replace((strtolower($iconfont['setting']) . "-"), "", $key) . '</span></div>';
									$icon_counter = $icon_counter + 1;
								}
								break;
							} else {
								$icon_counter = 0;
								foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
									$output .= '<div class="ts-vcsc-icon-preview" data-name="' . $key . '" data-code="' . $option . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><span class="ts-vcsc-icon-preview-icon"><i class="' . $key . '"></i></span><span class="ts-vcsc-icon-preview-name">' . str_replace((strtolower($iconfont['setting']) . "-"), "", $key) . '</span></div>';
									$icon_counter = $icon_counter + 1;
								}
								break;
							}
						}
					}
				$output .= '</div>';
			$output .= '</div>';
			echo $output;
		?>
	</div>
</div>
