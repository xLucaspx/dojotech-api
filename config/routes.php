<?php

declare(strict_types=1);

use Xlucaspx\Dojotech\Api\Controller\Project\ListProjectController;
use Xlucaspx\Dojotech\Api\Controller\Sdg\ListSdgController;

return [
	'GET|/sdg' => ListSdgController::class,

	// 'GET|/user' => ,
	// 'POST|/user' => ,
	// 'PUT|/user' => ,
	// 'DELETE|/user' => ,

	// 'POST|/login' => ,

	// 'POST|/auth' => ,

	'GET|/project' => ListProjectController::class,
	// 'GET|/project/details' => ,
	// 'GET|/project/report' => ,
	// 'GET|/project/filter' => ,
	// 'GET|/project/user' => ,
	// 'POST|/project' => ,
	// 'PUT|/project' => ,
	// 'DELETE|/project' => ,
];
