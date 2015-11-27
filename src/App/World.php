<?php
namespace App;

class World
{
	/**
	 * @param WorldState $state
	 * @return WorldState
	 */
	public static function run(WorldState $state)
	{
		$generation = $state->generation;
		for ($i = 1; $i <= $state->iterations; $i++) {
			$generation = self::iteration($state->worldWidth, $generation);
		}
		$lastState = new WorldState();
		$lastState->worldWidth = $state->worldWidth;
		$lastState->iterations = $state->iterations;
		$lastState->speciesCount = $state->speciesCount;
		$lastState->generation = $generation;
		return $lastState;
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