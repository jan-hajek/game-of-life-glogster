<?php
define('SRC_DIR', __DIR__);
define('PROJECT_DIR', dirname(__DIR__));

require_once PROJECT_DIR . '/vendor/autoload.php';

spl_autoload_register(function ($class) {
	require_once SRC_DIR . '/' . str_replace('\\', '/', $class) . '.php';
});
