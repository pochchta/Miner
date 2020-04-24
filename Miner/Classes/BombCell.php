<?php

namespace Miner\classes;

class BombCell extends Cell
{
	const SYMBOL = 'o';
	public function __toString()
	{
		return self::SYMBOL;
	}	
}