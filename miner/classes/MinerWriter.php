<?php

namespace miner\classes;

class MinerWriter
{
	const MAX_TIME_GAME = 999;
	public static function printField(Miner $miner)
	{
		print '<div class=field>';
		foreach ($miner->getField() as $h => $row) {
			print '<div class=row>';
			foreach ($row as $w => $cell) {
				$class = 'cell ';
				if ($cell instanceof Cell) {
					if ($cell->visible == false && $miner->isEndGame() == false) {
						$class .= 'notVisible';
					} elseif ($cell instanceof BombCell) {
						if ($cell->exploded) {
							$class .= 'explodedBomb';
						} else {
							$class .= 'bomb';
						}
					} elseif ($cell instanceof EmptyCell) {
						if ($cell->countBombAround > 0) {
							$class .= 'numberBombs';
						} else {
							$class .= 'notBomb';
						}
					} 
					print "<div class='{$class}' id='_{$h}_{$w}'>$cell</div>";
				}
			}
			print '</div>';
		}
		print '</div>';
	}
	public static function printTimeGame(Miner $miner)
	{
		if ($miner->getTime() == 0) {
			print '0';
			return;
		}
		if ($miner->getTime() > self::MAX_TIME_GAME) {
			print self::MAX_TIME_GAME;
			return;
		}
		print $miner->getTime();
	}
}