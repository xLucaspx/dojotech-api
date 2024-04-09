<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use DomainException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\UpdateProjectDto;
use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\UpdateUserDto;
use Xlucaspx\Dojotech\Api\Exception\DuplicateKeyException;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class UpdateProjectController implements RequestHandlerInterface
{
	public function __construct(
		private ProjectRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		try {
			$body = json_decode($request->getBody(), true);
			$project = $body['project'];

			if (!isset($project['id'])) {
				return new Response(400, body: json_encode(['error' => 'O ID do projeto não foi informado!']));
			}

			if (!isset($project['userId'])) {
				return new Response(400, body: json_encode(['error' => 'O ID do usuário não foi informado!']));
			}

			$requestUserId = filter_var($project['userId'], FILTER_VALIDATE_INT);

			$token = $request->getHeaderLine('authorization');
			$decoded = JsonWebToken::decode($token);
			$userId = $decoded['sub'];

			if ($userId !== $requestUserId) {
				return new Response(401, body: json_encode(['error' => 'Não é possível modificar o projeto de outros usuários!']));
			}

			$projectId = filter_var($project['id'], FILTER_VALIDATE_INT);
			$name = filter_var($project['name']);
			$cause = filter_var($project['cause']);
			$goal = filter_var($project['goal']);
			$target = filter_var($project['target']);
			$city = filter_var($project['city']);
			$partners = filter_var($project['partners']);
			$summary = filter_var($project['summary']);

			$sdgIds = $body['sdg'];

			$dto = new UpdateProjectDto($projectId, $name, $cause, $goal, $target, $city, $partners, $summary, $userId, $sdgIds);

			$this->repository->update($dto);
			return new Response(204);
		} catch (DuplicateKeyException $e) {
			return new Response(409, body: json_encode(['error' => $e->getMessage()]));
		} catch (DomainException $e) {
			return new Response(400, body: json_encode(['error' => $e->getMessage()]));
		}
	}
}
