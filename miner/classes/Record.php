<?php

namespace miner\classes;

class Record
{
	private $name;
	private $level;
	private $counterHelp;
	private $time;
	public function __construct($settings)
	{
		$this->name = $settings['name'];
		$this->level = $settings['level'];
		$this->counterHelp = $settings['counterHelp'];
		$this->time = $settings['time'];
	}
	public function getPropArray()
	{
		return array(
			'name' => $this->name,
			'level' => $this->level,
			'counterHelp' => $this->counterHelp,
			'time' => $this->time,
		);
	}
}