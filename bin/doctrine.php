<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/env.php';

$entityManager = EntityManagerCreator::createEntityManager();

ConsoleRunner::run(new SingleManagerProvider($entityManager));
