<?php
/*
Plugin Name: Name Of The Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
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
//Resources:
//FourSquare Colors:http://www.colourlovers.com/web/trends/websites/7855/FourSquare
//
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
$name = $dataarray['response']['venue']['name'];
$address = $dataarray['response']['venue']['location']['address'];
$zip = $dataarray['response']['venue']['location']['postalCode'];
$city = $dataarray['response']['venue']['location']['city'];
$ctrycode = $dataarray['response']['venue']['location']['cc'];
$checkins = $dataarray['response']['venue']['stats']['checkinsCount'];
$likes = $dataarray['response']['venue']['likes']['count'];
$photopre = $dataarray['response']['venue']['photos']['groups']['0']['items']['1']['user']['photo']['prefix'];
$photosuf = $dataarray['response']['venue']['photos']['groups']['0']['items']['1']['user']['photo']['suffix'];
$photo = $photopre.'64x64'.$photosuf;
$mayorf = $dataarray['response']['venue']['mayor']['user']['firstName'];
$mayorl = $dataarray['response']['venue']['mayor']['user']['lastName'];
$mphp = $dataarray['response']['venue']['mayor']['user']['photo']['prefix'];
$mphs = $dataarray['response']['venue']['mayor']['user']['photo']['suffix'];
$mayorphoto = $mphp.'64x64'.$mphs;
echo '<html>
<head>
<style type="text/css">
* {margin:0;padding:0;font-family: Arial, Helvetica, sans-serif;};
html{font-family: Arial, Helvetica, sans-serif; font-weight:bold; margin:0; padding:0;}
div#v_wrap{width:300px;background:#40B3DF;list-style-type:none;padding:none;margin:auto;border:3px solid #17649A;
padding:5px;overflow:hidden;text-align:center;}
h4{line-height:30px;background:#17649A;color:#FFFFFF;border-bottom:1px solid #FFFFFF;}
p#ck{}
p#v_address{clear:left;}
div.entry {background:#A8CB17;background:rgba(168,203,23,0.8);overflow:hidden;border: 2px solid #FFFFFF;}
img#venue{float:left;border: 2px solid #FFFFFF;margin:6px; }
img#mayor{float:right;border: 2px solid #FFFFFF;margin:6px; }
</style>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
<div id="v_wrap">
<div id="top" class="entry">
<img id="venue" src="'.$photo.'"/><h4>'.$name.'</h4>
<p id="ck">Check-Ins:'.$checkins.' Likes:'.$likes.'</p><br/>
<p id="v_address">' . $address . ' ' . $city .' '.$zip.' '.$ctrycode.'</p>
<br/>
<h3>Current <img src="https://ss1.4sqi.net/img/legal-crown-816e9b13aaf659b514dcee77cb4b94be.png"/> Mayor</h3><br/>
<p> '.$mayorf . ' ' . $mayorl.'<img id="mayor" src="'.$mayorphoto.'"/></p>
 </div>
</div>
</body>
</html>';
//print var_dump(get_defined_vars());
?>
