<?php

namespace miner\classes;

class Record
{
	private $name;
	private $width;
	private $height;
	private $numberBombs;
	private $counterHelp;
	public function __construct($settings)
	{
		$this->name = $settings['name'];
		$this->width = $settings['width'];
		$this->height = $settings['height'];
		$this->numberBombs = $settings['numberBombs'];
		$this->counterHelp = $settings['counterHelp'];
	}
}