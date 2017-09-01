<?php

abstract class LunchMenuSource
{
	public $title;
	public $link;
	public $icon;

	abstract public function getTodaysMenu($todayDate, $cacheSourceExpires);
}


class LunchMenuResult
{
	public $timestamp;
	public $dishes;

	public function __construct($timestamp, $dishes = array())
	{
		$this->timestamp = $timestamp;
		$this->dishes = $dishes;
	}
}


class Source
{
	public $cacheExpires;
	public $module;

	public function __construct($module, $cacheExpires=NULL)
	{
		$this->module = $module;
		$this->cacheExpires = $cacheExpires;
	}
}


class Dish
{
	public $number;
	public $name;
	public $price;
	public $quantity;
	public $group;

	public function __construct($name, $price=NULL, $quantity=NULL, $group=NULL, $number=NULL)
	{
		$this->name = trim($name);
		$this->price = trim($price);
		$this->quantity = trim($quantity);
		$this->group = trim($group);
		$this->number = trim($number);

		if (is_null($quantity)) {
			// try to extract quantity from name
			if (preg_match('(^([0-9]+\.)?\s*([0-9,.]+)\s*([gl])\s+(.+?)$)ui', $this->name, $m)) {
				// found at the beginning
				$this->name = trim("$m[1] $m[4]");
				$this->quantity = trim("$m[2] $m[3]");

			} elseif (preg_match('(([0-9]+\.)?\s*(.+?)([0-9,.]+)\s*([gl])$)ui', $this->name, $m)) {
				// found at the end
				$this->name = trim("$m[1] $m[2]");
				$quantity = trim("$m[3] $m[4]");
			}

		} else {
			// try to fix spacing
			if (preg_match('(^([0-9,.]+)\s*([gl])$)ui', $this->quantity, $m)) {
				$this->quantity = trim("$m[1] $m[2]");
			}
		}

		// try to extract number from name
		if (is_null($number)) {
			if (preg_match('(^(?:menu\s+)?([0-9]+)[.:]\s*(.+))ui', $this->name, $m)) {
				$this->number = trim($m[1]);
				$this->name = trim($m[2]);
			}
		}
	}
}


class ScrapingFailedException extends Exception
{

}