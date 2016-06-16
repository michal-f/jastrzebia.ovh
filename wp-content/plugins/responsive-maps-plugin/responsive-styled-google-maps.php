<?php
/*
 * Plugin Name: Responsive Styled Google Maps
 * Description: A plugin which adds responsive and styled maps to pages and posts using a simple shortcode: [res_map address="street, city, country"]
 * Version: 2.23
 * Author: greenline
 * Text Domain: res_map
 * Domain Path: lang
 * Author URI: http://codecanyon.net/user/greenline
 * Plugin URI: http://codecanyon.net/item/responsive-styled-google-maps-wordpress-plugin/3909576
*/

/**
 * Include the plugin helper functions.
 */
require('responsive-styled-google-maps-functions.php');

/**
 * Include the plugin settings form.
 */
require('responsive-styled-google-maps-admin.php');

/**
 * Add a menu item for plugin's settings in WordPress Dashboard.
 */
function rsmaps_add_options_page() {
    global $rs_settings_page;
    $rs_settings_page = add_options_page(__('Responsive Styled Google Maps Helper'), __('Responsive Styled Google Maps Helper'), 'manage_options', __FILE__, 'rsmaps_admin');
}
add_action('admin_menu', 'rsmaps_add_options_page');

/**
 * Display a 'Settings' link in the 'Plugins - > Installed Plugins' page.
 */
function rsmaps_plugin_action_links($links, $file) {
    if ($file == plugin_basename( __FILE__ )) {
        $rsmaps_links = '<a href="'.get_admin_url().'options-general.php?page=responsive-maps-plugin/responsive-styled-google-maps.php">'.__('Settings').'</a>';
        // Make the 'Settings' link appear first
        array_unshift( $links, $rsmaps_links );
    }
    return $links;
}
add_filter('plugin_action_links', 'rsmaps_plugin_action_links', 10, 2);

/**
 * Display the 'Documentation' and 'Support' links in the 'Plugins - > Installed Plugins' page.
 */
function rsmaps_plugin_row_meta($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $documentation_link = '<a target="_blank" href="' . plugin_dir_url(__FILE__) . 'documentation/' .
                              '" title="' . __('View documentation', 'res_map') . '">' . __('Documentation', 'res_map') . '</a>';
        $support_link = '<a target="_blank" href="http://codecanyon.net/user/greenline" title="' . __('Contact plugin author', 'res_map') . '">' . __('Support', 'res_map') . '</a>';
            
        $links[] = $documentation_link;
        $links[] = $support_link;
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'rsmaps_plugin_row_meta', 10, 2 );   

/**
 * Add the stylesheet needed for the plugin.
 */
function responsive_map_css() {
    wp_register_style('responsive_map_css', plugins_url('includes/css/rsmaps.css', __FILE__), false, '2.23');
    wp_enqueue_style('responsive_map_css');
}
add_action('wp_enqueue_scripts', 'responsive_map_css');
 
/**
 * Register needed jquery scripts
 */
function responsive_map_scripts() {
    // Register Google Maps API library depending if is a SSL connection or not 
    if (is_ssl()) {
            wp_register_script('googlemapsapi', 'https://maps-api-ssl.google.com/maps/api/js?sensor=false&v=3.exp&libraries=adsense', array( 'jquery' ), null, true);
    } else {
            wp_register_script('googlemapsapi', 'http://maps.googleapis.com/maps/api/js?sensor=false&v=3.exp&libraries=adsense', array( 'jquery' ), null, true);
    }
    // Also register the third-party Google maps jQuery plugin used for geocoding 
    wp_register_script('jquerygmap', plugins_url('includes/js/jquery.gmap.min.js', __FILE__), array('jquery'), '3.3.3', true);
    // Also register the plugin's javascript file
    wp_register_script('rsmaps', plugins_url('includes/js/rsmaps.js', __FILE__), array('jquery'), '3.3.3', true);
    // Then, we enqueue them only when the shortcode function is called, to avoid unneccessary loading in all pages.
}
add_action('wp_enqueue_scripts', 'responsive_map_scripts');

/**
 * Add the stylesheet needed for the plugin's admin page.
 */
function admin_responsive_map_css($hook) {
    // If not on Responsive Styled Google Maps Plugin settings page, return and do not add stylesheets
    global $rs_settings_page;
    if($hook != $rs_settings_page)  
        return;
        
    // Register and enqueue the required stylesheets
    wp_register_style('responsive_map_css', plugins_url('includes/css/rsmaps.admin.css', __FILE__), false, '2.23');
    wp_enqueue_style('responsive_map_css');
}
add_action('admin_enqueue_scripts', 'admin_responsive_map_css');

/**
 * Register and enqueue scripts neccessary to plugin's admin page
 */
