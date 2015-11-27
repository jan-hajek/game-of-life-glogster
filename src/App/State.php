<?php
namespace App;

class State
{
	CONST LET_REST = 1;
	CONST LET_LIVE = 2;
	CONST TERMINATE = 3;
	CONST RESURRECT = 4;

	/**
	 * @var int
	 */
	private $state;
	/**
	 * @var string
	 */
	private $type;

	/**
	 * @param $state
	 * @param $type
	 */
	private function __construct($state, $type = null)
	{
		$this->state = $state;
		$this->type = $type;
	}

	/**
	 * @return State
	 */
	public static function letLive()
	{
		return new self(self::LET_LIVE);
	}

	/**
	 * @return State
	 */
	public static function letRest()
	{
		return new self(self::LET_REST);
	}

	/**
	 * @return State
	 */
	public static function terminate()
	{
		return new self(self::TERMINATE);
	}

	/**
	 * @param $newType
	 * @return State
	 */
	public static function resurrect($newType)
	{
		return new self(self::RESURRECT, $newType);
	}

	/**
	 * @return int
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


}