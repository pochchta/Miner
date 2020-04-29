<?php

namespace Miner\classes;

class Miner
{
	private $field = array(array());
	private $messages = array();
	private $endGame = false;
	private $startGame = false;
	private $countEmptyCell;
	private $width;
	private $height;
	private $numberBombs;
	const MESSAGE_LOSE_GAME = 'Вы проиграли.';
	const MESSAGE_WIN_GAME = 'Поздравляем. Вы победили.';
	const MESSAGE_END_GAME = 'Игра закончена. Начните новую.';
	public function __construct($height, $width, $numberBombs)
	{
		$this->width = $width;
		$this->height = $height;
		$this->numberBombs = $numberBombs;
		$this->countEmptyCell = $width * $height - $numberBombs;
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
		if ($this->isEndGame() == false) {
			$cell = $this->field[$h][$w];
			while ($cell instanceof BombCell && $this->startGame == false) {
				self::__construct($this->height, $this->width, $this->numberBombs);
				$cell = $this->field[$h][$w];
			}
			if ($cell instanceof BombCell) {
				$cell->exploded = true;
				$this->endGame = true;
				$this->messages[] = self::MESSAGE_LOSE_GAME;
			}
			if ($this->isEndGame() == false) {
				if ($cell instanceof Cell && $cell->visible == false) {
					$this->startGame = true;
					$cell->visible = true;
					$this->countEmptyCell--;
					$this->openBesideCell($h, $w);
				}
			}
		}
		if ($this->countEmptyCell == 0) {
			$this->endGame = true;
			$this->messages[] = self::MESSAGE_WIN_GAME;
		}		
		if ($this->isEndGame() == true) {
			$this->messages[] = self::MESSAGE_END_GAME;
		}	
	}
	private function openBesideCell($h, $w)
	{
		foreach (range(-1, 1) as $hScan) {
			foreach (range(-1, 1) as $wScan) {
				$hNew = $h + $hScan;
				$wNew = $w + $wScan;
				if (
					isset($this->field[$hNew][$wNew]) &&
					$this->field[$hNew][$wNew] instanceof EmptyCell &&
					$this->field[$hNew][$wNew]->visible == false
				) {
					$this->field[$hNew][$wNew]->visible = true;
					$this->countEmptyCell--;
					if ($this->field[$hNew][$wNew]->countBombAround == 0) {
						$this->openBesideCell($hNew, $wNew);
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
	public function isEndGame()
	{
		return $this->endGame;
	}
}