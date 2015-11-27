<?php
namespace App;


class XmlProviderSaveWorldStateToFileTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function validXml()
	{
		$state = new WorldState();
		$state->worldWidth = 1;
		$state->speciesCount = 2;
		$state->iterations = 3;
		$state->generation[5][6] = "a";
		$state->generation[6][7] = "c";
		$expectedFilePath = __DIR__ . "/XmlProviderSaveWorldStateToFileTestData/expected.xml";
		$actualFilePath = __DIR__ . "/XmlProviderSaveWorldStateToFileTestData/actual.xml";
		XmlProvider::saveWorldStateToFile($state, $actualFilePath);
		$this->assertFileEquals($expectedFilePath, $actualFilePath);
		unlink($actualFilePath);
	}

	/**
	 * @test
	 * @expectedException  \App\XmlProviderException
	 * @expectedExceptionCode  \App\XmlProviderException::WRITE_FILE_FAIL
	 */
	public function writeFailed()
	{
		$state = new WorldState();
		$state->generation = [];
		$actualFilePath = "/unknownDir/xxx/yyy/test.xml";
		XmlProvider::saveWorldStateToFile($state, $actualFilePath);
	}
}