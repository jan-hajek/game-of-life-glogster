<?php
namespace App;


class XmlProviderCreateWorldStateTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @expectedException  \App\XmlProviderException
	 * @expectedExceptionCode  \App\XmlProviderException::READ_FILE_FAIL
	 */
	public function fileNotFound()
	{
		(new XmlProvider())->createWorldStateFromXmlFile(__DIR__ . "/unknown.xml");
	}

	/**
	 * @test
	 * @expectedException  \App\XmlProviderException
	 * @expectedExceptionCode  \App\XmlProviderException::PARSE_ERROR
	 */
	public function invalidXml()
	{
		(new XmlProvider())->createWorldStateFromXmlFile(__DIR__ . "/XmlProviderCreateWorldStateTestData/invalid.xml");
	}

	/**
	 * @test
	 * @expectedException  \App\XmlProviderException
	 * @expectedExceptionCode  \App\XmlProviderException::WRONG_FORMAT
	 */
	public function missingCellTag()
	{
		(new XmlProvider())->createWorldStateFromXmlFile(__DIR__ . "/XmlProviderCreateWorldStateTestData/missingCellsTag.xml");
	}


	/**
	 * @test
	 */
	public function validXml()
	{
		$expectedFirstGeneration[5][6] = "a";
		$expectedFirstGeneration[6][7] = "c";
		$config = (new XmlProvider())->createWorldStateFromXmlFile(__DIR__ . "/XmlProviderCreateWorldStateTestData/valid.xml");
		$this->assertEquals(100, $config->worldWidth);
		$this->assertEquals(1000, $config->iterations);
		$this->assertEquals($expectedFirstGeneration, $config->generation);
	}
}