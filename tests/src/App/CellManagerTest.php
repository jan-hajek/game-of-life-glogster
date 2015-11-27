<?php
namespace App;


class CellManagerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var array
	 */
	private $cells;
	/**
	 * @var int
	 */
	private $worldWidth = 50;

	protected function setUp()
	{
		$this->cells = [
			["xx", "xx", null, null, null],
			["xx", "xx", "yy", "yy", null],
			["xx", null, "yy", null, "xx"],
			[null, null, null, "xx", "xx"],
			[null, null, null, null, null],
		];
	}

	/**
	 * @dataProvider getNeighboursCountForLiveCellProvider
	 * @test
	 * @param int $x
	 * @param int $y
	 * @param State $expected
	 */
	public function getNewCellStateForLiveCell($x, $y, State $expected)
	{
		$this->assertEquals($expected->getState(), \App\CellManager::getNewCellStateForLiveCell($this->cells, $x, $y, $this->worldWidth)->getState());
	}

	/**
	 * @return array
	 */
	public function getNeighboursCountForLiveCellProvider()
	{
		$data = [];
		// x, y, expected state
		$data[] = [0, 0, State::letLive()];
		$data[] = [0, 1, State::letLive()];
		$data[] = [1, 0, State::terminate()];
		$data[] = [1, 1, State::terminate()];
		$data[] = [1, 2, State::letLive()];
		$data[] = [1, 3, State::letLive()];
		$data[] = [2, 0, State::letLive()];
		$data[] = [2, 2, State::letLive()];
		$data[] = [2, 4, State::letLive()];
		$data[] = [3, 3, State::letLive()];
		$data[] = [3, 4, State::letLive()];

		return $data;
	}


	/**
	 * @dataProvider getNewCellStateForDeadCellProvider
	 * @test
	 * @param int $x
	 * @param int $y
	 * @param State $expected
	 */
	public function getNewCellStateForDeadCell($x, $y, State $expected)
	{
		$this->assertEquals($expected->getState(), \App\CellManager::getNewCellStateForDeadCell($this->cells, $x, $y, $this->worldWidth)->getState());
	}

	/**
	 * @return array
	 */
	public function getNewCellStateForDeadCellProvider()
	{
		$data = [];
		// x, y, expected state
		$data[] = [0, 2, State::letRest()];
		$data[] = [0, 3, State::letRest()];
		$data[] = [0, 4, State::letRest()];
		$data[] = [1, 4, State::letRest()];
		$data[] = [2, 1, State::resurrect("xx")];
		$data[] = [2, 3, State::resurrect("yy")];
		$data[] = [3, 0, State::letRest()];
		$data[] = [3, 1, State::letRest()];
		$data[] = [3, 2, State::letRest()];
		$data[] = [4, 0, State::letRest()];
		$data[] = [4, 1, State::letRest()];
		$data[] = [4, 2, State::letRest()];
		$data[] = [4, 3, State::letRest()];
		$data[] = [4, 4, State::letRest()];

		return $data;
	}

	/**
	 * @test
	 */
	public function getDeadNeighbours()
	{
		$liveCells[0][0] = "xx";
		$liveCells[19][19] = "yy";
		$liveCells[20][20] = "zz";
		$liveCells[49][49] = "aa";

		$expected[0][1] = null;
		$expected[1][0] = null;
		$expected[1][1] = null;

		$expected[18][18] = null;
		$expected[18][19] = null;
		$expected[18][20] = null;
		$expected[19][18] = null;
		$expected[19][20] = null;
		$expected[19][21] = null;
		$expected[20][18] = null;
		$expected[20][19] = null;
		$expected[20][21] = null;
		$expected[21][19] = null;
		$expected[21][20] = null;
		$expected[21][21] = null;

		$expected[48][48] = null;
		$expected[48][49] = null;
		$expected[49][48] = null;

		$this->assertEquals($expected, \App\CellManager::getDeadNeighbours($liveCells, $this->worldWidth));
	}

}
