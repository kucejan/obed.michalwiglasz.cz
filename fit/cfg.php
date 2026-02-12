<?php

$root = "https://obed.cucin.eu/fit";
$cache_default_interval = 60 * 60; // 1 hour
$cache_menza_interval = 60;  // 1 minute
$cache_html_interval = $cache_default_interval - 10;

if (isset($_GET['force'])) {
	$cache_default_interval = 0;
	$cache_html_interval = 0;
	$cache_menza_interval = 0;
}


$menza_close = strtotime('2018-06-15 23:59:59');
$menza_open = strtotime('2018-09-17 00:00:01');


$sources = [
	new Source(new MenickaCz(3225, 'U 3 opic', 'http://www.u3opic.cz/', 'u3opic')),
	new Source(new MenickaCz(8483, 'Pad Thai', 'http://www.padthairestaurace.cz', 'thailand')),
	new Source(new Nepal),
	new Source(new Bistro53),
	new Source(new Charlies('Charlie\'s Mill', 'https://www.charliesmill.cz')),
	new Source(new KlubCestovatelu),
	new Source(new MenickaCz(3874, 'U Mušketýra', 'https://www.musketyrbrno.cz/', 'musketyr')),
];


if (get_today_timestamp() < $menza_close || get_today_timestamp() > $menza_open) {
	$sources[] = new Source(new Menza, 60);
}
