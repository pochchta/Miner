<?php

namespace Miner\classes;

class Miner
{
	const NO_BOMB = ' ';
	const BOMB = 'B';
	private $field = array(array());
	private $messages = array();
	public function __construct($width, $height, $numberBombs)
	{
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				$this->field[$h][$w] = self::NO_BOMB;
			}
		}
		for	($n = $numberBombs; $n!= 0; ){
			$cell = & $this->field[rand(0, $height - 1)][rand(0, $width - 1)];
			if ($cell != self::BOMB) {
				$cell = self::BOMB;
				$n--;
			}
		}
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				if ($this->field[$h][$w] == self::NO_BOMB) {	
					$sumScan = 0;
					foreach (range(-1, 1) as $hScan) {
						foreach (range(-1, 1) as $wScan) {
							if (
								isset($this->field[$h + $hScan][$w + $wScan]) &&
								$this->field[$h + $hScan][$w + $wScan] == self::BOMB
							) {
								$sumScan++;
							}
						}
					}
					if ($sumScan != 0 ) {
						$this->field[$h][$w] = $sumScan;
					}
				}
			}
		}
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