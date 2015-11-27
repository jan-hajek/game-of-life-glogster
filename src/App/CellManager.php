<?php
namespace App;

class CellManager
{
	/**
	 * @param array $liveCells
	 * @param int $x
	 * @param int $y
	 * @param int $worldWidth
	 * @return State
	 */
	public static function getNewCellStateForLiveCell(array &$liveCells, $x, $y, $worldWidth)
	{
		if (!isset($liveCells[$x][$y])) {
			throw new \RuntimeException("cell x:{$x}, y:{$y} is dead");
		}
		$count = 0;
		$species = $liveCells[$x][$y];

		// iterate living neighbours of cell excluding neighbours beyond edges and cell itself
		for ($a = max($x - 1, 0); $a <= min($x + 1, $worldWidth - 1); $a++) {
			for ($b = max($y - 1, 0); $b <= min($y + 1, $worldWidth - 1); $b++) {
				if ($a == $x && $b == $y) {
					continue;
				}
				if (isset($liveCells[$a][$b])) {
					$neighborSpecies = $liveCells[$a][$b];
					if ($neighborSpecies == $species) {
						$count++;
					}
				}
			}
		}

//		If there are two or three organisms of the same type living in the elements
//		surrounding an organism of the same, type then it may survive.
		if ($count == 2 || $count == 3) {
			return State::letLive();

//		– If there are less than two organisms of one type surrounding one of the same type
//		then it will die due to isolation.
//		– If there are four or more organisms of one type surrounding one of the same type
//		then it will die due to overcrowding.
		} else {
			return State::terminate();
		}
	}

	/**
	 * @param array $liveCells
	 * @param int $x
	 * @param int $y
	 * @return State
	 */
	public static function getNewCellStateForDeadCell(array &$liveCells, $x, $y, $worldWidth)
	{
		if (isset($liveCells[$x][$y])) {
			throw new \RuntimeException("cell x:{$x}, y:{$y} is alive");
		}
		$speciesCount = [];

		// iterate live neighbours of cell excluding neighbours beyond edges and cell itself
		for ($a = max($x - 1, 0); $a <= min($x + 1, $worldWidth - 1); $a++) {
			for ($b = max($y - 1, 0); $b <= min($y + 1, $worldWidth - 1); $b++) {
				if ($a == $x && $b == $y) {
					continue;
				}
				// count types of living neighbours
				if (isset($liveCells[$a][$b])) {
					$neighborSpecies = $liveCells[$a][$b];
					if (!isset($speciesCount[$neighborSpecies])) {
						$speciesCount[$neighborSpecies] = 1;
					} else {
						$speciesCount[$neighborSpecies]++;
					}
				}
			}
		}
		foreach ($speciesCount as $species => $count) {
//			If there are exactly three organisms of one type surrounding one element, they may
//			give birth into that cell. The new organism is the same type as its parents. If this
//			condition is true for more then one species on the same element then species type
//			for the new element is chosen randomly.
			if ($count == 3) {
				return State::resurrect($species);
			}
		}
		return State::letRest();
	}


	/**
	 * @param array $liveCells
	 * @param int $worldWidth
	 * @return array
	 */
	public static function getDeadNeighbours(array &$liveCells, $worldWidth)
	{
		$deadCells = [];

		// iterate living cells and find dead neighbours
		// only inside gaming filed
		foreach ($liveCells as $x => $cols) {
			foreach ($cols as $y => $species) {
				for ($a = max($x - 1, 0); $a <= min($x + 1, $worldWidth - 1); $a++) {
					for ($b = max($y - 1, 0); $b <= min($y + 1, $worldWidth - 1); $b++) {
						if ($a == $x && $b == $y) {
							continue;
						}
						if (!isset($liveCells[$a][$b])) {
							$deadCells[$a][$b] = null;
						}
					}
				}
			}
		}
		return $deadCells;
	}
}