<?php

declare(strict_types=1);

namespace Xlucaspx\Dojotech\Api;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240401130910 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Create schema; tables: {user, project, sdg, project_sdg, media}';
	}

	public function up(Schema $schema): void
	{
		$this->addSql(<<<END
			CREATE TABLE `user` (
				`id` INT AUTO_INCREMENT PRIMARY KEY,
				`name` VARCHAR(75) NOT NULL,
				`email` VARCHAR(50) NOT NULL UNIQUE,
				`username` VARCHAR(20) NOT NULL UNIQUE,
				`phone` VARCHAR(13) NOT NULL,
				`password_hash` VARCHAR(255) NOT NULL,
				`password_salt` VARCHAR(255) NOT NULL,
				`postal_code` VARCHAR(8) NOT NULL,
				`address` VARCHAR(100) NOT NULL,
				`district` VARCHAR(50) NOT NULL,
				`number` VARCHAR(10) DEFAULT NULL,
				`complement` VARCHAR(50) DEFAULT NULL,
				`city` VARCHAR(50) NOT NULL,
				`state` VARCHAR(2) NOT NULL
			);
			END
		);

		$this->addSql(<<<END
			CREATE TABLE `project` (
				`id` INT AUTO_INCREMENT PRIMARY KEY,
				`name` VARCHAR(75) NOT NULL,
				`cause` VARCHAR(75) NOT NULL,
				`goal` VARCHAR(125) NOT NULL,
				`target` VARCHAR(75) NOT NULL,
				`city` VARCHAR(50) NOT NULL,
				`partners` VARCHAR(255) DEFAULT NULL,
				`summary` TEXT NOT NULL,
				`user_id` INT NOT NULL,
				CONSTRAINT `fk_project_user`
					FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) 
					ON UPDATE NO ACTION ON DELETE CASCADE
			);
			END
		);

		$this->addSql(<<<END
			CREATE TABLE `sdg` (
				`id` INT AUTO_INCREMENT PRIMARY KEY,
				`name` VARCHAR(50) NOT NULL,
				`image_url` VARCHAR(175) NOT NULL
			);
			END
		);

		$this->addSql(<<<END
			CREATE TABLE `project_sdg` (
				`project_id` INT NOT NULL,
				`sdg_id` INT NOT NULL,
				PRIMARY KEY(`project_id`, `sdg_id`),
				CONSTRAINT `fk_project_sdg_p`
					FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
					ON UPDATE NO ACTION ON DELETE CASCADE,
				CONSTRAINT `fk_project_sdg_s`
					FOREIGN KEY (`sdg_id`) REFERENCES `sdg` (`id`)
					ON UPDATE NO ACTION ON DELETE NO ACTION
			); 
			END
		);

		$this->addSql(<<<END
			CREATE TABLE `media` (
				`id` INT AUTO_INCREMENT PRIMARY KEY,
				`type` VARCHAR(25) NOT NULL,
				`url` VARCHAR(175) NOT NULL,
				`alt` VARCHAR(255) DEFAULT NULL,
				`project_id` INT NOT NULL,
				CONSTRAINT `fk_media_project`
					FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
					ON UPDATE NO ACTION ON DELETE CASCADE
			);
			END
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE `media`');
		$this->addSql('DROP TABLE `project_sdg`');
		$this->addSql('DROP TABLE `sdg`');
		$this->addSql('DROP TABLE `project`');
		$this->addSql('DROP TABLE `user`');
	}
}
