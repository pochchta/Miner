<?php

namespace miner\classes;

class MinerController
{
	const MIN_WIDTH = 1;
	const MIN_HEIGHT = 1;
	const MIN_NUMBER_BOMBS = 0;
	const MAX_WIDTH = 30;
	const MAX_HEIGHT = 16;
	const MAX_NUMBER_BOMBS = 100;
	const SESSION_NAME = 'minerSession';
	const COOKIE_NAME = 'settings';
	const COOKIE_TIME = 2592000;	// 60 * 60 * 24 * 30 = month
	private static $miner = NULL;
	private static $width = 10;
	private static $height = 10;
	private static $numberBombs = 10;
	private function __construct()
	{
	}
	public static function loadMinerFromSession()
	{
		if (isset($_SESSION[self::SESSION_NAME])) {
			self::$miner = $_SESSION[self::SESSION_NAME];
			$settings = self::$miner->getSettings();
			self::saveSettings($settings);
		} else {
			MinerController::loadSettingsFromCookie();
			self::$miner = self::getMiner();
		}
	}
	public static function saveMinerToSession()
	{
		$_SESSION[self::SESSION_NAME] = self::$miner;
	}
	private static function filterSettings(array $settings)
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
			$n < $w * $h
		) {
			return true;
		}		
		return false;
	}
	private static function changedSettings(array $settings)
	{
		$w = (int)$settings['width'];
		$h = (int)$settings['height'];
		$n = (int)$settings['numberBombs'];
		if (
			self::$width != $w ||
			self::$height != $h ||
			self::$numberBombs != $n
		) {
			return true;
		}
		return false;
	}
	private static function saveSettings(array $settings)
	{
		$w = (int)$settings['width'];
		$h = (int)$settings['height'];
		$n = (int)$settings['numberBombs'];
		self::$width = $w;
		self::$height = $h;
		self::$numberBombs = $n;
	}
	public static function setSettings(array $settings)
	{
		if (self::filterSettings($settings)) {
			if (self::changedSettings($settings)) {
				self::saveSettings($settings);
				self::setSettingsToCookie();
				self::newMiner();
			}
		}
	}
	public static function getSettings()
	{
		return array(
			"width" => self::$width,
			"height" => self::$height,
			"numberBombs" => self::$numberBombs
		);
	}
	public static function setSettingsToCookie()
	{
		$settings = serialize([
			'width' => self::$width,
			'height' => self::$height,
			'numberBombs' => self::$numberBombs
		]);
		setcookie(self::COOKIE_NAME, $settings, time()+self::COOKIE_TIME);
	}
	public static function loadSettingsFromCookie()
	{
		$value = $_COOKIE[self::COOKIE_NAME];
		$settings = unserialize($value);
		if (is_array($settings)) {
			if (self::filterSettings($settings)) {
				if (self::changedSettings($settings)) {
					self::saveSettings($settings);
				}
			}		
		}
	}
	public static function getMiner()
	{
		if (self::$miner == NULL) {
			self::newMiner();
		}
		return self::$miner;
	}
	public static function newMiner()
	{
		self::$miner = new Miner(self::$height, self::$width, self::$numberBombs);
	}	
	public static function isBomb($coord)
	{
		if (is_string($coord)) {
			$coord = explode('_', $coord);
			$w = (int)$coord[2];
			$h = (int)$coord[1];
			if (
				$w >= 0 &&
				$w < self::$width &&
				$h >= 0 &&
				$h < self::$height
			) {
				$miner = self::getMiner();
				$miner->isBomb((int)$h, (int)$w);
			}
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