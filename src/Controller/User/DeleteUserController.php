<?php

namespace Xlucaspx\Dojotech\Api\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class DeleteUserController implements RequestHandlerInterface
{
	public function __construct(
		private UserRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$body = json_decode($request->getBody(), true);

		if (!isset($body['id'])) {
			return new Response(400, body: json_encode(['error' => 'O ID do usuário não foi informado!']));
		}

		$requestId = filter_var($body['id'], FILTER_VALIDATE_INT);

		$token = $request->getHeaderLine('authorization');
		$decoded = JsonWebToken::decode($token);
		$userId = $decoded['sub'];

		if ($userId !== $requestId) {
			return new Response(401, body: json_encode(['error' => 'Não é possível modificar os dados de outros usuários!']));
		}

		$this->repository->delete($userId);

		return new Response(204);
	}
}
