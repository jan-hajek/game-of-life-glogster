<?php
namespace App;

class World
{
	/**
	 * @param int $worldWidth
	 * @param int $iterations
	 * @param array $generation
	 * @return array
	 */
	public static function run($worldWidth, $iterations, array $generation)
	{
		for ($i = 1; $i <= $iterations; $i++) {
			$generation = self::iteration($worldWidth, $generation);
		}
		return $generation;
	}

	/**
	 * @param int $worldWidth
	 * @param array $generation
	 * @return array
	 */
	public static function iteration($worldWidth, array $generation)
	{
		$newGeneration = [];
		$deadNeighbours = CellManager::getDeadNeighbours($generation, $worldWidth);
		foreach ($generation as $x => $row) {
			foreach ($row as $y => $species) {
				$state = CellManager::getNewCellStateForLiveCell($generation, $x, $y, $worldWidth);
				if ($state && $state->getState() == State::LET_LIVE) {
					$newGeneration[$x][$y] = $species;
				}
			}
		}
		foreach ($deadNeighbours as $x => $row) {
			foreach ($row as $y => $species) {
				$state = CellManager::getNewCellStateForDeadCell($generation, $x, $y, $worldWidth);
				if ($state && $state->getState() == State::RESURRECT) {
					$newGeneration[$x][$y] = $state->getType();
				}
			}
		}
		return $newGeneration;
	}
}