<?php

namespace Xlucaspx\Dojotech\Api\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\User\UserDetailsDto;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class UserDetailsController implements RequestHandlerInterface
{
	public function __construct(
		private UserRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$queryParams = $request->getQueryParams();

		if (!isset($queryParams['id'])) {
			return new Response(400, body: json_encode(['error' => 'O ID não foi informado!']));
		}

		$queryId = filter_var($queryParams['id'], FILTER_VALIDATE_INT);

		$token = $request->getHeaderLine('authorization');
		$decoded = JsonWebToken::decode($token);
		$userId = $decoded['sub'];

		if ($userId !== $queryId) {
			return new Response(401, body: json_encode(['error' => 'Não é possível buscar as informações de outros usuários!']));
		}

		$user = $this->repository->find($userId);
		$dto = new UserDetailsDto($user);

		return new Response(200, body: json_encode($dto));
	}
}
