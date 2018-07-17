<?php
/*
 * Render the plugin's settings admin panel form
 * Version: 2.23
 * Author: greenline
 * Profile: http://codecanyon.net/user/greenline
 */
function rsmaps_admin() {
    ?>
    <div class="wrap">

    <!-- Beginning of the Responsive Styled Gooogle Maps helper form -->
    <form method="post" action="">

          <script type="text/javascript">
          // On page load, initialize the map preview and the color picker.
           var pluginurl = "<?php echo plugins_url(); ?>";
          jQuery(document).ready(function($) {
              updateMap(pluginurl);
              jQuery('#mapcolor').colorPicker( { onColorChange : function(id, newValue) { 
                  jQuery('#newcolor').val(newValue);
                  updateMap(pluginurl);
              } } );
              showHideColorPicker();
          });
          </script>
      
            <!-- Table structure containing shortcode parameters -->
            <table border="0" cellpadding="5" id="rsmaps">
                <!-- Plugin title -->
                <tr>
                    <td scope="row" valign="top">
                    </td>
                    <td valign="top">
                        <h2><?php echo __('Responsive Styled Google Maps - Helper ', 'res_map') ?></h2>
                    </td>
                </tr>
                
                <!-- Address, the map and the shortcode -->
                <tr>
                    <td align="left" valign="top"><?php echo __('Address') ?></td>
                    <td valign="top">
                        <textarea name="address" id="address" rows="3" cols="70" type='textarea' onblur="updateMap(pluginurl)">Yeronga QLD 4104, Australia</textarea><br>
                        <span class="info"><?php echo __('For multiple markers use: address1 | address2 | address3  OR lat1,long1 | lat2,long2 | lat3,long3') ?></span>
                    </td>
                    <td rowspan="15" valign="top" valign="top" width="600px">
                        <div id="responsive_map" class="responsive-map" style="height:500px;width:600px;"></div><br>
                        <!-- Update map button -->
                        <a href="javascript: updateMap(pluginurl);" class="button button-primary"><?php echo __('REFRESH THE MAP') ?></a>
                        <a href="https://www.google.com/maps" class="button button-primary" target="_blank"><?php echo __('GOOGLE MAPS HOMEPAGE') ?></a>
                        <a href="http://yava.ro/mapgenerator" class="button button-primary" target="_blank"><?php echo __('LATITUDE / LONGITUDE FINDER TOOL') ?> </a>
                        <br><div class="preheader"><?php echo __('COPY-PASTE IN YOUR POST / PAGE /WIDGET THIS SHORTCODE:') ?></div>
                        <pre id="shortcode" onClick="selectText('shortcode')"></pre>
                        <hr>
                    </td>
                </tr>

                <!-- Marker Description -->
                <tr>
                    <td align="left" valign="top"><?php echo __('Description') ?></td>
                    <td valign="top">
                        <textarea name="pdescription" id="pdescription" rows="3" cols="70" type='textarea' onblur="updateMap(pluginurl)"><img src='<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/img/company.png'> {br} Yeronga QLD 4104, Australia {br} Phone:  0040 752 235 756</textarea><br>
                        <span class="info"><?php echo __('For multiple markers use: description1 | description2 | description3 and {br} for a new line') ?></span>
                    </td>
                </tr>
                
                <!-- Marker icon color -->
                <tr>
                    <td scope="row" align="left" valign="bottom"><?php echo __('Icon') ?></td>
                    <td valign="bottom">
                        <label><input name="color" type="radio" value="black" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/black.png"> </label>
                        <label><input name="color" type="radio" value="blue" onclick="updateMap(pluginurl)" checked /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/blue.png"> </label>
                        <label><input name="color" type="radio" value="gray" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/gray.png"> </label>
                        <label><input name="color" type="radio" value="green" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/green.png"> </label>
                        <label><input name="color" type="radio" value="magenta" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/magenta.png"> </label>
                        <label><input name="color" type="radio" value="orange" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/orange.png"> </label>
                        <label><input name="color" type="radio" value="purple" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/purple.png"> </label>
                        <label><input name="color" type="radio" value="red" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/red.png"> </label>
                        <label><input name="color" type="radio" value="white" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/white.png"> </label>
                        <label><input name="color" type="radio" value="yellow" onclick="updateMap(pluginurl)" /> <img width="27" height="38" src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/yellow.png"> </label>
                    </td>
                </tr>
                
                <!-- Marker icon url -->
                <tr>
                    <tr>
                    <td scope="row" align="left" valign="top"></td>
                    <td valign="top">
                        <label><input name="color" type="radio" value="custom" onclick="updateMap(pluginurl)" /></label>
                        <textarea name="iconurl" id="iconurl" rows="2" cols="66" type='textarea' onblur="">http://yava.ro/icons/car.png</textarea><br>
                        <span class="info"><?php echo __('For multiple markers use: http://yava.ro/icons/car.png | http://yava.ro/icons/sailboat.png') ?></span>
                    </td>
                </tr>
                
                <!-- Directions text -->
                <tr>
                    <td align="left"><?php echo __('Directions text') ?></td>
                    <td>
                        <input type="text" size="28" name="directions" id="directions" value="(directions to our address)" onblur="updateMap(pluginurl)"  />
                        <span class="info"><?php echo __('Optional, the text to put as directions text in the popup.') ?> </span>
                    </td>
                </tr>    
                
                <!-- Map center -->
                <tr>
                    <td align="left"><?php echo __('Center map to') ?></td>
                    <td>
                        <input type="text" size="28" name="center" id="center" value="" onblur="updateMap(pluginurl);"/>
                        <span class="info"><?php echo __('Optional: latitude, longitude i.e. 38.980288, 22.145996') ?></span>
                    </td>
                </tr>
                
                <!-- Controls -->
                <tr>
                    <td align="left"><?php echo __('Controls') ?></td>
                    <td>
                        <?php echo __('Pan control') ?>
                        <select name='panControl' id='panControl' onchange="updateMap(pluginurl)">
                            <option value='' selected><?php echo __('no') ?></option>
                            <option value='true'><?php echo __('yes') ?></option>
                        </select>
                         Scale control
                        <select name='scaleControl' id='scaleControl' onchange="updateMap(pluginurl)">
                            <option value='' selected><?php echo __('no') ?></option>
                            <option value='true'><?php echo __('yes') ?></option>
                        </select>
                        Type control
                        <select name='typeControl' id='typeControl' onchange="updateMap(pluginurl)">
                            <option value='' selected><?php echo __('no') ?></option>
                            <option value='true'><?php echo __('yes') ?></option>
                        </select>
                        Street control
                        <select name='streetControl' id='streetControl' onchange="updateMap(pluginurl)">
                            <option value='' selected><?php echo __('no') ?></option>
                            <option value='true'><?php echo __('yes') ?></option>
                        </select>
                    </td>
                </tr>

                <!-- Second line with map controls -->
                <tr>
                    <td align="left"></td>
                    <td>
                        <?php echo __('Zoom level') ?> &nbsp;
                        <select name="zoom" id="zoom" onchange="updateMap(pluginurl)">
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            <option value='6'>6</option>
                            <option value='7'>7</option>
                            <option value='8'>8</option>
                            <option value='9'>9</option>
                            <option value='10'>10</option>
                            <option value='11'>11</option>
                            <option value='12'>12</option>
                            <option value='13' selected>13</option>
                            <option value='14'>14</option>
                            <option value='15'>15</option>
                            <option value='16'>16</option>
                            <option value='17'>17</option>
                            <option value='18'>18</option>
                            <option value='19'>19</option>
                            </select>
                        <?php echo __('Zoom control') ?>
                        <select name='zoomControl' id='zoomControl' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        <?php echo __('Scroll wheel') ?>
                        <select name='scrollwheel' id='scrollwheel' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        <?php echo __('Draggable map') ?>
                        <select name='draggable' id='draggable' onchange="updateMap(pluginurl)">>
                            <option value=''>no</option>
                            <option value='true' selected>yes</option>
                        </select>
                        </td>
                </tr>
                
                <!-- Style -->
                <tr>
                    <td align="left"><strong><?php echo __('Map style') ?></strong></td>
                    <td>
                        <select name='style' id="style" onchange="showHideColorPicker();updateMap(pluginurl)">
                            <option value='0'><?php echo __('select color') ?></option>
                            <option value='1'>style 1</option>
                            <option value='2' selected>style 2</option>
                            <option value='3'>style 3</option>
                            <option value='4'>style 4</option>
                            <option value='5'>style 5</option>
                            <option value='6'>style 6</option>
                            <option value='7'>style 7</option>
                            <option value='8'>style 8</option>
                            <option value='9'>style 9</option>
                            <option value='10'>style 10</option>
                            <option value='11'>style 11</option>
                            <option value='12'>style 12</option>
                            <option value='13'>style 13</option>
                            <option value='14'>style 14</option>
                            <option value='15'>style 15</option>
                            <option value='16'>style 16</option>
                            <option value='17'>style 17</option>
                            <option value='18'>style 18</option>
                            <option value='19'>style 19</option>
                            <option value='20'>style 20</option>
                            <option value='21'>style 21</option>
                            <option value='22'>style 22</option>
                            <option value='23'>style 23</option>
                            <option value='24'>style 24</option>
                            <option value='25'>style 25</option>
                            <option value='26'>style 26</option>
                            <option value='27'>style 27</option>
                            <option value='28'>style 28</option>
                            <option value='29'>style 29</option>
                            <option value='30'>style 30</option>
                            <option value='31'>style 31</option>
                            <option value='32'>style 32</option>
                            <option value='33'>style 33</option>
                            <option value='34'>style 34</option>
                            <option value='35'>style 35</option>
                            <option value='36'>style 36</option>
                            <option value='37'>style 37</option>
                            <option value='38'>style 38</option>
                            <option value='39'>style 39</option>
                            <option value='40'>style 40</option>
                        </select>
                        <!-- Color picker -->
                        <input type="text" id="mapcolor" name="mapcolor" value="#2EA2CC" data-text="select the color..."/>
                        <span class="info" id="colorinfo"><?php echo __('Choose from 40 custom styles or a specific color.') ?></span>
                        <input type="hidden" name ="newcolor" id="newcolor" value="#2EA2CC"/>
                    </td>
                </tr>

                <!-- Width -->
                <tr>
                    <td align="left"><?php echo __('Map width') ?></td>
                    <td>
                        <input type="text" size="6" name="width" id="width" value="100" onblur="updateMap(pluginurl)"/>
                        <select name='widthm' id='widthm' onchange="updateMap(pluginurl)">
                            <option value='%' selected>%</option>
                            <option value='px'>px</option>
                        </select>
                        <span class="info"><?php echo __('100% for a responsive map (the map preview has fixed width and height).') ?></span>
                    </td>
                </tr>

                <!-- Height -->
                <tr>
                    <td align="left"><?php echo __('Map height') ?></td>
                    <td>
                        <input type="text" size="6" name="height" id="height" value="500" onblur="updateMap(pluginurl)"/>
                        <select name='heightm' id='heightm' onchange="updateMap(pluginurl)">
                            <option value='%'>%</option>
                            <option value='px' selected>px</option>
                        </select>
                        <span class="info"><?php echo __('In px or % (the map preview has fixed width and height).') ?></span>
                    </td>
                </tr>
                
                <!-- Map Type -->
                <tr>
                    <td align="left"><?php echo __('Map type') ?></td>
                    <td>
                        <select name='type' id="type" onchange="updateMap(pluginurl)">
                            <option value='roadmap' selected><?php echo __('roadmap') ?></option>
                            <option value='satellite'><?php echo __('satellite') ?></option>
                            <option value='terrain'><?php echo __('terrain') ?></option>
                            <option value='hybrid'><?php echo __('hybrid') ?></option>
                        </select>
                        <span class="info"><?php echo __('Possible values: roadmap, satellite, terrain or hybrid') ?></span>
                    </td>
                </tr>

                <!-- Popup -->
                <tr>
                    <td align="left"><?php echo __('Popup') ?></td>
                    <td>
                        <select name='popup' id="popup" onchange="updateMap(pluginurl)">
                            <option value=''><?php echo __('hidden') ?></option>
                            <option value='true' selected><?php echo __('visible') ?></option>
                        </select>
                        <span class="info"><?php echo __('The popup window which appears when a marker is clicked.') ?></span>
                    </td>
                </tr>
                
                <!-- Map refresh (when window is scaled) -->
                <tr>
                    <td align="left"><?php echo __('Refresh map') ?></td>
                    <td>
                        <select name='refresh' id='refresh' onchange="updateMap(pluginurl)">>
                            <option value='' selected><?php echo __('no') ?></option>
                            <option value='true'><?php echo __('yes') ?></option>
                        </select>
                        <span class="info"> <?php echo __('Select "yes" if the map should be refreshed (re-centered) when the window is scaled. ') ?></span>
                    </td>
                </tr>
                
                <!-- Adsense publisher id -->
                <tr>
                  <td align="left"><?php echo __('Adsense publisher id') ?></td>
                  <td width="300px">
                    <input type="text" size="40" name="pubid" id="pubid" value="" onblur="updateMap(pluginurl);"/>
                    <span class="info"><a href="https://support.google.com/adsense/answer/105516?hl=en" target="_blank"><?php echo __('Optional, a Google Adsense publisher id.') ?></a></span>
                  </td>
                  
                </tr>
				
                <!-- Adsense background color -->
                <tr>
                  <td align="left" valign="top"><?php echo __('Adsense ad background color') ?></td>
                  <td valign="top">
                  <input id="adbg" name="adbg" type="text" size="40" value="#ffffff" onblur="updateMap(pluginurl);"/>
                  <span class="info"><?php echo __('Optional, a hex color code for ad background.') ?></span>
                  </td>
                </tr>
                
            </table>
        </form>
    </div>
<?php
}
?>