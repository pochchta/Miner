<?php

namespace Miner\classes;

class EmptyCell extends Cell
{
	const SYMBOL = ' ';
	public $countBombAround = 0;
	public function __toString()
	{
		if ($this->visible){
			if ($this->countBombAround != 0){
				return (string) $this->countBombAround;
			}
			return self::SYMBOL;
		}
		return '';
	}
}