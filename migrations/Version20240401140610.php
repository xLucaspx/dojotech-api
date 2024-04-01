<?php

declare(strict_types=1);

namespace Xlucaspx\Dojotech\Api;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240401140610 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Populate table: sdg';
	}

	public function up(Schema $schema): void
	{
		$this->addSql(<<<END
					INSERT INTO `sdg` (`id`, `name`, `image_url`) VALUES
						(1, 'Erradicação da Pobreza', 'ods_1.svg'),
						(2, 'Fome Zero e Agricultura Sustentável', 'ods_2.svg'),
						(3, 'Saúde e Bem-Estar', 'ods_3.svg'),
						(4, 'Educação de Qualidade', 'ods_4.svg'),
						(5, 'Igualdade de Gênero', 'ods_5.svg'),
						(6, 'Água Potável e Saneamento', 'ods_6.svg'),
						(7, 'Energia Limpa e Acessível', 'ods_7.svg'),
						(8, 'Trabalho Decente e Crescimento Econômico', 'ods_8.svg'),
						(9, 'Indústria, Inovação e Infraestrutura', 'ods_9.svg'),
						(10, 'Redução das Desigualdades', 'ods_10.svg'),
						(11, 'Cidades e Comunidades Sustentáveis', 'ods_11.svg'),
						(12, 'Consumo e Produção Responsáveis', 'ods_12.svg'),
						(13, 'Ação Contra a Mudança Global do Clima', 'ods_13.svg'),
						(14, 'Vida na Água', 'ods_14.svg'),
						(15, 'Vida Terrestre', 'ods_15.svg'),
						(16, 'Paz, Justiça e Instituições Eficazes', 'ods_16.svg'),
						(17, 'Parcerias e Meios de Implementação', 'ods_17.svg')
				END
		);
	}

	public function down(Schema $schema): void
	{
		$this->addSql('DELETE FROM `sdg` WHERE `id` > 0 AND `id` < 18');
	}
}
