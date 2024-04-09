<?php

declare(strict_types=1);

use Xlucaspx\Dojotech\Api\Controller\Auth\{AuthController, LoginController};
use Xlucaspx\Dojotech\Api\Controller\Project\{DeleteProjectController,
	ListProjectController,
	NewProjectController,
	ProjectDetailsController
};
use Xlucaspx\Dojotech\Api\Controller\Sdg\ListSdgController;
use Xlucaspx\Dojotech\Api\Controller\User\{DeleteUserController,
	ListUserProjectsController,
	NewUserController,
	UpdateUserController,
	UserDetailsController
};

return [
	// public routes
	'GET|/sdg' => ListSdgController::class,
	'GET|/project' => ListProjectController::class,
	'GET|/project/details' => ProjectDetailsController::class,
	'POST|/user' => NewUserController::class,
	'POST|/login' => LoginController::class,

	// private routes
	'GET|/auth' => AuthController::class,
	'GET|/user/details' => UserDetailsController::class,
	'GET|/user/projects' => ListUserProjectsController::class,

	'PUT|/user' => UpdateUserController::class,
	'DELETE|/user' => DeleteUserController::class,


	'POST|/project' => NewProjectController::class,
	// 'GET|/project/report' => ,
	// 'PUT|/project' => ,
	'DELETE|/project' => DeleteProjectController::class,
];
