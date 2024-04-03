<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Xlucaspx\Dojotech\Api\Controller\Error\Error404Controller;

require_once __DIR__ . '/../vendor/autoload.php';

// load environment variables
require_once __DIR__ . '/../config/env.php';

// handle CORS
require_once __DIR__ . '/../config/cors.php';

/** @var array $routes */
$routes = require_once __DIR__ . '/../config/routes.php';

/** @var ContainerInterface $diContainer */
$diContainer = require_once __DIR__ . '/../config/dependencies.php';

$httpMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = $_SERVER['PATH_INFO'];

$key = "$httpMethod|$pathInfo";

$controller = new Error404Controller();

if (array_key_exists($key, $routes)) {
	$controllerClass = $routes[$key];
	$controller = $diContainer->get($controllerClass);
}

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
	$psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
);

$request = $creator->fromGlobals();

$response = $controller->handle($request);

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $values) {
	foreach ($values as $value) {
		header(sprintf('%s: %s', $name, $value), false);
	}
}

echo $response->getBody();
