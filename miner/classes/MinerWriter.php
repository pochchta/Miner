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
				$class = self::elemCellClass($cell, $miner);				
				print "<div class='{$class}' id='_{$h}_{$w}'>$cell</div>";
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
	public static function printJsonField(Miner $miner)
	{
		$output[0] = self::getStateGame($miner);
		$output = array_merge(
			$output,
			self::getUpdateCell($miner)
		);
		print json_encode($output);
	}
	public static function printJsonState(Miner $miner)
	{
		$output[0] = self::getStateGame($miner);
		print json_encode($output);
	}
	private static function getStateGame(Miner $miner)
	{
		$output = array(
			'startGame' => $miner->isStartGame(),
			'endGame' => $miner->isEndGame(),
			'startTime' => $miner->getStartTime(),
			'endTime' => $miner->getEndTime(),
			'countRemainingBomb' => $miner->getCountRemainingBomb(),
		);
		return $output;
	}
	private static function getUpdateCell(Miner $miner)
	{
		$output = array();
		foreach ($miner->getField() as $h => $row) {
			foreach ($row as $w => $cell) {
				if (
					$cell instanceof Cell && 
					($cell->isUpdate() || $miner->isEndGame())
				) {
					$class = self::elemCellClass($cell, $miner);				
					$cell->checkUpdate();
					$output[] = array(
						'id' => "_{$h}_{$w}",
						'class' => "{$class}",
						'value' => "{$cell}"
					);
				}
			}
		}
		return $output;
	}
	private static function elemCellClass(Cell $cell, Miner $miner)
	{
		$class = 'cell ';
		if ($cell->isVisible() == false && $miner->isEndGame() == false) {
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
		return $class;
	}
}