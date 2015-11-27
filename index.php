<?php
require_once __DIR__ . '/src/bootstrap.php';

use App\World;

$input = [
	["xx", "xx", null, null, null],
	["xx", "xx", "yy", "yy", null],
	["xx", null, "yy", null, "xx"],
	[null, null, null, "xx", "xx"],
	[null, null, null, null, null],
];

function cleanArray(array $array)
{
	$newArray = [];
	foreach ($array as $x => $row) {
		foreach ($row as $y => $value) {
			if ($value) {
				$newArray[$x][$y] = $value;
			}
		}
	}
	return $newArray;
}

$actual = World::run(100, 10000, cleanArray($input));