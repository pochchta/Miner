<?php

namespace Miner\classes;

class BombCell extends Cell
{
	const SYMBOL = 'o';
	public $exploded = false;
	public function __toString()
	{
		if ($this->visible){
			return self::SYMBOL;
		}
		return '';
	}
}