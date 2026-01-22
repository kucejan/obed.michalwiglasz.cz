<?php

class Nepal extends LunchMenuSource
{
	public $title = 'Nepal';
	public $link = 'https://nepalbrno.cz/NepalBrno/poledni.php';
	public $icon = 'nepal';

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$cached = $this->downloadHtml($cacheSourceExpires);
		$result = new LunchMenuResult($cached['stored']);
		$html = $cached['html'];

		$todayString = mb_strtolower(date('l', $todayDate), 'utf-8');

		$specialsContainer = $html->find('div#specialsContainer', 0);
		if (!$specialsContainer) {
			$specialsContainer = $html->find('div.specials-container', 0); //fallback
		}

		if (!$specialsContainer) {
			throw new ScrapingFailedException("div#specialsContainer or div.specials-container not found");
		}

		foreach ($specialsContainer->find('div.day-section') as $daySection) {
			$dayTitleElement = $daySection->find('h2.day-title', 0);
			if (!$dayTitleElement) {
				continue;
			}
			$dayName = trim(mb_strtolower($dayTitleElement->plaintext, 'utf-8'));

			if (strpos($dayName, $todayString) === false) {
				continue;
			}

			foreach ($daySection->find('div.category-group') as $categoryGroup) {
				$categoryNameElement = $categoryGroup->find('h3.category-name', 0);
				$group = $categoryNameElement ? $categoryNameElement->plaintext : null;

				foreach ($categoryGroup->find('div.menu-items div.menu-item') as $menuItem) {
					$dishNameElement = $menuItem->find('h3', 0);
					$priceElement = $menuItem->find('span', 0);

					if ($dishNameElement && $priceElement) {
						$what = trim($dishNameElement->plaintext);
						$price = trim($priceElement->plaintext);

						if (!empty($what)) {
							$result->dishes[] = new Dish($what, $price, null, $group);
						}
					}
				}
			}
		}
		return $result;
	}
}
