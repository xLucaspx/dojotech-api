<?php

namespace Xlucaspx\Dojotech\Api\Helper;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\{EntityManager, ORMSetup};
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\{ConsoleOutput, OutputInterface};

class EntityManagerCreator
{
	public static function createEntityManager(): EntityManager
	{
		$config = ORMSetup::createAttributeMetadataConfiguration(
			paths: [__DIR__ . '/../'],
			isDevMode: true
		);

		// utilizando middleware de log para exibir SQL no console:
		$consoleOutput = new ConsoleOutput(OutputInterface::VERBOSITY_DEBUG);
		$consoleLogger = new ConsoleLogger($consoleOutput);
		$logMiddleware = new Middleware($consoleLogger);

		$config->setMiddlewares([$logMiddleware]);

		$connection = DriverManager::getConnection([
			'driver' => $_ENV['DB_DRIVER'],
			'host' => $_ENV['DB_HOST'],
			'port' => $_ENV['DB_PORT'],
			'user' => $_ENV['DB_USERNAME'],
			'password' => $_ENV['DB_PASSWORD'],
			'dbname' => $_ENV['DB_DATABASE']
		], $config);

		return new EntityManager($connection, $config);
	}
}
