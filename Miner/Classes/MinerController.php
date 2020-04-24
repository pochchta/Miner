<?php

namespace Miner\classes;

class MinerController
{
	const MIN_WIDTH = 1;
	const MIN_HEIGHT = 1;
	const MIN_NUMBER_BOMBS = 0;
	const MAX_WIDTH = 30;
	const MAX_HEIGHT = 16;
	const MAX_NUMBER_BOMBS = 100;	
	private static $ctrl = NULL;
	private static $miner = NULL;
	private $width = 10;
	private $height = 10;
	private $numberBombs = 10;
	private function __construct()
	{
	}
	private static function getCtrl()
	{
		if (self::$ctrl == NULL) {
			self::loadFromSession();
		}
		return self::$ctrl;
	}
	public static function loadFromSession()
	{
		if (isset($_SESSION['minerCtrl'])) {
			self::$ctrl = $_SESSION['minerCtrl'];
		} else {
			self::$ctrl = new self();
			$_SESSION['minerCtrl'] = self::$ctrl;
		}
	}
	// public static function saveToSession()
	// {
	// 	$_SESSION['minerCtrl'] = self::getCtrl();
	// }
	public static function setSettings(array $settings)
	{
		$w = (int)$settings['width'];
		$h = (int)$settings['height'];
		$n = (int)$settings['numberBombs'];
		if (
			$w >= self::MIN_WIDTH &&
			$w <= self::MAX_WIDTH &&
			$h >= self::MIN_HEIGHT &&
			$h <= self::MAX_HEIGHT &&
			$n >= self::MIN_NUMBER_BOMBS &&
			$n <= self::MAX_NUMBER_BOMBS &&
			$n <= $w * $h
		) {
			self::$ctrl->width = $w;
			self::$ctrl->height = $h;
			self::$ctrl->numberBombs = $n;
		}
	}
	public static function getSettings()
	{
		return array(
			"width" => self::$ctrl->width,
			"height" => self::$ctrl->height,
			"numberBombs" => self::$ctrl->numberBombs
		);
	}
	private static function getMiner()
	{
		if (self::$miner == NULL) {
			self::newMiner();
		}
		return self::$miner;
	}
	// private static function setMiner(Miner $miner)
	// {
	// 	self::$miner = $miner;
	// }	
	public static function newMiner()
	{
		self::$miner = new Miner(self::$ctrl->width, self::$ctrl->height, self::$ctrl->numberBombs);
	}
	public static function isBomb()
	{
			return "I'm function isBomb!";
			// return self::$miner->isBomb();
	}
	public static function getField()
	{
		return self::getMiner()->getField();
	}
	public static function getMessages()
	{
		return self::getMiner()->getMessages();
	}
	public static function clearMessages()
	{
		self::getMiner()->clearMessages();
	}
}