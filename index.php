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
	header("Location: /");
}
if ($_POST['newGame'] == 'get') {
	MinerController::newMiner();
	MinerController::setSettingsToCookie();
	MinerWriter::printJsonField(MinerController::getMiner());
} elseif (! empty($_POST['coord'])) {
	MinerController::isBomb($_POST['coord']);
	MinerWriter::printJsonField(MinerController::getMiner());
} elseif($_POST['record'] == 'get') {
	RecordWriter::printJsonRecord(MinerController::getMiner()->getRecord());
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

// v(MinerController::getMiner()->getRecord()->getPropArray());