function admin_responsive_map_scripts($hook) {
    // If not on Responsive Styled Google Maps Plugin settings page, return and do not add scripts
    global $rs_settings_page;
    if($hook != $rs_settings_page)  
        return;
    
    // Register and enqueue the neccessary scripts
    if (is_ssl()) {
            wp_register_script('googlemapsapi', 'https://maps-api-ssl.google.com/maps/api/js?sensor=false&v=3.exp&libraries=adsense', array( 'jquery' ), null, false);
    } else {
            wp_register_script('googlemapsapi', 'http://maps.googleapis.com/maps/api/js?sensor=false&v=3.exp&libraries=adsense', array( 'jquery' ), null, false);
    }
    wp_register_script('jquerygmap', plugins_url('includes/js/jquery.gmap.min.js', __FILE__), array('jquery'), '3.3.3');
    wp_register_script('rsmaps', plugins_url('includes/js/rsmaps.admin.js', __FILE__), array('jquery'), '2.23');
    
    // Enque the neccessary scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('googlemapsapi');
    wp_enqueue_script('jquerygmap');
    wp_enqueue_script('rsmaps');
}
add_action('admin_enqueue_scripts', 'admin_responsive_map_scripts');

/**
 * Define the shortcode: [res_map] and its attributes
 */
