<?php

namespace miner\classes;

class RecordWriter
{
	// const NUMBER_TABLE_ROWS = 10;
	public static function printTable(array $collection = array())
	{
		$table = array();
		$table[] = array(
			'number' => '№',
			'name' => 'Имя',
			'level' => 'Сложность',
			'counterHelp' => 'Помощь',
			'time' => 'Время'
		);
		foreach ($collection as $key => $record) {
			$row = $record->getPropArray();
			$row['number'] = $key + 1;
			if (! isset($row['level'])) {
				$row['level'] = $row['width'].'x'.$row['height'].'x'.$row['numberBombs'].' (ШхВхБ)';
			}
			foreach ($table[0] as $name => $value) {
				if (! isset($row[$name])) $row[$name] = '&nbsp;';
			}
			$table[] = $row;
		}
		print '<div class="table">';
		foreach ($table[0] as $name => $value) {
			print '<div class="column">';
			foreach ($table as $nRow => $row) {
				print '<div class="row">';
				print $row[$name];
				print '</div>';
			}
			print '</div>';
		}
		print '</div>';
	}
}