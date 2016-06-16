<?php
	$output = '';
	$TS_VCSC_Border_Type = array(
		__( "Solid Border", "ts_visual_composer_extend" )                  => "solid",
		__( "Dotted Border", "ts_visual_composer_extend" )                 => "dotted",
		__( "Dashed Border", "ts_visual_composer_extend" )                 => "dashed",
		__( "Double Border", "ts_visual_composer_extend" )                 => "double",
		__( "Grouve Border", "ts_visual_composer_extend" )                 => "groove",
		__( "Ridge Border", "ts_visual_composer_extend" )                  => "ridge",
		__( "Inset Border", "ts_visual_composer_extend" )                  => "inset",
		__( "Outset Border", "ts_visual_composer_extend" )                 => "outset"
	);
    $TS_VCSC_Icon_Border_Radius = array(
        __( "None", "ts_visual_composer_extend" )                          => "",
        __( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
        __( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
        __( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
        __( "Full Circle", "ts_visual_composer_extend" )                   => "ts-radius-full"
    );
    $TS_VCSC_Tooltip_Style = array(
        __( "Black", "ts_visual_composer_extend" )                          => "",
        __( "Gray", "ts_visual_composer_extend" )                           => "ts-simptip-style-gray",
        __( "Green", "ts_visual_composer_extend" )                          => "ts-simptip-style-green",
        __( "Blue", "ts_visual_composer_extend" )                           => "ts-simptip-style-blue",
        __( "Red", "ts_visual_composer_extend" )                            => "ts-simptip-style-red",
        __( "Orange", "ts_visual_composer_extend" )                         => "ts-simptip-style-orange",
        __( "Yellow", "ts_visual_composer_extend" )                         => "ts-simptip-style-yellow",
        __( "Purple", "ts_visual_composer_extend" )                         => "ts-simptip-style-purple",
        __( "Pink", "ts_visual_composer_extend" )                           => "ts-simptip-style-pink",
        __( "White", "ts_visual_composer_extend" )                          => "ts-simptip-style-white"
    );
    $TS_VCSC_Tooltip_Position = array(
        __( "Top", "ts_visual_composer_extend" )                            => "ts-simptip-position-top",
        __( "Right", "ts_visual_composer_extend" )                          => "ts-simptip-position-right",
        __( "Bottom", "ts_visual_composer_extend" )                         => "ts-simptip-position-bottom",
        __( "Left", "ts_visual_composer_extend" )                           => "ts-simptip-position-left"
    );
    $TS_VCSC_Link_Target = array(
        __( "Same Window", "ts_visual_composer_extend" )                    => "_parent",
        __( "New Window", "ts_visual_composer_extend" )                     => "_blank"
    );
    $TS_VCSC_Icon_Align = array(
        __( "Center", "ts_visual_composer_extend" )                        	=> "ts-align-center",
        __( "Left", "ts_visual_composer_extend" )                          	=> "ts-align-left",
        __( "Right", "ts_visual_composer_extend" )                         	=> "ts-align-right",
        __( "Float Left", "ts_visual_composer_extend" )                    	=> "ts-align-floatleft",
        __( "Float Right", "ts_visual_composer_extend" )                   	=> "ts-align-floatright"
    );
?>

<div id="ts-settings-generator" style="display: block;">
	
	<a class="button-secondary" style="width: 200px; margin: 20px auto 10px auto; text-align: center;" href="<?php echo $this->settingsLink; ?>" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Back to Plugin Settings</a>
	
    <p>Here you can generate the HTML code and Shortcode for any of the Icon Fonts that are part of Visual Composer Extensions. Embed the shortcode / HTML code into the tinyMCE editor in order to utilize the font icons outside the Visual Composer Elements.</p>

    <hr class='style-six' style='margin-top: 20px;'>

	<div id="ts_tiny_fonts_container" style="display: none;">
		<?php
			// Create Hidden List with all Icons per enabled Font
			foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
				$default = ($iconfont['default'] == "true" ? 1 : 0);
				if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) && ($iconfont['setting'] != "Custom")){
					$output = '';
					$output .= '<ul id="ts-tiny-icons-' . strtolower($iconfont['setting']) . '" data-font="' . $Icon_Font . '" data-group="' . strtolower($iconfont['setting']) . '" class="dropdown iconfontholder">';
						$icon_counter = 0;
						foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
							$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ts-' . $key . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="ts-' . $key . '"><a href="#"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class="ts-' . $key . '"></i>ts-' . $key . '</a></li>';
							$icon_counter = $icon_counter + 1;
						}
					$output .= '</ul>';
					echo $output;
				} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) && ($iconfont['setting'] == "Custom")){
					$output = '';
					$output .= '<ul id="ts-tiny-icons-' . strtolower($iconfont['setting']) . '" data-font="' . $Icon_Font . '" data-group="' . strtolower($iconfont['setting']) . '" class="dropdown iconfontholder">';
						$icon_counter = 0;
						foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
							$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $key . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><a href="#"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class="' . $key . '"></i>' . $key . '</a></li>';
							$icon_counter = $icon_counter + 1;
						}
					$output .= '</ul>';
					echo $output;
				}
			}
			$output = '';
			$output .= '<ul id="ts-tiny-icons-iconsearch" class="dropdown">';
				$output .= '<li class="ts-wrapper-dropdown-search"><input autocomplete="off" size="60" placeholder="' . __( "Type Icon Name to Filter", "ts_visual_composer_extend" ) . '" type="text" class="ts-wrapper-dropdown-filter" value="" name="ts-wrapper-dropdown-filter" /></li>';
			$output .= '</ul>';
			echo $output;
		?>
	</div>
	
	<div id="ts_tiny_tinymce_import_container" name="ts_tiny_tinymce_convert" style="display: none;">
		<div id="ts_tiny_tinymce_import_inner" style="">
			<table id="ts_tiny_tinymce_table_importCODE" class="ts_tiny_form ts_tiny_table ts_tiny_tinymce_table_importCODE" cellspacing="0" style="">
				<tr>
					<td><strong><?php _e( "Insert Shortcode", "ts_visual_composer_extend" ); ?>"</strong></td>
					<td>
						<textarea rows="10" id="ts-tiny-tinymce-importcode-select" name="ts-tiny-tinymce-importcode-select"></textarea>
						<textarea rows="10" id="ts-tiny-tinymce-importcode-holder" name="ts-tiny-tinymce-importcode-holder" style="display: none !important"></textarea>
					</td>
				</tr>
				<tr class="tbl_last">
					<td colspan="2" style="width: 100%;">
						<input style="margin-top: 10px; float: left; margin-right: 20px; width: 200px;" type="button" value="<?php _e( "Import Settings", "ts_visual_composer_extend" ); ?>" name="ts_tiny_import_code" id="ts_tiny_import_code" class="button-secondary" />
					</td>    
				</tr>
			</table>
		</div>
	</div>
	
	<div id="ts_tiny_generator_container">
		<div id="ts_tiny_generator_container_inner" style="">
			<div style="width: 100%; display: inline-block; margin-bottom: 20px; border-bottom: 1px solid #DDDDDD; padding-bottom: 20px;">
				<div style="float: left; margin-right: 20px;">
					<img src="<?php echo TS_VCSC_GetResourceURL('images/other/icon_fonts.png'); ?>" style="width: 200px; height: 71px;">
				</div>
			</div>
			
			<form id="ts_tiny_tinymce_form" name="ts_tiny_tinymce_form" autocomplete="off" style=""/>
				<table id="ts_tiny_tinymce_table_icon" class="ts_tiny_form ts_tiny_table ts_tiny_tinymce_table_icon" cellspacing="0" style="">
					<?php
						if (get_option('ts_vcsc_extend_settings_demo', 1) == 0) {
							$output = '';
							$output .= '<tr>';
								$output .= '<td><strong>' . __( "Code Importer", "ts_visual_composer_extend" ) . ':</strong></td>';
								$output .= '<td>';
									$output .= '<div id="ts-tiny-tinymce-importer" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-importer-check" style="width: 80px;" data-on="' . __( "Show", "ts_visual_composer_extend" ) . '" data-off="' . __( "Hide", "ts_visual_composer_extend" ) . '">';
										$output .= '<div class="toggle-slide">';
											$output .= '<div class="toggle-inner">';
												$output .= '<div class="toggle-on">' . __( "Show", "ts_visual_composer_extend" ) . '</div>';
												$output .= '<div class="toggle-blob"></div>';
												$output .= '<div class="toggle-off active">' . __( "Hide", "ts_visual_composer_extend" ) . '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
									$output .= '<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-importer-check" class="ts-tiny-tinymce-importer-check" style="display: none;">';
								$output .= '</td>';
							$output .= '</tr>';
							$output .= '<tr>';
								$output .= '<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>';
							$output .= '</tr>';
							echo $output;
						}
					?>
					<tr <?php echo ($this->TS_VCSC_Active_Icon_Fonts == 1 ? 'style="display: none; height: 0px; line-height: 0px; margin: 0px; padding: 0px;"' : ''); ?>>
						<td <?php echo ((get_option('ts_vcsc_extend_settings_demo', 1) == 0) ? 'style="padding-top: 20px;"' : ''); ?>><strong><?php _e( "Font:", "ts_visual_composer_extend" ); ?></strong></td>
						<td <?php echo ((get_option('ts_vcsc_extend_settings_demo', 1) == 0) ? 'style="padding-top: 20px;"' : ''); ?>>
							<?php
								$output = '';
								$activeIcons = 0;
								$output .= '<div id="ts-tiny-tinymce-font" class="ts-wrapper-dropdown" data-type="font">';
									$output .= '<input name="ts-tiny-tinymce-font-select" id="ts-tiny-tinymce-font-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-font-select" type="hidden" value=""/>';
									// Output first of all enabled Fonts
									$activeFonts = 0;
									foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
										$default = ($iconfont['default'] == "true" ? 1 : 0);
										if (get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) {
											$activeFonts++;
											if ($activeFonts == 1) {
												$output .= '<span class="ts-wrapper-dropdown-name">' . $Icon_Font . ' (' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . ' Icons)</span>';
											}
										}
									}
									// Create List of all enabled Fonts for Selection Box
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-fonts-list" class="dropdown">';
										$activeFonts = 0;
										foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
											$default = ($iconfont['default'] == "true" ? 1 : 0);
											if (get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) {
												$activeFonts++;
												$activeIcons = $activeIcons + $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'};
												$output .= '<li class="ts-wrapper-dropdown-item' . ($activeFonts == 1 ? " active" : "") . '" title="' . $Icon_Font . '" rel="' . strtolower($iconfont['setting']) . '"><a href="#">' . $Icon_Font . ' (' . $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'} . ' Icons)</a></li>';
											}
										}
										if ($this->TS_VCSC_Active_Icon_Fonts > 1) {
											$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "All Fonts", "ts_visual_composer_extend" ) . '" rel="allfonts"><a href="#">' . __( "All Fonts", "ts_visual_composer_extend" ) . ' (' . $activeIcons . ' ' . __( "Icons", "ts_visual_composer_extend" ) . ')</a></li>';
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Icon:", "ts_visual_composer_extend" ); ?></strong></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-icon" class="ts-wrapper-dropdown" data-type="icon">';
									$output .= '<input name="ts-tiny-tinymce-icon-select" id="ts-tiny-tinymce-icon-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-icon-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name" data-holder="' . __( "Select Icon", "ts_visual_composer_extend" ) . '">' . __( "Select Icon", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-icons-list" class="dropdown">';
										$output .= '<li class="ts-wrapper-dropdown-search"><input autocomplete="off" size="60" placeholder="' . __( "Type Icon Name to Filter", "ts_visual_composer_extend" ) . '" type="text" class="ts-wrapper-dropdown-filter" value="" name="ts-wrapper-dropdown-filter" /></li>';
										foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
											$default = ($iconfont['default'] == "true" ? 1 : 0);
											if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default) == 1) && ($iconfont['setting'] != "Custom")){
												$icon_counter = 0;
												foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
													$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ts-' . $key . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="ts-' . $key . '"><a href="#"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class="ts-' . $key . '"></i>ts-' . $key . '</a></li>';
													$icon_counter = $icon_counter + 1;
												}
												break;
											} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], 0) == 1) && ($iconfont['setting'] == "Custom")){
												$icon_counter = 0;
												foreach ($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''} as $key => $option ) {
													$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $key . '" data-font="' . strtolower($iconfont['setting']) . '" data-count="' . $icon_counter . '" rel="' . $key . '"><a href="#"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class="' . $key . '"></i>' . $key . '</a></li>';
													$icon_counter = $icon_counter + 1;
												}
												break;
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						  </select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Size", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-size-select" name="ts-tiny-tinymce-size-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-size" type="text" value="16"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-size" class="ts-nouislider-input" name="ts-nouislider-icon-size" data-value="16" data-min="16" data-max="400" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Color", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconcolor-frame">
								<input id="ts-tiny-tinymce-iconcolor-select" name="ts-tiny-tinymce-iconcolor-select" type="text" value="#000000" size="20">
								<input id="ts-tiny-tinymce-iconcolor" name="ts-tiny-tinymce-iconcolor" style="width: 250px; display: none;" type="text" value="#000000" size="100">
							</div>
						</td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Animations", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconanimation" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconanimation-check" style="width: 80px;" data-on="<?php _e( "Default", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "Hover", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on active"><?php _e( "Default", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off"><?php _e( "Hover", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconanimation-check" class="ts-tiny-tinymce-iconanimation-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-icondefault-true">
						<td style="padding-top: 20px;"><strong><?php _e( "Default Animation", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-animationdefault" class="ts-wrapper-dropdown" data-type="animation">';
									$output .= '<input name="ts-tiny-tinymce-animationdefault-select" id="ts-tiny-tinymce-animationdefault-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-animationdefault-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "None", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-animationdefault-list" class="dropdown">';
										$icon_counter = 1;
										$output .= '<li class="ts-wrapper-dropdown-item active" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : " data-count="' . $icon_counter . '" rel=""><a href="#">' . __( "None", "ts_visual_composer_extend" ) . '</a></li>';
										foreach ($this->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
											if ($Animation_Class) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ts-infinite-css-' . $animations['class'] . '" data-count="' . $icon_counter . '" rel="ts-infinite-css-' . $animations['class'] . '"><a href="#">' . $Animation_Class . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconhover-true" style="display: none;">
						<td style="padding-top: 20px;"><strong><?php _e( "Hover Animation", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-animationhover" class="ts-wrapper-dropdown" data-type="animation">';
									$output .= '<input name="ts-tiny-tinymce-animationhover-select" id="ts-tiny-tinymce-animationhover-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-animationhover-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "None", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-animationhover-list" class="dropdown">';
										$icon_counter = 1;
										$output .= '<li class="ts-wrapper-dropdown-item active" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : " data-count="' . $icon_counter . '" rel=""><a href="#">' . __( "None", "ts_visual_composer_extend" ) . '</a></li>';
										foreach ($this->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
											if ($Animation_Class) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ts-hover-css-' . $animations['class'] . '" data-count="' . $icon_counter . '" rel="ts-hover-css-' . $animations['class'] . '"><a href="#">' . $Animation_Class . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td colspan="2"><div style="text-align: justify; margin: 20px 0px 10px 0px;"><?php _e( "By nature, viewport animations can not be previewed with this Generator. For test purposes, temporarily apply a Default Animation to the icon to see how the effect will look like.", "ts_visual_composer_extend" ); ?></div></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Viewport Animation", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-animationview" class="ts-wrapper-dropdown" data-type="animation">';
									$output .= '<input name="ts-tiny-tinymce-animationview-select" id="ts-tiny-tinymce-animationview-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-animationview-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "None", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-animationview-list" class="dropdown">';
										$icon_counter = 1;
										$output .= '<li class="ts-wrapper-dropdown-item active" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : " data-count="' . $icon_counter . '" rel=""><a href="#">' . __( "None", "ts_visual_composer_extend" ) . '</a></li>';
										foreach ($this->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
											if ($Animation_Class) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ts-viewport-css-' . $animations['class'] . '" data-count="' . $icon_counter . '" rel="ts-viewport-css-' . $animations['class'] . '"><a href="#">' . $Animation_Class . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-animationdelay-true">
						<td style="padding-top: 20px;"><strong><?php _e( "Animation Delay", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-delay-select" name="ts-tiny-tinymce-delay-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-animation-delay" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">ms</span>
								<div id="ts-tiny-animation-delay" class="ts-nouislider-input" name="ts-nouislider-icon-size" data-value="0" data-min="0" data-max="10000" data-step="100" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Background Color", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconback" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconback-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off active"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconback-check" class="ts-tiny-tinymce-iconback-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconback-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Color", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-backcolor-frame">
								<input id="ts-tiny-tinymce-backcolor-select" name="ts-tiny-tinymce-backcolor-select" type="text" value="" size="20">
								<input id="ts-tiny-tinymce-backcolor" name="ts-tiny-tinymce-backcolor" style="width: 250px; display: none;" type="text" value="" size="100">
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconback-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Opacity", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-opacity-select" name="ts-tiny-tinymce-opacity-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-opacity" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">%</span>
								<div id="ts-tiny-tinymce-opacity" class="ts-nouislider-input" name="ts-nouislider-icon-opacity" data-value="100" data-min="0" data-max="100" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Hover Colors", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconhoverchange" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconhoverchange-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off active"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconhoverchange-check" class="ts-tiny-tinymce-iconhoverchange-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconhoverchange-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Icon Color", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconhovercolor-frame">
								<input id="ts-tiny-tinymce-iconhovercolor-select" name="ts-tiny-tinymce-iconhovercolor-select" type="text" value="#000000" size="20">
								<input id="ts-tiny-tinymce-iconhovercolor" name="ts-tiny-tinymce-iconhovercolor" style="width: 250px; display: none;" type="text" value="#000000" size="100">
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconhoverchange-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Background Color", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-backhovercolor-frame">
								<input id="ts-tiny-tinymce-backhovercolor-select" name="ts-tiny-tinymce-backhovercolor-select" type="text" value="" size="20">
								<input id="ts-tiny-tinymce-backhovercolor" name="ts-tiny-tinymce-backhovercolor" style="width: 250px; display: none;" type="text" value="" size="100">
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconhoverchange-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Opacity", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-opacityhover-select" name="ts-tiny-tinymce-opacityhover-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-opacityhover" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">%</span>
								<div id="ts-tiny-tinymce-opacityhover" class="ts-nouislider-input" name="ts-nouislider-icon-opacityhover" data-value="100" data-min="0" data-max="100" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Icon Border", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconborder" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconborder-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off active"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconborder-check" class="ts-tiny-tinymce-iconborder-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconborder-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Border Type", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-bordertype" class="ts-wrapper-dropdown" data-type="border">';
									$output .= '<input name="ts-tiny-tinymce-bordertype-select" id="ts-tiny-tinymce-bordertype-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-bordertype-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "Solid Border", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-bordertype-list" class="dropdown">';
										$icon_counter = 0;
										foreach ($TS_VCSC_Border_Type as $key => $option ) {
											if ($option) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item' . ($icon_counter == 1 ? " active" : "") . '" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $option . '" data-count="' . $icon_counter . '" rel="' . $option . '"><a href="#">' . $key . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconborder-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Border Radius", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-borderradius" class="ts-wrapper-dropdown" data-type="border">';
									$output .= '<input name="ts-tiny-tinymce-borderradius-select" id="ts-tiny-tinymce-borderradius-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-borderradius-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "None", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-borderradius-list" class="dropdown">';
										$icon_counter = 0;
										foreach ($TS_VCSC_Icon_Border_Radius as $key => $option ) {
											if ($key) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item' . ($icon_counter == 1 ? " active" : "") . '" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $option . '" data-count="' . $icon_counter . '" rel="' . $option . '"><a href="#">' . $key . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconborder-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Border Color", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-bordercolor-frame">
								<input id="ts-tiny-tinymce-bordercolor-select" name="ts-tiny-tinymce-bordercolor-select" type="text" value="#cccccc" size="20">
								<input id="ts-tiny-tinymce-bordercolor" name="ts-tiny-tinymce-bordercolor" style="width: 250px; display: none;" type="text" value="204, 204, 204" size="100">
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconborder-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Border Width", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-borderwidth-select" name="ts-tiny-tinymce-borderwidth-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-borderwidth" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-borderwidth" class="ts-nouislider-input" name="ts-nouislider-icon-borderwidth" data-value="1" data-min="1" data-max="10" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Paddings", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconpadding" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconpadding-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off active"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconpadding-check" class="ts-tiny-tinymce-iconpadding-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconpadding-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Top", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-paddingtop-select" name="ts-tiny-tinymce-paddingtop-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-paddingtop" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-paddingtop" class="ts-nouislider-input ts-tiny-tinymce-paddinggroup" name="ts-nouislider-icon-paddingtop" data-side="top" data-value="0" data-min="0" data-max="100" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconpadding-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Bottom", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-paddingbottom-select" name="ts-tiny-tinymce-paddingbottom-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-paddingbottom" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-paddingbottom" class="ts-nouislider-input ts-tiny-tinymce-paddinggroup" name="ts-nouislider-icon-paddingbottom" data-side="bottom" data-value="0" data-min="0" data-max="100" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconpadding-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Left", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-paddingleft-select" name="ts-tiny-tinymce-paddingleft-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-paddingleft" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-paddingleft" class="ts-nouislider-input ts-tiny-tinymce-paddinggroup" name="ts-nouislider-icon-paddingleft" data-side="left" data-value="0" data-min="0" data-max="100" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconpadding-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Right", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-paddingright-select" name="ts-tiny-tinymce-paddingright-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-paddingright" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-paddingright" class="ts-nouislider-input ts-tiny-tinymce-paddinggroup" name="ts-nouislider-icon-paddingright" data-side="right" data-value="0" data-min="0" data-max="100" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Margins", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconmargin" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconmargin-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off active"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconmargin-check" class="ts-tiny-tinymce-iconmargin-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconmargin-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Top", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-margintop-select" name="ts-tiny-tinymce-margintop-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-margintop" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-margintop" class="ts-nouislider-input ts-tiny-tinymce-margingroup" name="ts-nouislider-icon-margintop" data-side="top" data-value="5" data-min="-50" data-max="250" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconmargin-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Bottom", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-marginbottom-select" name="ts-tiny-tinymce-marginbottom-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-margingbottom" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-marginbottom" class="ts-nouislider-input ts-tiny-tinymce-margingroup" name="ts-nouislider-icon-marginbottom" data-side="bottom" data-value="5" data-min="-50" data-max="250" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconmargin-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Left", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-marginleft-select" name="ts-tiny-tinymce-marginleft-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-marginleft" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-marginleft" class="ts-nouislider-input ts-tiny-tinymce-margingroup" name="ts-nouislider-icon-marginleft" data-side="left" data-value="5" data-min="-50" data-max="250" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconmargin-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Right", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<div class="ts-nouislider-input-slider">
								<input style="width: 50px; float: left; margin-left: 0px; margin-right: 10px;" id="ts-tiny-tinymce-marginright-select" name="ts-tiny-tinymce-marginright-select" class="ts-nouislider-serial nouislider-input-selector ts-nouislider-icon-marginright" type="text" value="0"/>
								<span style="float: left; margin-right: 20px; margin-top: 5px;" class="unit">px</span>
								<div id="ts-tiny-tinymce-marginright" class="ts-nouislider-input ts-tiny-tinymce-margingroup" name="ts-nouislider-icon-marginright" data-side="right" data-value="5" data-min="-50" data-max="250" data-step="1" style=""></div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Link", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconlink" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconlink-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off active"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconlink-check" class="ts-tiny-tinymce-iconlink-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconlink-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "URL", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<input name="ts-tiny-tinymce-linkurl-select" id="ts-tiny-tinymce-linkurl-select" class="ts-tiny-tinymce-linkurl-select" type="text" value=""/>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconlink-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Link Target", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-linktarget" class="ts-wrapper-dropdown" data-type="link">';
									$output .= '<input name="ts-tiny-tinymce-linktarget-select" id="ts-tiny-tinymce-linktarget-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-linktarget-select" type="hidden" value="_parent"/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "Same Window", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-linktarget-list" class="dropdown">';
										$icon_counter = 0;
										foreach ($TS_VCSC_Link_Target as $key => $option ) {
											if ($key) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item' . ($icon_counter == 1 ? " active" : "") . '" title="Class Name : ' . $option . '" data-count="' . $icon_counter . '" rel="' . $option . '"><a href="#">' . $key . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Show Icon as Inline", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-iconinline" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-iconinline-check" style="width: 70px;" data-on="<?php _e( "Yes", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "No", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on active"><?php _e( "Yes", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off"><?php _e( "No", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-iconinline-check" class="ts-tiny-tinymce-iconinline-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-iconinline-false">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Align", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-iconalign" class="ts-wrapper-dropdown" data-type="align">';
									$output .= '<input name="ts-tiny-tinymce-iconalign-select" id="ts-tiny-tinymce-iconalign-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-iconalign-select" type="hidden" value="ts-align-center"/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "Center", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-iconalign-list" class="dropdown">';
										$icon_counter = 0;
										foreach ($TS_VCSC_Icon_Align as $key => $option ) {
											if ($key) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item' . ($icon_counter == 1 ? " active" : "") . '" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $option . '" data-count="' . $icon_counter . '" rel="' . $option . '"><a href="#">' . $key . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Icon Tooltip", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<div id="ts-tiny-tinymce-icontooltip" class="toggle toggle-light" data-checkbox="ts-tiny-tinymce-icontooltip-check" style="width: 80px;" data-on="<?php _e( "Title", "ts_visual_composer_extend" ); ?>" data-off="<?php _e( "CSS3", "ts_visual_composer_extend" ); ?>">
								<div class="toggle-slide">
									<div class="toggle-inner">
										<div class="toggle-on active"><?php _e( "Title", "ts_visual_composer_extend" ); ?></div>
										<div class="toggle-blob"></div>
										<div class="toggle-off"><?php _e( "CSS3", "ts_visual_composer_extend" ); ?></div>
									</div>
								</div>
							</div>
							<input type="checkbox" disabled="disabled" id="ts-tiny-tinymce-icontooltip-check" class="ts-tiny-tinymce-icontooltip-check" style="display: none;">
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-icontooltip-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Style", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-tooltipstyle" class="ts-wrapper-dropdown" data-type="align">';
									$output .= '<input name="ts-tiny-tinymce-tooltipstyle-select" id="ts-tiny-tinymce-tooltipstyle-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-tooltipstyle-select" type="hidden" value=""/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "Black", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-tooltipstyle-list" class="dropdown">';
										$icon_counter = 0;
										foreach ($TS_VCSC_Tooltip_Style as $key => $option ) {
											if ($key) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item' . ($icon_counter == 1 ? " active" : "") . '" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $option . '" data-count="' . $icon_counter . '" rel="' . $option . '"><a href="#">' . $key . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr class="ts-tiny-tinymce-icontooltip-true">
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Position", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<?php
								$output = '';
								$output .= '<div id="ts-tiny-tinymce-tooltipposition" class="ts-wrapper-dropdown" data-type="align">';
									$output .= '<input name="ts-tiny-tinymce-tooltipposition-select" id="ts-tiny-tinymce-tooltipposition-select" class="ts-wrapper-dropdown-value ts-tiny-tinymce-tooltipposition-select" type="hidden" value="ts-simptip-position-top"/>';
									$output .= '<span class="ts-wrapper-dropdown-name">' . __( "Top", "ts_visual_composer_extend" ) . '</span>';
									$output .= '<span class="ts-wrapper-dropdown-select"><i style="font-size: 24px; width: 24px; height: 24px; line-height: 24px;" class=""></i></span>';
									$output .= '<ul id="ts-tiny-tinymce-tooltipposition-list" class="dropdown">';
										$icon_counter = 0;
										foreach ($TS_VCSC_Tooltip_Position as $key => $option ) {
											if ($key) {
												$icon_counter = $icon_counter + 1;
												$output .= '<li class="ts-wrapper-dropdown-item' . ($icon_counter == 1 ? " active" : "") . '" title="' . __( "Class Name", "ts_visual_composer_extend" ) . ' : ' . $option . '" data-count="' . $icon_counter . '" rel="' . $option . '"><a href="#">' . $key . '</a></li>';
											}
										}
									$output .= '</ul>';
								$output .= '</div>';
								echo $output;
							?>
						</td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><span style="margin-left: 20px;"><?php _e( "Content", "ts_visual_composer_extend" ); ?>:</span></td>
						<td style="padding-top: 20px;">
							<textarea rows="4" id="ts-tiny-tinymce-tooltipcontent" name="ts-tiny-tinymce-tooltipcontent"></textarea>
							<span style="width: 100%; float: left; font-size: 10px;"><?php _e( "Please do NOT use any quotation marks in your tooltip text!", "ts_visual_composer_extend" ); ?></span>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "ID", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<input name="ts-tiny-tinymce-iconid-select" id="ts-tiny-tinymce-iconid-select" class="ts-tiny-tinymce-iconid-select" type="text" value=""/>
						</td>
					</tr>
					<tr>
						<td style="padding-top: 20px;"><strong><?php _e( "Class Name", "ts_visual_composer_extend" ); ?>:</strong></td>
						<td style="padding-top: 20px;">
							<input name="ts-tiny-tinymce-iconclass-select" id="ts-tiny-tinymce-iconclass-select" class="ts-tiny-tinymce-iconclass-select" type="text" value=""/>
						</td>
					</tr>
					<tr class="tbl_last">
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
				</table>
				
				<table id="ts_tiny_tinymce_table_preview" class="ts_tiny_form ts_tiny_table ts_tiny_tinymce_table_preview" cellspacing="0" style="">
					<tr>
						<td colspan="2" style="width: 100%;">
							<div style="margin: 20px auto; text-align: center; font-weight: bold;"><?php _e( "Icon Preview", "ts_visual_composer_extend" ); ?></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width: 100%;">
							<span class="ts-tiny-tinymce-inline-sample"><?php _e( "This is some sample text ...", "ts_visual_composer_extend" ); ?> </span><span id="ts_tiny_tinymce_icon_preview_holder" class="ts-align-inline"><i id="ts_tiny_tinymce_icon_preview" class="ts-font-icon"></i></span><span class="ts-tiny-tinymce-inline-sample"> <?php _e( "... to show the icon placement.", "ts_visual_composer_extend" ); ?></span>
						</td>
					</tr>
					<tr class="tbl_last">
						<td colspan="2" style="width: 100%; padding-top: 15px; border-bottom: 1px solid #DDDDDD;"></td>
					</tr>
				</table>
			</form>
		</div>

		<?php
			$shortcodeMessage 	= base64_encode('<p style="font-weight: bold;">' . __( "Shortcode", "ts_visual_composer_extend" ) . ':</p>' . __( "You need to select an icon first!", "ts_visual_composer_extend" ));
			$htmlcodeMessage 	= base64_encode('<p style="font-weight: bold;">' . __( "HTML Code", "ts_visual_composer_extend" ) . ':</p>' . __( "You need to select an icon first!", "ts_visual_composer_extend" ));
		?>

		<div id="ts_tiny_tinymce_shortcode" data-code="<?php echo $shortcodeMessage; ?>"><p style="font-weight: bold;"><?php _e( "Shortcode", "ts_visual_composer_extend" ); ?>:</p><?php _e( "You need to select an icon first!", "ts_visual_composer_extend" ); ?></div>
		<div id="ts_tiny_tinymce_shortcode_insert" style="display: none !important"></div>
		<textarea rows="10" id="ts_tiny_tinymce_shortcode_copy" name="ts_tiny_tinymce_shortcode_copy" style="display: none !important; width: 100%;"></textarea>
		<input style="margin-top: 10px; margin-right: 20px; width: 200px; display: inline-block;" data-clipboard-target="ts_tiny_tinymce_shortcode_copy" 	type="button" value="<?php _e( "Copy Shortcode", "ts_visual_composer_extend" ); ?>" 	name="ts_tiny_clipboard_copy_shortcode" 	id="ts_tiny_clipboard_copy_shortcode" 	class="button-primary" 		data-error="<?php _e( "No Icon Shortcode has been generated yet.", "ts_visual_composer_extend" ); ?>"		data-success="<?php _e( "Icon Shortcode has been copied to Clipboard.", "ts_visual_composer_extend" ); ?>"/>
		
		<div id="ts_tiny_tinymce_html" data-code="<?php echo $htmlcodeMessage; ?>"><p style="font-weight: bold;"><?php _e( "HTML Code", "ts_visual_composer_extend" ); ?>:</p><?php _e( "You need to select an icon first!", "ts_visual_composer_extend" ); ?></div>
		<div id="ts_tiny_tinymce_html_insert" style="display: none !important"></div>
		<textarea rows="10" id="ts_tiny_tinymce_html_copy" name="ts_tiny_tinymce_html_copy" style="display: none !important; width: 100%;"></textarea>
		<input style="margin-top: 10px; margin-right: 20px; width: 200px; display: inline-block;" data-clipboard-target="ts_tiny_tinymce_html_copy" 		type="button" value="<?php _e( "Copy HTML Code", "ts_visual_composer_extend" ); ?>" 	name="ts_tiny_clipboard_copy_html" 			id="ts_tiny_clipboard_copy_html" 		class="button-secondary" 	data-error="<?php _e( "No Icon HTML Code has been generated yet.", "ts_visual_composer_extend" ); ?>"		data-success="<?php _e( "Icon HTML Code has been copied to Clipboard.", "ts_visual_composer_extend" ); ?>"/>
	</div>
	
    <?php
		echo '<script type="text/javascript">';
			echo 'var pathVCSCMCEIconImage  	= "' . TS_VCSC_GetResourceURL('images/other/ts_vcsc_tinymce.png') . '";';
			echo 'var pathCopyToClipboardSWF 	= "' . TS_VCSC_GetResourceURL('js/ZeroClipboard.swf') . '";';
			if (get_option('ts_vcsc_extend_settings_useTinyMCEMedia', 1) == 1) {
				echo 'var TS_VCSC_tinyMCE_Media = true;';
			} else {
				echo 'var TS_VCSC_tinyMCE_Media = false;';
			}
		echo '</script>';
	?>
</div>
