<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Doctrine\ORM\Exception\ORMException;
use DomainException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\NewProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\Address\PostalCode;
use Xlucaspx\Dojotech\Api\Entity\User\NewUserDto;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;
use Xlucaspx\Dojotech\Api\Repository\SdgRepository;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class NewProjectController implements RequestHandlerInterface
{
	public function __construct(
		private ProjectRepository $projectRepository,
		private UserRepository $userRepository,
		private SdgRepository $sdgRepository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		try {
			$body = json_decode($request->getBody(), true);

			$project = $body['project'];

			if (!isset($project['userId'])) {
				return new Response(400, body: json_encode(['error' => 'O ID do usuário não foi informado!']));
			}

			$requestId = filter_var($project['userId'], FILTER_VALIDATE_INT);

			$token = $request->getHeaderLine('authorization');
			$decoded = JsonWebToken::decode($token);
			$userId = $decoded['sub'];

			if ($userId !== $requestId) {
				return new Response(401, body: json_encode(['error' => 'Não é possível cadastrar projetos para outros usuários!']));
			}

//			$user = $this->userRepository->find($userId);

			$name = filter_var($project['name']);
			$cause = filter_var($project['cause']);
			$goal = filter_var($project['goal']);
			$target = filter_var($project['target']);
			$city = filter_var($project['city']);
			$partners = filter_var($project['partners']);
			$summary = filter_var($project['summary']);

			// sdg
			$sdgIds = $body['sdg'];

			// media

			$dto = new NewProjectDto($name, $cause, $goal, $target, $city, $partners, $summary, $userId, $sdgIds);

			$id = $this->projectRepository->add($dto);
			return new Response(201, body: json_encode(['id' => $id]));
		} catch (DomainException $e) {
			return new Response(400, body: json_encode(['error' => $e->getMessage()]));
		} catch (ORMException $e) {
			return new Response(500, body: json_encode(['error' => $e->getMessage()]));
		}
	}
}
