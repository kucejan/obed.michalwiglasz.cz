<?php

ini_set('display_errors', 'on');
date_default_timezone_set('Europe/Prague');
header('content-type: text/html; charset=utf-8');

$root = "https://obed.michalwiglasz.cz";
$cache_default_interval = 60 * 60; // 1 hour
$cache_menza_interval = 60;  // 1 minute

if (isset($_GET['force'])) {
	$cache_default_interval = 0;
	$cache_menza_interval = 0;
}

$cache_html_interval = $cache_default_interval - 10;

$menza_close = strtotime('2016-06-30 23:59:59');
$menza_open = strtotime('2016-09-19 00:00:01');


$zomato = [
	'Camel' => [
		'https://www.zomato.com/cs/Camel1/denn%C3%AD-menu',
		'http://www.restaurace-camel.com/',
		'camel',
	],
	'U 3 opic' => [
		'https://www.zomato.com/cs/brno/u-3-opic-kr%C3%A1lovo-pole-brno-sever/denn%C3%AD-menu',
		'http://www.u3opic.cz/',
		'monkey',
	],
	'Velorex' => [
		'https://www.zomato.com/cs/brno/velorex-kr%C3%A1lovo-pole-brno-sever/denn%C3%AD-menu',
		'http://www.restauracevelorex.cz/',
		'velorex',
	],
	'Pad Thai' => [
		'https://www.zomato.com/cs/brno/pad-thai-kr%C3%A1lovo-pole-brno-sever/denn%C3%AD-menu',
		'http://padthairestaurace.cz/',
		'japanese',
	],
	'Yvy Restaurant' => [
		'https://www.zomato.com/cs/brno/yvy-restaurant-kr%C3%A1lovo-pole-brno-sever/denn%C3%AD-menu',
		'http://www.yvy.cz/',
		'yvy',
	],
];

$zomato_filters = [
	'(<div class="tmi-price ta-right col-l-2 bold">\\s*<div class="row">\\s*<\/div>\\s*</div>)ui' => '',
	'(\\((A.)?[0-9,\\s]+\\)\\s*</div>)i' => '</div>',
	'(<small class="tmi-desc">\\s*Alergeny:[^<]*</small>)i' => '',
	'(<div class="tmi-desc">\\s*Alergeny:[^<]*</div>)i' => '',
];

$menza_filters = [
	'(&nbsp;)' => ' ',
	'(<td class="levy">[HP]\\s+)' => '<td class="levy">',
	'((<td class="levyjid[^"]+"[^>]+>)P\s)ui' => '$1Polévka ',
	'(<small style=\'font-size: 8pt;\'>[^>]+</small>)' => '',
	'(<td class="levy"><small> </span></td>)' => '',
];
