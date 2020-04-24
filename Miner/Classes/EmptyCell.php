<?php

namespace Miner\classes;

class EmptyCell extends Cell
{
	const SYMBOL = 'x';
	public $countBombAround = 0;
	public function __toString()
	{
		if ($this->countBombAround != 0){
			return (string) $this->countBombAround;
		}
		return self::SYMBOL;
	}	
}