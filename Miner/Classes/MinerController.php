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
	private $miner = NULL;
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
		$ctrl = self::getCtrl();
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
			if (
				$ctrl->width != $w ||
				$ctrl->height != $h ||
				$ctrl->numberBombs != $n
			) {
				$ctrl->width = $w;
				$ctrl->height = $h;
				$ctrl->numberBombs = $n;
				self::newMiner();
			}
		}
	}
	public static function getSettings()
	{
		$ctrl = self::getCtrl();
		return array(
			"width" => $ctrl->width,
			"height" => $ctrl->height,
			"numberBombs" => $ctrl->numberBombs
		);
	}
	private static function getMiner()
	{
		$ctrl = self::getCtrl();
		if ($ctrl->miner == NULL) {
			self::newMiner();
		}
		return $ctrl->miner;
	}
	// private static function setMiner(Miner $miner)
	// {
	// 	$ctrl = self::getCtrl();
	// 	$ctrl->miner = $miner;
	// }	
	public static function newMiner()
	{
		$ctrl = self::getCtrl();
		$ctrl->miner = new Miner($ctrl->width, $ctrl->height, $ctrl->numberBombs);
	}
	public static function isBomb($coord)
	{
		$ctrl = self::getCtrl();
		if (
			(int)$coord['w'] >= 0 &&
			(int)$coord['w'] < $ctrl->width &&
			(int)$coord['h'] >= 0 &&
			(int)$coord['h'] < $ctrl->height
		) {
			return $ctrl->miner->isBomb((int)$coord['w'], (int)$coord['h']);
		}
		return false;
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