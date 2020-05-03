<?php

namespace miner\classes;

abstract class Cell
{
	public $visible = false;
	abstract public function __toString();
}