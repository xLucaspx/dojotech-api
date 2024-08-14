<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;
use Xlucaspx\Dojotech\Api\Utils\Report;

class CreateReportController implements RequestHandlerInterface
{
	public function __construct(private ProjectRepository $repository) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$reportData = $this->repository->getReportData();
		$totalProjects = $this->repository->count();

		$report = Report::generateReport($reportData, $totalProjects);

		return new Response(200, body: json_encode(['report' => $report]));
	}
}
