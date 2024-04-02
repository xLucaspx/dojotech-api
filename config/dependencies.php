<?php

use DI\ContainerBuilder;
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;
use Xlucaspx\Dojotech\Api\Repository\SdgRepository;

$builder = new ContainerBuilder();

$builder->addDefinitions([
	SdgRepository::class => fn() => EntityManagerCreator::createEntityManager()->getRepository(Sdg::class)
]);

$container = $builder->build();

return $container;
