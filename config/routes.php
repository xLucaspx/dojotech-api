<?php

declare(strict_types=1);

use Xlucaspx\Dojotech\Api\Controller\Auth\AuthController;
use Xlucaspx\Dojotech\Api\Controller\Auth\LoginController;
use Xlucaspx\Dojotech\Api\Controller\Project\ListProjectController;
use Xlucaspx\Dojotech\Api\Controller\Project\ProjectDetailsController;
use Xlucaspx\Dojotech\Api\Controller\Sdg\ListSdgController;
use Xlucaspx\Dojotech\Api\Controller\User\UserDetailsController;

return [
	// public routes
	'GET|/sdg' => ListSdgController::class,
	'GET|/project' => ListProjectController::class,
	'GET|/project/details' => ProjectDetailsController::class,
	'POST|/login' => LoginController::class,

	// private routes
	'GET|/auth' => AuthController::class,
	'GET|/user/details' => UserDetailsController::class,

	// 'POST|/user' => ,
	// 'PUT|/user' => ,
	// 'DELETE|/user' => ,


	// 'GET|/project/report' => ,
	// 'GET|/project/user' => ,
	// 'POST|/project' => ,
	// 'PUT|/project' => ,
	// 'DELETE|/project' => ,
];
