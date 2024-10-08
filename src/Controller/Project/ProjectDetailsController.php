<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\ProjectDetailsDto;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;

class ProjectDetailsController implements RequestHandlerInterface
{

	public function __construct(
		private ProjectRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$queryParams = $request->getQueryParams();

		if (!isset($queryParams['id'])) {
			return new Response(400, body: json_encode(['error' => 'O ID não foi informado!']));
		}

		$id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);

		$project = $this->repository->find($id);

		if (!$project) {
			return new Response(404, body: json_encode(['error' => "Nenhum projeto encontrado para o ID $id"]));
		}

		$dto = new ProjectDetailsDto($project);

		return new Response(200, body: json_encode($dto));
	}
}
