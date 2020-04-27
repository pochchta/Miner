<?php

namespace Miner\classes;

class MinerWriter
{
	public static function printField($field, $endGame = false)
	{
		print '<div class=field>';
		foreach ($field as $h => $row) {
			print '<div class=row>';
			foreach ($row as $w => $cell) {
				$class = 'cell ';
				if ($cell instanceof Cell) {
					if ($cell->visible == false && $endGame == false) {
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
					print "<div class='{$class}' id='cell_{$h}_{$w}'>$cell</div>";
				}
			}
			print '</div>';
		}
		print '</div>';
	}
}