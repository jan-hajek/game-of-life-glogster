<?php
namespace App;

class XmlProvider
{
	/**
	 * @param string $filePath
	 * @throws XmlProviderException
	 * @return WorldState
	 */
	public static function createWorldStateFromXmlFile($filePath)
	{
		$data = self::getData($filePath);

		$config = new WorldState();
		$config->worldWidth = self::getNumberValue($data, "world.cells");
		$config->iterations = self::getNumberValue($data, "world.iterations");
		// new cells can born only from species declared in world.organisms.organism.species tag in source xml
		// therefore tag world.species is unnecessary
		$config->speciesCount = self::getNumberValue($data, "world.species");

		if (!isset($data['organisms']['organism'])) {
			throw new XmlProviderException("missing tag organisms.organism", XmlProviderException::WRONG_FORMAT);
		}
		foreach ($data['organisms']['organism'] as $organism) {
			$x = self::getNumberValue($organism, 'x_pos');
			$y = self::getNumberValue($organism, 'y_pos');
			if (!isset($organism['species'])) {
				throw new XmlProviderException("missing tag species", XmlProviderException::WRONG_FORMAT);
			}
			$species = $organism['species'];
			$config->generation[$x][$y] = $species;
		}
		return $config;
	}

	/**
	 * @param array $array
	 * @param string $path
	 * @return int
	 * @throws XmlProviderException
	 */
	private function getNumberValue($array, $path)
	{
		foreach (explode(".", $path) as $name) {
			if (!isset($array[$name])) {
				throw new XmlProviderException("missing tag {$path}", XmlProviderException::WRONG_FORMAT);
			}
			$array = $array[$name];
		}
		$value = $array;
		if (!is_numeric($value)) {
			throw new XmlProviderException("{$path} must be numeric", XmlProviderException::WRONG_FORMAT);
		}
		return $value;
	}


	/**
	 * @param string $filePath
	 * @return array
	 * @throws XmlProviderException
	 */
	private static function getData($filePath)
	{
		$xmlInput = @file_get_contents($filePath);
		if ($xmlInput === FALSE) {
			$lastError = error_get_last();
			throw new XmlProviderException($lastError['message'], XmlProviderException::READ_FILE_FAIL);
		}
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($xmlInput, "SimpleXMLElement", LIBXML_NOCDATA);
		if (!$xml) {
			$errors = libxml_get_errors();
			$error = array_shift($errors);
			throw new XmlProviderException($error->message, XmlProviderException::PARSE_ERROR);
		}
		libxml_use_internal_errors(false);
		$json = json_encode($xml);
		return json_decode($json, TRUE);
	}

	/**
	 * @param WorldState $state
	 * @param string $filePath
	 * @throws XmlProviderException
	 */
	public static function saveWorldStateToFile(WorldState $state, $filePath)
	{
		$xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><life></life>");

		$world = $xml->addChild("world");
		$world->addChild('cells', $state->worldWidth);
		$world->addChild('species', $state->speciesCount);
		$world->addChild('iterations', $state->iterations);

		$organisms = $xml->addChild('organisms');
		foreach ($state->generation as $x => $row) {
			foreach ($row as $y => $species) {
				$organism = $organisms->addChild("organism");
				$organism->addChild('x_pos', $x);
				$organism->addChild('y_pos', $y);
				$organism->addChild('species', $species);

			}
		}
		$result = @$xml->saveXML($filePath);
		if (!$result) {
			$lastError = error_get_last();
			throw new XmlProviderException($lastError["message"], XmlProviderException::WRITE_FILE_FAIL);
		}
	}
}