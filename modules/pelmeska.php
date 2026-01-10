<?php

class Pelmeska extends LunchMenuSource
{
	public $title = 'Pelmeška';
	public $link = 'https://maps.app.goo.gl/cGLsVPCCc6RXRwmo8';
	public $sourceLink = 'https://maps.app.goo.gl/WfoMv86QYNUVKPPUA';
	public $icon = 'pelmeska';
	public $note = "Stálá nabídka, která není online.";


	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$result = new LunchMenuResult(DateTime::createFromFormat(DateTime::ATOM, "2026-01-09T11:10:00+0100")->getTimestamp());

		$group = "Polévka";
		$result->dishes[] = new Dish("Borsch se smetanou (nebo podle denní nabídky)", NULL, NULL, $group);

		$group = "Pelmeně";
		$result->dishes[] = new Dish("Sibiřské (hovězí a vepřové)", '169 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Hovězí", '179 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Drůbeží (kuřecí a krůtí)", '159 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Jelení", '209 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Jehněčí", '209 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Lososové", '209 Kč', '180 g', $group);
		$result->dishes[] = new Dish("Zapečené", '209 Kč', NULL, $group);

		$group = "Vareniky";
		$result->dishes[] = new Dish("S bramborovou kaší a cibulkou", '149 Kč', '240 g', $group);
		$result->dishes[] = new Dish("S bramborovou kaší a lanýžem", '159 Kč', '240 g', $group);
		$result->dishes[] = new Dish("S bramborovou kaší a liškami", '159 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Sladké s tvarohem a meruňkou", '159 Kč', '240 g', $group);
		$result->dishes[] = new Dish("Sladké s višněmi", '199 Kč', '180 g', $group);

		return $result;
	}
}
