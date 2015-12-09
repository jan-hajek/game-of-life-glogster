<?php
require_once __DIR__ . '/src/bootstrap.php';

use App\World;
use App\XmlProvider;

$xmlProvider = new XmlProvider();
$state = $xmlProvider->createWorldStateFromXmlFile(__DIR__ . "/in.xml");
$world = new World(new \App\CellManager($state->worldWidth));
$lastState = $world->run($state);
$xmlProvider->saveWorldStateToFile($lastState, __DIR__ . "/out.xml");