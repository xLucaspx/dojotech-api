<?php

namespace Xlucaspx\Dojotech\Api\Controller\Auth;

use Firebase\JWT\SignatureInvalidException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\User\User;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class AuthController implements RequestHandlerInterface
{

	public function __construct(
		private UserRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$token = preg_replace('/^bearer\s/i', '', $request->getHeaderLine('authorization'));

		try {
			$decoded = JsonWebToken::decode($token);

			if (!$decoded) {
				return new Response(401, body: json_encode(['error' => 'Token de autorização inválido']));
			}

			return new Response(200, body: json_encode($decoded));
		} catch (SignatureInvalidException) {
			return new Response(401, body: json_encode(['error' => 'Token de autorização inválido']));
		}
	}
}
