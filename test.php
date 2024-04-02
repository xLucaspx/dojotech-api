<?php

use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;

require_once __DIR__ . '/vendor/autoload.php';

$em = EntityManagerCreator::createEntityManager();
$repository = $em->getRepository(Project::class);

$projectList = $repository->findAll();

foreach ($projectList as $project) {
	var_dump(new ListProjectDto($project));
}
