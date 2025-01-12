<?php

class Tao extends LunchMenuSource
{
	public $title = 'Tao Restaurant';
	public $link = 'https://www.taorestaurant.cz';
	public $sourceLink = 'https://www.taorestaurant.cz/tydenni_menu/nabidka';
	public $icon = 'tao';

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$cached = $this->downloadHtml($cacheSourceExpires);
		$result = new LunchMenuResult($cached['stored']);

		$menu = $cached['html']->find("div.tydenni-menu-text");
		$today = get_czech_day(date('w', $todayDate));

		foreach ($menu as $item) {
			// Remove HTML entities
			$line = html_entity_decode($item->plaintext);

			// Remove emoticons and other pictograms
			//$line = preg_replace('/[^\p{L}\p{N}\p{P}\s]+/u', '', $line);

			if (!preg_match('/^((([0-9]+\.\s*M[0-9]+)[,:])|(\w+):)\s*(.*)[^0-9](([0-9]+)\s?K[čc])(.*)/ui', $line, $matches)) {
				continue;
			}

			$number = $matches[3];
			$weekday = $matches[4];

			$what = trim($matches[5]);
			$what = preg_replace('/\( /', '(', $what);
			$what = preg_replace('/(.)\(/', '$1 (', $what);
			$what = preg_replace('/ ,/', ',', $what);
			$what = preg_replace('/ \./', '.', $what);
			$what = preg_replace('/\.+/', '.', $what);

			if (strlen($number))
				$what = $number . ': ' . $what;

			if (strlen($weekday) && mb_strtolower($weekday) != $today)
				continue;

			$group = strlen($weekday) ? "Speciální nabídka:" : NULL;
			$price = $matches[7]. ' Kč';

			$result->dishes[] = new Dish($what, $price, NULL, $group);
		}

		return $result;
	}
}
