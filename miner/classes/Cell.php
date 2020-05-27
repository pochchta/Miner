<?php

namespace miner\classes;

abstract class Cell
{
	protected $visible = false;
	private $update = true;
	public function setVisible()
	{
		$this->visible = true;
		$this->update = true;
	}
	public function isVisible()
	{
		return $this->visible;
	}
	public function checkUpdate()
	{
		$this->update = false;
	}
	public function isUpdate()
	{
		return $this->update;
	}
	abstract public function __toString();
}