<?php

namespace Xlucaspx\Dojotech\Api\Utils;

class Report
{
	public static function generateReport(array $reportData, int $totalProjects): string
	{
		$title = "Relatório de projetos da plataforma Somar - Ajuda RS";
		$date = self::getFormattedDateString();
		$total = str_pad("- Total de projetos cadastrados", 70, '.', STR_PAD_RIGHT);


		$report = sprintf(<<<END
		$title
		
		$date
		
		$total %05d
		
		- Total de projetos para cada ODS:
		
		END, $totalProjects);

		foreach ($reportData as $data) {
			$sdgId = sprintf('%02d', $data['sdg_id']);
			$str = str_pad("  - $sdgId - {$data['sdg_name']}", 72, '.', STR_PAD_RIGHT);
			$report .= sprintf("$str %05d", $data['total_projects']) . PHP_EOL;
		}

		return $report;
//		return file_put_contents(__DIR__ . '/../../public/report/relatorio.txt', $report);
	}

	private static function getFormattedDateString(): string
	{
		date_default_timezone_set('America/Sao_paulo');
		$date = getdate();
		$weekDay = match ($date['wday']) {
			0 => 'Domingo',
			1 => 'Segunda-feira',
			2 => 'Terça-feira',
			3 => 'Quarta-feira',
			4 => 'Quinta-feira',
			5 => 'Sexta-feira',
			6 => 'Sabado'
		};
		$monthDay = $date['mday'];
		$month = match ($date['mon']) {
			1 => 'janeiro',
			2 => 'fevereiro',
			3 => 'março',
			4 => 'abril',
			5 => 'maio',
			6 => 'junho',
			7 => 'julho',
			8 => 'agosto',
			9 => 'setembro',
			10 => 'outubro',
			11 => 'novembro',
			12 => 'dezembro'
		};
		$year = $date['year'];
		$hours = sprintf('%02d', $date['hours']);
		$minutes = sprintf('%02d', $date['minutes']);
		$seconds = sprintf('%02d', $date['seconds']);

		return "$weekDay, $monthDay de $month de $year às $hours:$minutes:$seconds";
	}
}
