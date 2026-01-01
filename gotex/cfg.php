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
	// sorted by walking time from gotex (old exit to park, 6H6X+PG Brno-Královo Pole for google maps), from closest to farthest 
	new Source(new FreshMenu), // 0 m
	new Source(new MenickaCz(9363, 'Bistro na Botance', 'https://www.facebook.com/BistroNaBotance', 'botanka')), // 270m
	new Source(new MenickaCz(5448, 'Light of India', 'http://www.lightofindia.cz/lang-cs/denni-menu', 'india')), // 350m
	new Source(new HostinecPodSchody), //400m
	new Source(new MenickaCz(3854, 'Na Růžku', 'https://www.naruzkubrno.cz/tydenni-menu/', 'ruzek')), //400m
	new Source(new MenickaCz(2724, 'Plzeňský Dvůr', 'https://www.plzenskydvur.cz/', 'plzen')), //450m
	new Source(new MenickaCz(2609, 'Pizzeria Al Capone', 'https://www.pizzaalcapone.cz/poledni-menu', 'alcapone')), //500m
	new Source(new MenickaCz(6468, 'Divá Bára', 'https://www.restauracedivabara.cz/menu/', 'bara')), //500m
	new Source(new MenickaCz(2752, 'U Dřeváka Beer&Grill', 'https://udrevaka.cz/pages/poledni-menu', 'drevak')), //500m
	new Source(new Tao),
	new Source(new MenickaCz(5429, 'Bistro PLAC', 'https://bistroplac.cz/online-menu/section:obedove-menu-11-14h', 'plac')), //600m
	new Source(new MenickaCz(6694, 'Bistro Ministerio', 'https://ministerio.cz/', 'ministerio')), //800m
	new Source(new MenickaCz(4116, 'Padagali', 'https://padagali.cz/', 'pagadali')), //800m
	new Source(new MenickaCz(5416, 'Restaurace Na Halách', 'https://www.sportcentrumluzanky.cz/restaurace/', 'haly')), //850m
];
