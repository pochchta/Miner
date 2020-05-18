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
	const MIN_CELL_SIZE = 1;
	const MAX_CELL_SIZE = 100;
	const MAX_NAME_LEN = 30;
	private $width = 10;
	private $height = 10;
	private $numberBombs = 10;
	private $cellSize = 15;
	private $name = 'unknown';
	public function setSettings(array $settings = array())
	{
		try {
			if (isset($settings['width'])) {
				$this->filterFieldSettings($settings);
				$this->width = $settings['width'];
				$this->height = $settings['height'];
				$this->numberBombs = $settings['numberBombs'];
			}
			if (isset($settings['cellSize'])) {
				$this->filterSizeSettings($settings);
				$this->cellSize = $settings['cellSize'];
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
	private function filterSizeSettings(array $settings)
	{
		$s = (int)$settings['cellSize'];
		if (
			$s >= self::MIN_CELL_SIZE &&
			$s <= self::MAX_CELL_SIZE
		) {
			return;
		}
		throw new \Exception('Недопустимые размеры ячеек поля');
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
			'cellSize' => $this->cellSize,
			'name' => $this->name
		);
	}
}