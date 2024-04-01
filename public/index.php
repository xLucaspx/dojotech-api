<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var array $routes */
$routes = require_once __DIR__ . '/../config/routes.php';

$httpMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = $_SERVER['PATH_INFO'];

$key = "$httpMethod|$pathInfo";

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
	$psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
);

$request = $creator->fromGlobals();

echo $key;
