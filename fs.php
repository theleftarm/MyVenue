<?php
/*
Plugin Name: MyVenue
Plugin URI: www.theleftarm.com/plugins/myvenue
Description: Fetches and caches current venue information from FourSquare.
Version: 1.0
Author: Sam Mank ( sam@theleftarm.com)
Author URI: http://theleftarm.com/
License: GPL2
*/
/*  Copyright 2013  Sam Mank  (email : sam@theleftarm.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
   // add_action( 'widgets_init', 'register_my_venue' );
class my_venue extends WP_Widget {

function my_venue() {
parent::WP_Widget(false, $name = 'MyVenue');
if ( !function_exists( 'register_sidebar_widget' ))
		return;
        if ( false === ( $my_venue_data = get_transient( 'my_venue_data' ) ) ) {
        if(!function_exists("curl_init")) die("cURL extension is not installed");
    $url='https://api.foursquare.com/v2/venues/50ae8912830240763aabfc2c?client_id=LT1RSNLZJCG24SO1DNH351UHLXCZPIK2HYQ1I1GA0BYJFVAE&client_secret=PRKUTHDYILLZX3Y0QPSK3HSYSJVUC3X4HLT1MKVAR4UPULCJ&v=20121212';
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $status = curl_getinfo($ch);
    $r=curl_exec($ch);
    curl_close($ch);

    $dataarray = json_decode($r, true);
    $my_venue_data = array();
    $my_venue_data['name'] = $dataarray['response']['venue']['name'];
    $my_venue_data['address'] = $dataarray['response']['venue']['location']['address'];
    $my_venue_data['zip'] = $dataarray['response']['venue']['location']['postalCode'];
    $my_venue_data['city'] = $dataarray['response']['venue']['location']['city'];
    $my_venue_data['ctrycode'] = $dataarray['response']['venue']['location']['cc'];
    $my_venue_data['checkins'] = $dataarray['response']['venue']['stats']['checkinsCount'];
    $my_venue_data['likes'] = $dataarray['response']['venue']['likes']['count'];
    $my_venue_data['photopre'] = $dataarray['response']['venue']['photos']['groups']['0']['items']['1']['user']['photo']['prefix'];
    $my_venue_data['photosuf'] = $dataarray['response']['venue']['photos']['groups']['0']['items']['1']['user']['photo']['suffix'];
    $my_venue_data['photo'] = $my_venue_data['photopre'].'64x64'.$my_venue_data['photosuf'];
    $my_venue_data['mayorf'] = $dataarray['response']['venue']['mayor']['user']['firstName'];
    $my_venue_data['mayorl'] = $dataarray['response']['venue']['mayor']['user']['lastName'];
    $my_venue_data['mphp'] = $dataarray['response']['venue']['mayor']['user']['photo']['prefix'];
    $my_venue_data['mphs'] = $dataarray['response']['venue']['mayor']['user']['photo']['suffix'];
    $my_venue_data['mayorphoto'] = $my_venue_data['mphp'].'64x64'.$my_venue_data['mphs'];
    set_transient( 'my_venue_data', $my_venue_data, 30 * MINUTE_IN_SECONDS );
    }
echo '<div id="v_wrap">
<div id="top" class="entry">
<img id="venue" src="'.$my_venue_data[photo].'"/><h4>'.$my_venue_data[name].'</h4>
<p id="ck">Check-Ins: '.$my_venue_data[checkins].' Likes:'.$my_venue_data['likes'].'</p><br/>
<p id="v_address">' . $my_venue_data['address'] . ' ' . $my_venue_data['city'] .' '.$my_venue_data['zip'].' '.$my_venue_data['ctrycode'].'</p>
<br/>
<h3>Current <img src="https://ss1.4sqi.net/img/legal-crown-816e9b13aaf659b514dcee77cb4b94be.png"/> Mayor</h3><br/>
<p> '.$my_venue_data['mayorf'] . ' ' . $my_venue_data['mayorl'].'<img id="mayor" src="'.$my_venue_data['mayorphoto'].'"/></p>
 </div>
</div>';
}
}
    wp_register_sidebar_widget(
    'my_venue_1',        // your unique widget id
    'MyVenue',          // widget name
    'my_venue_init',  // callback function
    array(                  // options
        'description' => 'Displays your current Venue information from FourSquare'
    ));
add_action('widgets_init', create_function('', 'return register_widget("my_venue");'));
//print var_dump(get_defined_vars());
?>