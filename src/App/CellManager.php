<?php
namespace App;

class CellManager
{
	/**
	 * @var int
	 */
	private $worldWidth;

	/**
	 * @param int $worldWidth
	 */
	public function __construct($worldWidth)
	{
		$this->worldWidth = $worldWidth;
	}

	/**
	 * @param array $liveCells
	 * @param int $x
	 * @param int $y
	 * @return State
	 */
	public function getCellNewState(array $liveCells, $x, $y)
	{
		$neighboursCount = $this->getNeighboursCount($liveCells, $x, $y);

		// live cell
		if (isset($liveCells[$x][$y])) {
			$species = $liveCells[$x][$y];
			return $this->getLiveCellNewState($neighboursCount, $species);
		// dead cell
		} else {
			return $this->getDeadCellNewState($neighboursCount);
		}
	}

	/**
	 * @param array $liveCells
	 * @param int $x
	 * @param int $y
	 * @return array
	 */
	private function getNeighboursCount(array $liveCells, $x, $y)
	{
		$count = [];
		// iterate living neighbours of cell excluding neighbours beyond edges and cell itself
		for ($a = max($x - 1, 0); $a <= min($x + 1, $this->worldWidth - 1); $a++) {
			for ($b = max($y - 1, 0); $b <= min($y + 1, $this->worldWidth - 1); $b++) {
				if ($a == $x && $b == $y) {
					continue;
				}
				// count types of living neighbours
				if (isset($liveCells[$a][$b])) {
					$neighborSpecies = $liveCells[$a][$b];
					if (!isset($count[$neighborSpecies])) {
						$count[$neighborSpecies] = 1;
					} else {
						$count[$neighborSpecies]++;
					}
				}
			}
		}
		return $count;
	}

	/**
	 * @param array $neighboursCount
	 * @param string $cellSpecies
	 * @return State
	 */
	private function getLiveCellNewState(array $neighboursCount, $cellSpecies)
	{
		$neighborSpeciesCount = isset($neighboursCount[$cellSpecies]) ? $neighboursCount[$cellSpecies] : 0;
		// If there are two or three organisms of the same type living in the elements
		// surrounding an organism of the same, type then it may survive.
		if ($neighborSpeciesCount == 2 || $neighborSpeciesCount == 3) {
			return State::letLive();
		}
		//		– If there are less than two organisms of one type surrounding one of the same type
		//		then it will die due to isolation.
		//		– If there are four or more organisms of one type surrounding one of the same type
		//		then it will die due to overcrowding.
		return State::terminate();
	}

	/**
	 * @param array $neighboursCount
	 * @return State
	 */
	private function getDeadCellNewState(array $neighboursCount)
	{
		foreach ($neighboursCount as $species => $count) {
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
	 * @return array
	 */
	public function getDeadNeighbours(array $liveCells)
	{
		$deadCells = [];

		// iterate living cells and find dead neighbours
		// only inside gaming filed
		foreach ($liveCells as $x => $cols) {
			foreach ($cols as $y => $species) {
				for ($a = max($x - 1, 0); $a <= min($x + 1, $this->worldWidth - 1); $a++) {
					for ($b = max($y - 1, 0); $b <= min($y + 1, $this->worldWidth - 1); $b++) {
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