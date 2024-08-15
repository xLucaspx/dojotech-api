<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/env.php';

use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;

$em = EntityManagerCreator::createEntityManager();
$repository = $em->getRepository(Project::class);

$projectList = $repository->filterBy([]);

// foreach ($projectList as $project) {
// 	var_dump(new ListProjectDto($project));
// }
