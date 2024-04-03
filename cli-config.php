<?php

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/env.php';

$config = new PhpFile(__DIR__ . '/migrations.php');

$entityManager = EntityManagerCreator::createEntityManager();

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
