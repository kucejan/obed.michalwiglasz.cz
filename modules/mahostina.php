<?php

class MaHostina extends LunchMenuSource
{
	public $title = 'Má hostina';
	public $link = 'https://www.mahostina.cz/';
	public $sourceLink = 'https://www.mahostina.cz/#dnesnibasta-section';
	public $icon = 'mahostina';

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$cached = $this->downloadHtml($cacheSourceExpires);
		$result = new LunchMenuResult($cached['stored']);

		$hostina = $cached['html']->find("div#dnesnibasta-page ul");

		foreach ($hostina as $basta) {
			$sibling = $basta->prev_sibling();
			if (!$sibling)
				continue;

			$caption = $sibling->plaintext;
			$sidemenu = preg_match('/před obědem/ui', $caption);
			$mainmenu = preg_match('/dnešní nabídka/ui', $caption);
			if (!$sidemenu && !$mainmenu)
				continue;

			$menu = $basta->find("li");
			foreach ($menu as $item) {
				$dish = $item->plaintext;
				$price = NULL;

				if (preg_match('/(.*?)\s*([0-9]+,-)\s*(.*)/um', $dish, $matches)) {
					$dish = "$matches[1], $matches[3]";
					$price = "$matches[2] Kč";
				}

				$result->dishes[] = new Dish($dish, $price, NULL, $caption);
			}
		}

		return $result;
	}
}
