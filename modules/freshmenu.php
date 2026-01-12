<?php

class FreshMenu extends LunchMenuSource
{
	private $restaurant;

	public $title = 'Fresh Menu';
	public $link = 'http://www.fresh-menu.cz/';
	public $icon = 'freshmenu';
	public $note = 'Silně experimentální, menu se tahá z PDF, lepší zdroj asi není.';

	public function __construct($restaurant = 'Centrum Šumavská')
	{
		$this->restaurant = mb_strtolower($restaurant);
	}

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$web = $this->downloadHtml($cacheSourceExpires, $this->link);
		$menus = $web['html']->find("nav ul li ul.sub-menu li a");

		foreach($menus as $menu) {
			if (mb_strtolower($menu->plaintext) != $this->restaurant)
				continue;

			$this->sourceLink = $menu->attr['href'];
		}

		if (!$this->sourceLink)
			throw new ScrapingFailedException("PDF link not found");

		$cached = $this->downloadRaw($cacheSourceExpires);
		$today = date('N', $todayDate) - 1;  // 0 = monday, 6 = sunday
		$result = new LunchMenuResult($cached['stored']);

		$pdf = new PDF2Text();
		$pdf->setContents($cached['contents']);
		$pdf->decodePDF();

		$output = $pdf->output();

		$output = preg_replace('![ ]+!', ' ', $output); # reduce spaces
		$output = preg_replace('![\n]+!', '', $output); # remove newlines

		$substrs = preg_split('/\s*(Pondělí|Úterý|Středa|Čtvrtek|Pátek|_)\s*/', $output);
		$weekmenu = array_slice($substrs, 1, 5);
		$daymenu = $weekmenu[$today];

		$match = preg_match('/\s*(Polévka[:]\s+.*)\s+(1\..*)\s+(2\..*)\s+(3\..*)\s+(4\..*)\s+(5\..*)/', $daymenu, $matches);
		$dishes = array_slice($matches, 1);

		foreach($dishes as $dish) {
			$match = preg_match('/^\s*(.*?)\s+([0-9]*\s*Kč)\s*$/', $dish, $matches);
			if ($match) {
				$what = $matches[1];
				$price = $matches[2];
				$result->dishes[] = new Dish($what, $price);
			} else {
				$result->dishes[] = new Dish($dish);
			}
		}

		return $result;
	}
}
