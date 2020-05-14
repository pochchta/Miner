<?php

namespace miner\classes;
use PDO;

class RecordMapper
{
	private $pdo;
	private function getPDO()
	{
		if ($this->pdo == NULL) {
			$dsn = 'mysql:host=localhost;port=3306;dbname=miner;charset=utf8';
			$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS
			];
			$this->pdo = new PDO($dsn, 'testuser', 'pass', $options);
		}
		return $this->pdo;
	}
	public function findAll($start = 0, $limit = 10)
	{
		$pdo = $this->getPDO();
		$stmt = $pdo->prepare("SELECT name, width, height, numberBombs, counterHelp FROM records LIMIT ?, ?");
		$stmt->bindValue(1, $start, PDO::PARAM_INT);
		$stmt->bindValue(2, $limit, PDO::PARAM_INT);
		$stmt->execute();
		$collection = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'miner\classes\Record', [array()]);
		return $collection;
	}
	public function insert(Record $record)
	{
		$pdo = $this->getPDO();
		$stmt = $pdo->prepare("INSERT INTO records(name, width, height, numberBombs, counterHelp) VALUES(?, ?, ?, ?, ?)");
		$stmt->execute(array_values($record->getPropArray()));
	}
}