<?php

$root = "https://obed.michalwiglasz.cz/embedit";
$cache_default_interval = 60 * 60; // 1 hour
$cache_menza_interval = 60;  // 1 minute
$cache_html_interval = $cache_default_interval - 10;

if (isset($_GET['force'])) {
	$cache_default_interval = 0;
	$cache_html_interval = 0;
	$cache_menza_interval = 0;
}

$sources = [
	new Source(new LaCorrida),
	new Source(new MenickaCz(2749, 'Rubín', 'http://restauracerubin.cz/', 'rubin')),
	new Source(new MenickaCz(2663, 'Korzár', 'http://www.korzar.com/cz/', 'musketyr')),
	new Source(new MenickaCz(3185, 'Zelená kočka', 'https://www.zelenakocka.cz/', 'kocka')),
	new Source(new Zomato(16506040, 'Šelepka', 'http://www.selepova.cz/denni-menu/', 'selepka')),
];