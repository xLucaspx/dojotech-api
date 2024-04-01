<?php

namespace Xlucaspx\Dojotech\Api\Helper;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

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
			'driver' => 'pdo_mysql',
			'host' => '127.0.0.1',
			'port' => '3306',
			'user' => 'user01',
			'password' => 'admin',
			'dbname' => 'dojotech_php'
		], $config);

		return new EntityManager($connection, $config);
	}
}
