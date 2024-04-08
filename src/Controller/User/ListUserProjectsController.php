<?php

namespace Xlucaspx\Dojotech\Api\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class ListUserProjectsController implements RequestHandlerInterface
{
	public function __construct(
		private ProjectRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$queryParams = $request->getQueryParams();

		if (!isset($queryParams['userId'])) {
			return new Response(400, body: json_encode(['error' => 'O ID do usuário não foi informado!']));
		}

		$queryId = filter_var($queryParams['userId'], FILTER_VALIDATE_INT);

		$token = $request->getHeaderLine('authorization');
		$decoded = JsonWebToken::decode($token);
		$userId = $decoded['sub'];

		if ($userId !== $queryId) {
			return new Response(401, body: json_encode(['error' => 'Não é possível buscar os projetos de outros usuários!']));
		}

		$projectList = $this->repository->findBy(['user' => $userId]);
		$dtoList = array_map(
			fn(Project $project) => new ListProjectDto($project),
			$projectList
		);

		return new Response(200, body: json_encode($dtoList));
	}
}
