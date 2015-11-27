<?php
require_once __DIR__ . '/src/bootstrap.php';

use App\World;
use App\XmlProvider;

$state = XmlProvider::createWorldStateFromXmlFile(__DIR__ . "/in.xml");
$lastState = World::run($state);
XmlProvider::saveWorldStateToFile($lastState, __DIR__ . "/out.xml");