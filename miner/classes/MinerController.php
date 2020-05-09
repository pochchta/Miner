<?php

namespace miner\classes;

class MinerController
{
	const SESSION_NAME = 'minerSession';
	const COOKIE_NAME = 'settings';
	const COOKIE_TIME = 2592000;	// 60 * 60 * 24 * 30 = month
	private static $miner;
	private static $settings;
	private static $messages = array();
	private function __construct()
	{
	}
	public static function loadMinerFromSession()
	{
		if (isset($_SESSION[self::SESSION_NAME])) {
			self::$miner = $_SESSION[self::SESSION_NAME];
			if (self::$miner instanceof Miner) {
				try {
					self::getSettings()->setSettings(self::$miner->getSettings());
				} catch (\Exception $e) {
					self::$messages[] = 'Session ошибка настроек: '.$e->getMessage();
				}
			}
		} else {
			self::$miner = self::getMiner();
		}
	}
	public static function saveMinerToSession()
	{
		$_SESSION[self::SESSION_NAME] = self::$miner;
	}
	public static function setSettings(array $arraySettings)
	{
		try {
			self::getSettings()->setSettings($arraySettings);
			self::setSettingsToCookie();
			self::newMiner();
			self::$messages[] = 'Настройки изменены, '.self::getSettings()->getArraySettings()['name'];
		} catch (\Exception $e) {
			self::$messages[] = 'Пользовательская ошибка настроек: '.$e->getMessage();
		}	
	}
	public static function getSettings()
	{
		if (self::$settings == NULL) {
			self::$settings = new Settings();
		}
		return self::$settings;
	}
	public static function setSettingsToCookie()
	{
		$serialSettings = serialize(self::getSettings()->getArraySettings());
		setcookie(self::COOKIE_NAME, $serialSettings, time()+self::COOKIE_TIME);
	}
	public static function loadSettingsFromCookie()
	{
		$serialSettings = $_COOKIE[self::COOKIE_NAME];
		$arraySettings = unserialize($serialSettings);
		if (is_array($arraySettings)) {
			try {
				self::getSettings()->setSettings($arraySettings);
				self::$messages[] = 'Привет, '.self::getSettings()->getArraySettings()['name'];
			} catch (\Exception $e) {
				self::$messages[] = 'Cookie ошибка настроек: '.$e->getMessage();
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
		$s = self::getSettings()->getArraySettings();
		self::$miner = new Miner($s['height'], $s['width'], $s['numberBombs']);
	}	
	public static function isBomb($coord)
	{
		if (is_string($coord)) {
			$arraySettings = self::getSettings()->getArraySettings();
			$coord = explode('_', $coord);
			$help = false;
			if ($coord[0] == 'help') $help = true;
			$w = (int)$coord[2];
			$h = (int)$coord[1];
			if (
				$w >= 0 &&
				$w < $arraySettings['width'] &&
				$h >= 0 &&
				$h < $arraySettings['height']
			) {
				$miner = self::getMiner();
				$miner->isBomb((int)$h, (int)$w, $help);
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
		return array_merge(
			self::$messages,
			self::getMiner()->getMessages()
		);
	}
	public static function clearMessages()
	{
		self::getMiner()->clearMessages();
	}
}