<?php

namespace Miner\classes;

class Miner
{
	private $field = array(array());
	private $messages = array();
	public function __construct($width, $height, $numberBombs)
	{
		for	($n = $numberBombs; $n!= 0; ){
			$h = rand(0, $height - 1);
			$w = rand(0, $width - 1);
			if ($this->field[$h][$w] == NULL) {
				$this->field[$h][$w] = new BombCell();
				$n--;
			}

// print "<pre>";
// print $w.' / '.$h."\n";
// var_dump($this->field);
// for ($h = 0; $h < $height; $h++){
// 	for ($w = 0; $w < $width; $w++){
// 		print '['.$this->field[$h][$w].']';
// 	}
// 	print '<br>';
// }
// print "</pre>";		

		}
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				if ($this->field[$h][$w] == NULL) {
					$this->field[$h][$w] = new EmptyCell();
				}
			}
		}		

print "<pre>";
print $w.' / '.$h."\n";
// var_dump($this->field);
for ($h = 0; $h < $height; $h++){
	for ($w = 0; $w < $width; $w++){
		print '['.$this->field[$h][$w].']';
	}
	print '<br>';
}
print "</pre>";	
		
		// count bomb around cell
		for ($h = 0; $h < $height; $h++){
			for ($w = 0; $w < $width; $w++){
				if ($this->field[$h][$w] instanceof EmptyCell) {
					$sumScan = 0;
					if ($w==0 && $h==0)
					foreach (range(-1, 1) as $hScan) {
						foreach (range(-1, 1) as $wScan) {
// print "<pre>";
// print "{$hScan} / {$wScan}\n";
// if ($w==0 && $h==0) 
// 	if ($this->field[$h + $hScan][$w + $wScan] != NULL) 
// 		var_dump($this->field[$h + $hScan][$w + $wScan]);
// print "</pre>";

							if (	
								isset($this->field[$h + $hScan][$w + $wScan]) &&
								$this->field[$h + $hScan][$w + $wScan] instanceof BombCell
							) {
								$sumScan++;
							}
						}
					}
					$this->field[$h][$w]->countBombAround = $sumScan;
				}
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