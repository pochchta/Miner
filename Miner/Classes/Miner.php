<?php

namespace Miner\classes;

class Miner
{
	private $field = array(array());
	private $messages = array();
	public function __construct($width, $height, $numberBombs)
	{
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				$this->field[$h][$w] = new EmptyCell();
			}
		}		
		for	($n = $numberBombs; $n!= 0; ){
			$h = rand(0, $height - 1);
			$w = rand(0, $width - 1);
			if ($this->field[$h][$w] instanceof EmptyCell) {
				$this->field[$h][$w] = new BombCell();
				$n--;
			}
		}
		// count bomb around cell
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				if ($this->field[$h][$w] instanceof EmptyCell) {
					$sumScan = 0;
					foreach (range(-1, 1) as $hScan) {
						foreach (range(-1, 1) as $wScan) {
							if (	
								isset($this->field[$h + $hScan][$w + $wScan]) &&
								$this->field[$h + $hScan][$w + $wScan] instanceof BombCell
							) {
								$sumScan++;
							}
						}
					}
					$this->field[$h][$w]->countBombAround = $sumScan;
				}
			}
		}
	}
	public function isBomb($w, $h)
	{
		if ($field[$h][$w] instanceof BombCell) {
			return true;
		}
		return false;
	}
	public function getMessages()
	{
		return $this->messages;
	}
	public function clearMessages()
	{
		$this->messages = array();
		return $messages;
	}	
	public function getField()
	{
		return $this->field;
	}
}