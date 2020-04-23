<?php

namespace Miner\classes;

$autoload = function ($path) {
	if (preg_match('/\\\\/', $path)) {
		$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
	}
	if (file_exists("{$path}.php")) {
		require_once("{$path}.php");
	} 
};
\spl_autoload_register($autoload);
session_start();

// const MIN_WIDTH = 1;
// print "<pre>";
// var_dump((int)$settings['width'] >= MIN_WIDTH);
// print "<br>";
// var_dump((int)NULL >= 1);
// print "<br></pre>";

MinerController::loadGame();
if (isset($_POST['newGame'])) {
	MinerController::setSettings($_POST);
	MinerController::newGame();
} elseif (isset($_POST['isBomb'])) {
	MinerController::isBomb($_POST['isBomb']);
}
MinerController::saveGame();
include('Miner'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'miner.html');
MinerController::clearMessages();
