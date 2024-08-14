<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\Media;
use Xlucaspx\Dojotech\Api\Repository\MediaRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class DeleteProjectMediaController implements RequestHandlerInterface
{
	public function __construct(
		private MediaRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$body = json_decode($request->getBody(), true);

		if (!isset($body['projectId'])) {
			return new Response(400, body: json_encode(['error' => 'O ID do projeto não foi informado!']));
		}

		if (!isset($body['mediaId'])) {
			return new Response(400, body: json_encode(['error' => 'O ID da mídia não foi informado!']));
		}

		$mediaId = filter_var($body['mediaId'], FILTER_VALIDATE_INT);
		/** @var Media $media */
		$media = $this->repository->find($mediaId);

		if (!$media) {
			return new Response(404, body: json_encode(['error' => "Nenhuma mídia encontrada para o ID $mediaId"]));
		}

		$project = $media->project;

		if ($project->id() !== filter_var($body['projectId'], FILTER_VALIDATE_INT)) {
			return new Response(400, body: json_encode(['error' => 'A mídia não pertence a projeto informado!']));
		}

		$token = $request->getHeaderLine('authorization');
		$decoded = JsonWebToken::decode($token);
		$userId = $decoded['sub'];

		if ($userId !== $project->user()->id()) {
			return new Response(401, body: json_encode(['error' => 'Não é possível modificar o projeto de outros usuários!']));
		}

		try {
			$deleted = $this->repository->delete($media);

			if (!$deleted) {
				return new Response(500, body: json_encode(['error' => 'Não foi possível deletar a mídia!']));
			}

			return new Response(204);
		} catch (Exception $exception) {
			return new Response(500, body: json_encode(['error' => $exception->getMessage()]));
		}
	}
}
