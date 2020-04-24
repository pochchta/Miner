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

MinerController::loadFromSession();
if (isset($_POST['setSettings'])) {
	MinerController::setSettings($_POST);
} elseif (isset($_POST['newGame'])) {
	MinerController::newMiner();
} elseif (isset($_POST['isBomb'])) {
	MinerController::isBomb($_POST['isBomb']);
}
include('Miner/templates/miner.html');
MinerController::clearMessages();


// print "<pre>";
// var_dump(MinerController::getSettings());
// print "<br>";
// var_dump($_SESSION);
// print "<br></pre>";

// unset($_SESSION['minerCtrl']);