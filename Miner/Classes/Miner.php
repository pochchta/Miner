<?php

namespace Miner\classes;

class Miner
{
	private $field = array(array());
	private $messages = array();
	private $endGame = false;
	public function __construct($height, $width, $numberBombs)
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
	public function isBomb($h, $w)
	{		
		$cell = $this->field[$h][$w];
		if ($cell instanceof Cell) {
			$cell->visible = true;
		}
		if ($cell instanceof BombCell) {
			$cell->exploded = true;
			$this->endGame = true;
			$this->messages[] = 'Вы проиграли';
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
	public function isEndGame()
	{
		return $this->endGame;
	}
}