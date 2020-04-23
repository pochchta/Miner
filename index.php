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
MinerController::load();
// if (isset($POST['newGame'])) MinerController::newGame();
// elseif (isset($POST['isBomb'])) MinerController::isBomb($POST['isBomb']);
// $message = MinerController::getMessage();
// $field = MinerController::getField();
// include('templates'.DIRECTORY_SEPARATOR.'miner.php');
