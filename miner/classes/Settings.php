<?php

namespace miner\classes;

class Settings
{
	const MIN_WIDTH = 1;
	const MAX_WIDTH = 30;
	const MIN_HEIGHT = 1;
	const MAX_HEIGHT = 16;
	const MIN_NUMBER_BOMBS = 0;
	const MAX_NUMBER_BOMBS = 100;
	const MAX_NAME_LEN = 20;
	const LEVEL1 = '10x10x10';	// WxHxB
	const LEVEL2 = '16x16x40';
	const LEVEL3 = '30x16x100';
	private $width = 10;
	private $height = 10;
	private $numberBombs = 10;
	private $level = self::LEVEL1;
	private $name = 'unknown';
	public function setSettings(array $settings = array())
	{
		try {
			if (isset($settings['width'])) {
				$this->filterFieldSettings($settings);
				$this->width = $settings['width'];
				$this->height = $settings['height'];
				$this->numberBombs = $settings['numberBombs'];
				$this->level = $this->width.'x'.$this->height.'x'.$this->numberBombs;
			}
			if (isset($settings['name'])) {
				$this->filterNameSettings($settings);
				$this->name = $settings['name'];
			}
		} catch (\Exception $e) {
			throw $e;
		}
	}
	private function filterFieldSettings(array $settings)
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
			return;
		}		
		throw new \Exception('Недопустимые параметры поля');
	}
	private function filterNameSettings(array $settings)
	{
		if (strlen($settings['name']) > self::MAX_NAME_LEN) {
			throw new \Exception('Слишком длинное имя');
		}
	}
	public function getArraySettings()
	{
		return array(
			'width' => $this->width,
			'height' => $this->height,
			'numberBombs' => $this->numberBombs,
			'level' => $this->level,
			'name' => $this->name
		);
	}
	public function getLevel()
	{
		switch ($this->level) {
			case self::LEVEL1: return 1;
			case self::LEVEL2: return 2;
			case self::LEVEL3: return 3;
		}
		return 0;
	}
}