<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Repository\{ProjectRepository};
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class DeleteProjectController implements RequestHandlerInterface
{
	public function __construct(
		private ProjectRepository $projectRepository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$body = json_decode($request->getBody(), true);

		if (!isset($body['id'])) {
			return new Response(400, body: json_encode(['error' => 'O ID do projeto não foi informado!']));
		}

		$projectId = filter_var($body['id'], FILTER_VALIDATE_INT);
		/** @var Project $project */
		$project = $this->projectRepository->find($projectId);

		if (!$project) {
			return new Response(404, body: json_encode(['error' => "Nenhum projeto encontrado para o ID $projectId"]));
		}

		$token = $request->getHeaderLine('authorization');
		$decoded = JsonWebToken::decode($token);
		$userId = $decoded['sub'];

		if ($userId !== $project->user()->id()) {
			return new Response(401, body: json_encode(['error' => 'Não é possível excluir o projeto de outros usuários!']));
		}

		$projectImageDir = __DIR__ . "/../../../public/img/project/{$project->id()}";
		$medias = $project->medias();
		foreach ($medias as $media) {
			unlink("$projectImageDir/$media->url");
		}

		rmdir($projectImageDir);

		$this->projectRepository->delete($project);

		return new Response(204);
	}
}
