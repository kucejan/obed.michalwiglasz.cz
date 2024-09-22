<?php

$root = "https://obed.cucin.eu/gotex";
$cache_default_interval = 60 * 60; // 1 hour
$cache_menza_interval = 60;  // 1 minute
$cache_html_interval = $cache_default_interval - 10;

if (isset($_GET['force'])) {
	$cache_default_interval = 0;
	$cache_html_interval = 0;
	$cache_menza_interval = 0;
}

$sources = [
	new Source(new MenickaCz(3185, 'Zelená kočka', 'https://www.zelenakocka.cz/', 'kocka')),
	new Source(new MenickaCz(2721, 'Šelepka', 'http://www.selepova.cz/denni-menu/', 'selepka')),
	new Source(new MenickaCz(5429, 'Bistro PLAC', 'https://bistroplac.cz/online-menu/section:obedove-menu-11-14h', 'plac')),
	new Source(new MenickaCz(2724, 'Plzeňský Dvůr', 'https://www.plzenskydvur.cz/', 'plzen')),
	new Source(new MenickaCz(5416, 'Restaurace Na Halách', 'https://www.sportcentrumluzanky.cz/restaurace/', 'haly')),
	new Source(new MenickaCz(2609, 'Pizzeria Al Capone', 'https://www.pizzaalcapone.cz/poledni-menu', 'alcapone')),
	new Source(new MenickaCz(6468, 'Divá Bára', 'https://www.restauracedivabara.cz/menu/', 'bara')),
	new Source(new MenickaCz(2752, 'U Dřeváka Beer&Grill', 'https://udrevaka.cz/pages/poledni-menu', 'drevak')),
	new Source(new MenickaCz(3854, 'Na Růžku', 'https://www.naruzkubrno.cz/tydenni-menu/', 'ruzek')),
	new Source(new MenickaCz(5448, 'Light of India', 'http://www.lightofindia.cz/lang-cs/denni-menu', 'india')),
	new Source(new MenickaCz(4116, 'Padagali', 'https://padagali.cz/', 'pagadali')),
	new Source(new MenickaCz(6694, 'Bistro Ministerio', 'https://ministerio.cz/', 'ministerio')),
	new Source(new MenickaCz(5818, 'Botanic', 'https://www.botanicbar.cz/#menu', 'botanic')),
];
