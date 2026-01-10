<?php

class HostinecPodSchody extends LunchMenuSource
{
	public $title = 'Hostinec Pod Schody';
	public $link = 'https://hostinecpodschody.cz';
	public $sourceLink = 'https://hostinecpodschody.cz/%f0%9f%8d%bd%ef%b8%8f-obedove-menu-hostinec-pod-schody/';
	public $icon = 'podschody';

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$cached = $this->downloadHtml($cacheSourceExpires);
		$result = new LunchMenuResult($cached['stored']);

		$menu = $cached['html']->find("div ul li.denni_menu");
		$today = get_czech_day(date('w', $todayDate));

		foreach ($menu as $item) {
			$node = $item->find("h2", 0);
			if (!$node)
				continue;

			$daymenu = preg_match("/^$today/ui", $node->plaintext);
			$weekmenu = preg_match('/týdenní/ui', $node->plaintext);
			if (!$daymenu) // && !$weekmenu
				continue;

			$lines = $item->find("li");
			foreach ($lines as $line) {
				$dish = $line->plaintext;
				$price = NULL;

				if (preg_match('/([^–]*)\s*–\s*([^–]*)/u', $dish, $matches)) {
					$dish = $matches[1];
					$price = "$matches[2] Kč";
				}

				$lower = mb_strtolower($dish);
				$dishlow = mb_substr($dish, 0, 1) . mb_substr($lower, 1);

				$group = $weekmenu ? "Týdenní menu" : NULL;
				$result->dishes[] = new Dish($dishlow, $price, NULL, $group);
			}
		}

		return $result;
	}
}
