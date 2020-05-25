<?php

namespace miner\classes;

$autoload = function ($path) {
	if (preg_match('/\\\\/', $path)) {
		$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
	}
	$path = __DIR__.DIRECTORY_SEPARATOR.$path;
	if (file_exists("{$path}.php")) {
		require_once("{$path}.php");
	} 
};
\spl_autoload_register($autoload);
session_start();

MinerController::loadSettingsFromCookie();
MinerController::loadMinerFromSession();
if (isset($_POST['setSettings'])) {
	MinerController::setSettings($_POST);
}
if (! empty($_POST['newGame'])) {
	MinerController::newMiner();
	MinerController::setSettingsToCookie();
}
if (! empty($_POST['coord'])) {
	MinerController::isBomb($_POST['coord']);
	MinerWriter::printJsonField(MinerController::getMiner());
} else {
	include(__DIR__.'/miner/templates/miner.html');
}
MinerController::clearMessages();
MinerController::saveMinerToSession();

// ---------------------------------------------------
// function v($test)
// {
// 	print "<pre>";
// 	var_dump($test);
// 	print "</pre>";
// }

// v(MinerController::getMiner());
