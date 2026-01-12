<?php

class Bratri extends LunchMenuSource
{
	public $title = 'U Nemilosrdných bratří';
	public $link = 'https://zelenakocka.cz/';
	public $icon = 'bratri';
	public $note = 'Silně experimentální, menu se tahá z PDF, lepší zdroj asi není.';

	public function getTodaysMenu($todayDate, $cacheSourceExpires)
	{
		$web = $this->downloadHtml($cacheSourceExpires, $this->link);

		$iframe = $web['html']->find("div.elementor-widget-pdf_viewer iframe", 0);
		if (!$iframe)
			throw new ScrapingFailedException("iframe not found");

		$menu = $iframe->attr['src'];
		if (!preg_match("@($this->link.*\.pdf)@u", $menu, $matches))
			throw new ScrapingFailedException("PDF link not found");

		$this->sourceLink = $matches[0];

		$cached = $this->downloadRaw($cacheSourceExpires);
		$today = date('N', $todayDate) - 1;  // 0 = monday, 6 = sunday
		$result = new LunchMenuResult($cached['stored']);

		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseContent($cached['contents']);
		$output = $pdf->getText();

		$output = preg_replace('![\x{200B}\x{200C}\x{200D}\x{FEFF}]!u', '', $output); # remove zero-width
		$output = preg_replace('![^\S\n]+!', ' ', $output); # reduce spaces
		$output = preg_replace('![ ]*\n!', "\n", $output); # trim endline spaces
		$output = preg_replace('![\n]+!', "\n", $output); # reduce newlines

		$substrs = preg_split('/\s*((Pondělí|Úterý|Středa|Čtvrtek|Pátek|Nabídka)[^\n]*\n)\s*/', $output);

		$weekmenu = array_slice($substrs, 1, 5);
		$daymenu = $weekmenu[$today];

		$dishes = explode("\n", $daymenu);

		foreach ($dishes as $item) {
			$dish = $item;
			$price = NULL;

			if (preg_match('/(.*)\s([0-9]+,-)/u', $dish, $matches)) {
				$dish = $matches[1];
				$price = "$matches[2] Kč";
			}

			$result->dishes[] = new Dish($dish, $price);
		}

		return $result;
	}
}
