<?php

class Flank extends LunchMenuSource
{
	public $title = 'Flank';
	public $link = 'https://www.flank.cz/';
	public $sourceLink = 'https://www.flank.cz/denni-menu/';
	public $icon = 'flank';

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$cached = $this->downloadHtml($cacheSourceExpires);
		$result = new LunchMenuResult($cached['stored']);

		$daily_menu = $cached['html']->find("div.daily-menu-day--active div.food-menu-item");
		$weekly_menu = $cached['html']->find("div.weekly-menu div.food-menu-item");

		foreach ($daily_menu as $item) {
			$row = $item->find("div.food-menu-item__row", 0);
			$name = html_entity_decode($row->find("div.food-menu-item__name", 0)->plaintext);
			$price = html_entity_decode($row->find("div.food-menu-item__price", 0)->plaintext);
			$result->dishes[] = new Dish($name, $price);
		}

		foreach ($weekly_menu as $item) {
			$row = $item->find("div.food-menu-item__row", 0);
			$name = html_entity_decode($row->find("div.food-menu-item__name", 0)->plaintext);
			$price = html_entity_decode($row->find("div.food-menu-item__price", 0)->plaintext);
			$result->dishes[] = new Dish($name, $price, NULL, "Týdenní nabídka");
		}

		return $result;
	}
}
