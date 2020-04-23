<?php

namespace Miner\classes;

class MinerController
{
	const MIN_WIDTH = 1;
	const MIN_HEIGHT = 1;
	const MIN_NUMBER_BOMBS = 0;
	private static $miner = NULL;
	private static $width = 10;
	private static $height = 10;
	private static $numberBombs = 10;
	private static function getInstance()
	{
		if (isset(self::$miner) == false) {
			self::loadGame();
		}
		return self::$miner;
	}
	private static function setInstance(Miner $miner)
	{
		self::$miner = $miner;
	}	
	public static function newGame()
	{
		self::$miner = new Miner(self::$width, self::$height, self::$numberBombs);
	}
	public static function loadGame()
	{
		if (isset($_SESSION['miner'])) {
			self::$miner = $_SESSION['miner'];
		} else {
			self::newGame();
		}
	}
	public static function saveGame()
	{
		$_SESSION['miner'] = self::getInstance($miner);
	}
	public static function setSettings(array $settings)
	{
		if ((int)$settings['width'] >= self::MIN_WIDTH) {
			self::$width = (int)$settings['width'];
		}
		if ((int)$settings['height'] >= self::MIN_HEIGHT) {
			self::$height = (int)$settings['height'];
		}
		$maxNumberBombs = (self::$width)*(self::$height);
		if ((int)$settings['numberBombs'] >= self::MIN_NUMBER_BOMBS && (int)$settings['numberBombs'] <= $maxNumberBombs) {
			self::$numberBombs = (int)$settings['numberBombs'];
		} elseif (self::MIN_NUMBER_BOMBS < $maxNumberBombs) {
			self::$numberBombs = self::MIN_NUMBER_BOMBS;
		} else {
			self::$numberBombs = $maxNumberBombs;
		}
	}
	public static function isBomb()
	{
			return "I'm function isBomb!";
			// return self::$miner->isBomb();
	}
	public static function getField()
	{
		return self::getInstance()->getField();
	}
	public static function getMessages()
	{
		return self::getInstance()->getMessages();
	}
	public static function clearMessages()
	{
		self::getInstance()->clearMessages();
	}
}