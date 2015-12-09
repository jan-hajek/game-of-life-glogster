<?php
namespace App;

class World
{
	/**
	 * @var CellManager
	 */
	private $cellManager;

	/**
	 * World constructor.
	 * @param CellManager $cellManager
	 */
	public function __construct(CellManager $cellManager)
	{
		$this->cellManager = $cellManager;
	}

	/**
	 * @param WorldState $state
	 * @return WorldState
	 */
	public function run(WorldState $state)
	{
		$generation = $state->generation;
		for ($i = 1; $i <= $state->iterations; $i++) {
			$generation = $this->iteration($generation);
		}
		$lastState = new WorldState();
		$lastState->worldWidth = $state->worldWidth;
		$lastState->iterations = $state->iterations;
		$lastState->speciesCount = $state->speciesCount;
		$lastState->generation = $generation;
		return $lastState;
	}

	/**
	 * @param array $generation
	 * @return array
	 */
	public function iteration(array $generation)
	{
		$newGeneration = [];
		$deadNeighbours = $this->cellManager->getDeadNeighbours($generation);
		foreach ($generation as $x => $row) {
			foreach ($row as $y => $species) {
				$state = $this->cellManager->getCellNewState($generation, $x, $y);
				if ($state->getState() == State::LET_LIVE) {
					$newGeneration[$x][$y] = $species;
				}
			}
		}
		foreach ($deadNeighbours as $x => $row) {
			foreach ($row as $y => $species) {
				$state = $this->cellManager->getCellNewState($generation, $x, $y);
				if ($state->getState() == State::RESURRECT) {
					$newGeneration[$x][$y] = $state->getType();
				}
			}
		}
		return $newGeneration;
	}
}