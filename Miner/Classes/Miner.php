<?php

namespace Miner\classes;

class Miner
{
	private $field = array(array());
	private $messages = array();
	public function __construct($width, $height, $numberBombs)
	{
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				$this->field[$h][$w] = '[%]';
			}
		}
	}
	public function getMessages()
	{
		return $this->messages;
	}
	public function clearMessages()
	{
		$this->messages = array();
		return $messages;
	}	
	public function getField()
	{
		return $this->field;
	}
}