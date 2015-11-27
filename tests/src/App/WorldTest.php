<?php
namespace App;


class WorldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var array
	 */
	private $iterations = [
		[
			["xx", "xx", null, null, null],
			["xx", "xx", "yy", "yy", null],
			["xx", null, "yy", null, "xx"],
			[null, null, null, "xx", "xx"],
			[null, null, null, null, null],
		],
		[
			["xx", "xx", null, null, null],
			[null, null, "yy", "yy", null],
			["xx", "xx", "yy", "yy", "xx"],
			[null, null, null, "xx", "xx"],
			[null, null, null, null, null],
		],
		[
			[null, null, null, null, null],
			[null, null, "yy", "yy", null],
			[null, null, "yy", "yy", "xx"],
			[null, null, null, "xx", "xx"],
			[null, null, null, null, null],
		]
];

	/**
	 * @test
	 */
	public function runTheWorld()
	{
		$iterations = $this->iterations;
		$input = array_shift($iterations);
		foreach ($iterations as $expected) {
			$actual = World::iteration(5, $this->cleanArray($input));
			$this->assertEquals($this->cleanArray($expected), $actual);
			$input = $expected;
		}
	}

	/**
	 * remove item without value
	 * @param array $array
	 * @return array
	 */
	private function cleanArray(array $array)
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
}