function responsive_map_shortcode($atts) {

    // Extract the attributes user gave in the shortcode
    $atts = shortcode_atts(array(
      'width'           => '',        // Leave blank for 100% (responsive map), or use a width in 'px' or '%'
      'height'          => '500px',   // Use a height in 'px' or '%'
      'maptype'         => 'roadmap', // Possible values: roadmap, satellite, terrain or hybrid
      'zoom'            => 14,        // Zoom, use values between 1-19
      'address'         => '',        // Markers addresses in this format: street, city, country | street, city, country | street, city, country
      'description'     => '',        // Markers descriptions in this format: description1 | description2 | description3 (one for each marker address above)
      'popup'           => 'false',   // true or false
      'pancontrol'      => 'false',   // true or false
      'zoomcontrol'     => 'false',   // true or false
      'draggable'       => 'true',    // true or false
      'scrollwheel'     => 'false',   // true or false
      'typecontrol'     => 'false',   // true or false
      'scalecontrol'    => 'false',   // true or false
      'streetcontrol'   => 'false',   // true or false
      'directionstext'  => '',        // The text to be displayed for directions link
      'center'          => '',        // The point where the map should be centered (latitude, longitude) for instance: center="38.980288, 22.145996"
      'icon'            => 'green',   // Possible color values: black, blue, gray, green, magenta, orange, purple, red, white, yellow or a link to a custom image
      'style'           => '1',       // Use style values between 1-30
      'refresh'         => 'false',   // true or false (true if the map should be refreshed and re-centered when window is scaled; false otherwise)
      'publisherid'     => '',        // The google adsense publisher id
      'adbg'            => '#ffffff'  // The background color of the google adsense advertising box displayed
    ), $atts);
    
    // Enque the neccessary jquery files
    wp_enqueue_script("jquery");
    wp_enqueue_script('googlemapsapi');
    wp_enqueue_script('jquerygmap');
    wp_enqueue_script('rsmaps');
    
    // Generate a unique identifier for the map
    $mapid = rand();

    // Extract the map type
    $atts['maptype'] = strtoupper($atts['maptype']);
    
    // If width or height were specified in the shortcode, extract them too
    $dimensions = 'height:'.$atts['height'];
    if($atts['width'])
        $dimensions .= ';width:'.$atts['width'];

    // Set the pre-defined style which corresponds to the number given in the shortcode
    $atts['style'] = getStyleString($atts['style']);
    
    // Extract the langitude and longitude for the map center
    if (trim($atts['center'])  != "") {
        sscanf($atts['center'], '%f, %f', $lat, $long);
    } else {
        $lat = 'null'; $long = 'null';
    }
    
    // Split the addresses and descriptions (by the pipeline "|" delimiter) and build markers JSON list
    if ($atts['address'] != '') {
      $addresses = explode("|", $atts['address']);
      $descriptions = explode("|", $atts['description']);
      $icons = explode("|", $atts['icon']);

      // Build a marker for each address
      $markers = '[';

      for($i = 0; $i < count($addresses); $i ++) {
        $address = cleanHtml($addresses[$i]);
        
        // If it's empty, set the default description equal to the the address
        if(isset($descriptions[$i]) && strlen(trim($descriptions[$i])) != 0) {
            $html = $descriptions[$i];  
        }
        else {
            $html = $address;
        }
            
        // Add the directions link to the description
        if (isset($atts['directionstext']) && strlen(trim($atts['directionstext'])) != 0) {
            $directions = 'http://maps.google.com/?daddr=' . urlencode($address);
            $html .= '<br><a target=\'_blank\' href="'. $directions .'">'. $atts['directionstext'] .'</a>' ;
        }
            
        // Prepare the description html
        $html = cleanHtml($html);
        
        // Get the correct icon image based on icon color or icon url given in the shortcode
        if(isset($icons[$i])) {
            $icon = getIcon($icons[$i]);
        } 

        // Extract the langitude and longitude
        $marker_latitude = null;
        $marker_longitude = null;
        if (trim($address)  != "") {
            sscanf($address, '%f, %f', $marker_latitude, $marker_longitude);
        }
        // If more markers, add the neccessary "," delimiter between markers
        if ($i > 0) $markers .= ",";
        
        // Build markers list based on given address or latitude/longitude
        if ($marker_latitude == '' || $marker_longitude == '') {
            $markers .= '{
                    address: \''. $address .'\', 
                    key: \''. ($i + 1)  . '\',';
        } else {
            $markers .= '{
                    latitude:' . $marker_latitude .', 
                    longitude:' . $marker_longitude .',
                    key: \''. ($i +1)  . '\',';
        }
        $markers .= 'html:"'. $html .'",
                    popup: '. toBool($atts['popup']) .',
                    flat: true,
                    icon: {
                        image: \''. $icon .'\'
                    }}';
        }
        $markers .= ']';
    }
    // Tell PHP to start output buffering
    ob_start();
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
    // The div object that will contain the map
    var mapdiv = jQuery("#responsive_map_<?php echo $mapid; ?>");
    var publisherid = "<?php echo $atts['publisherid']; ?>";
    // Create the map in the div 
    mapdiv.gMapResp({
        maptype: google.maps.MapTypeId.<?php echo $atts['maptype']; ?>,
        log: false,
        zoom: <?php echo $atts['zoom']; ?>,
        markers: <?php echo $markers; ?>,
        panControl: <?php echo toBool($atts['pancontrol']); ?>,
        zoomControl: <?php echo toBool($atts['zoomcontrol']); ?>,
        draggable: <?php echo toBool($atts['draggable']); ?>,
        scrollwheel: <?php echo toBool($atts['scrollwheel']); ?>,
        mapTypeControl: <?php echo toBool($atts['typecontrol']); ?>,
        scaleControl: <?php echo toBool($atts['scalecontrol']); ?>,
        streetViewControl: <?php echo toBool($atts['streetcontrol']); ?>,
        overviewMapControl: true,
        styles: <?php echo $atts['style']; ?>,
        latitude: <?php echo $lat; ?>,
        longitude: <?php echo $long; ?>,
        onComplete: function() {
            if(mapdiv.is(':hidden')) { // map is displayed in tabs
                $.each(['show'], function (i, ev) {
                    var el = $.fn[ev];
                    $.fn[ev] = function () {
                      this.trigger(ev);
                      el.apply(this, arguments);
                      jQuery('.responsive-map').each(function(){
                          if (jQuery(this).data('gmap')) {
                              // Fix maps on tabs
                              jQuery(this).gMapResp('fixAfterResize');
                              // Then trigger the click event for each marker to avoid tabs issues
                              var popup = <?php echo toBool($atts['popup']); ?>;
                              var markers = jQuery(this).data('gmap').markers;
                              if (popup) {
                                  console.log(popup);
                                  for (var i = 0; i < markers.length; i++) {
                                      google.maps.event.trigger( markers[i], 'click');
                                  }
                              }
                          }
                      });
                    };
                });
            }
            // Show ad if publisher id given
            if (publisherid.trim()  != "") {
            var gmap = mapdiv.data('gmap').gmap;
            var adUnitDiv = document.createElement('div');
            var adUnitOptions = {
                format: google.maps.adsense.AdFormat.HALF_BANNER,
                position: google.maps.ControlPosition.RIGHT_BOTTOM,
                backgroundColor: '<?php echo $atts['adbg']; ?>',
                borderColor: '#666666',
                titleColor: '#333333',
                textColor: '#666666',
                urlColor: '#999999',
                publisherId: publisherid,
                map: gmap,
                visible: true
            };
            var adUnit = new google.maps.adsense.AdUnit(adUnitDiv, adUnitOptions);}
        }
     });
  });
<?php if (isset($atts['refresh']) && $atts['refresh'] == 'yes') { ?>
  // If the refresh parameter is set to true, resize the map when window is resized 
  window.onresize = function() {
        jQuery('.responsive-map').each(function(i, obj) {
            data = jQuery(this).data('gmap');
            if (data) {
                var gmap = jQuery(this).data('gmap').gmap;
                google.maps.event.trigger(gmap, 'resize');
                jQuery(this).gMapResp('fixAfterResize');
            }
        });
  };
  <?php } ?>
  </script>
  <div id="responsive_map_<?php echo $mapid; ?>" class="responsive-map" style="<?php echo $dimensions; ?>;"></div>
  <?php
  return ob_get_clean();
}
add_shortcode('res_map', 'responsive_map_shortcode');
