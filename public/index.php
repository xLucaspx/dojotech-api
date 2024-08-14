<?php

declare(strict_types=1);

use Firebase\JWT\{ExpiredException, SignatureInvalidException};
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Xlucaspx\Dojotech\Api\Controller\Error\Error404Controller;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

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

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
	$psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
);
$request = $creator->fromGlobals();

$publicRoutes = ['GET|/sdg', 'GET|/project', 'GET|/project/details', 'POST|/user', 'POST|/login'];
// if accessing private routes the Authorization header is required
if (!in_array($key, $publicRoutes)) {
	if (!$request->hasHeader('Authorization')) {
		$res = new Response(401, body: json_encode(['error' => 'Token de autorização não enviado!']));
		echo $res->getBody();
		exit();
	}

	$token = $request->getHeaderLine('authorization');

	try {
		$decoded = JsonWebToken::decode($token);

		if (!$decoded) {
			$res = new Response(401, body: json_encode(['error' => 'Token de autorização inválido']));
			echo $res->getBody();
			exit();
		}
	} catch (SignatureInvalidException|ExpiredException) {
		$res = new Response(401, body: json_encode(['error' => 'Token de autorização inválido ou expirado']));
		echo $res->getBody();
		exit();
	}
}

// controller class = $routes[$key]
$controller = array_key_exists($key, $routes) ? $diContainer->get($routes[$key]) : new Error404Controller();

$response = $controller->handle($request);

http_response_code($response->getStatusCode());

foreach ($response->getHeaders() as $name => $values) {
	foreach ($values as $value) {
		header(sprintf('%s: %s', $name, $value), false);
	}
}

echo $response->getBody();
