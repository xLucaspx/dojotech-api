<?php

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\User\User;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;
use Xlucaspx\Dojotech\Api\Repository\{ProjectRepository, SdgRepository, UserRepository};

$builder = new ContainerBuilder();

$builder->addDefinitions([
	EntityManager::class => fn() => EntityManagerCreator::createEntityManager(),
	SdgRepository::class => fn() => EntityManagerCreator::createEntityManager()->getRepository(Sdg::class),
	ProjectRepository::class => fn() => EntityManagerCreator::createEntityManager()->getRepository(Project::class),
	UserRepository::class => fn() => EntityManagerCreator::createEntityManager()->getRepository(User::class)
]);

$container = $builder->build();

return $container;
