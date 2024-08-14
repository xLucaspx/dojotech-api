<?php

namespace Xlucaspx\Dojotech\Api\Controller\Auth;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\User\User;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class LoginController implements RequestHandlerInterface
{

	public function __construct(
		private UserRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$body = json_decode($request->getBody(), true);

		$typedUser = filter_var($body['user']);
		$typedPassword = filter_var($body['password']);

		/** @var User|null $userData */
		$userData = preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $typedUser)
			? $this->repository->findOneBy(['email' => $typedUser])
			: $this->repository->findOneBy(['username' => $typedUser]);

		if (!$userData || !password_verify($typedPassword, $userData->passwordHash())) {
			return new Response(400, body: json_encode(['error' => 'Usuário ou senha inválidos!']));
		}

//		if (password_needs_rehash($userData->passwordHash, PASSWORD_ARGON2ID)) {
//			$updateData = new RehashUserPasswordDto($userData->id, $userData->passwordHash);
//			$this->userRepository->updatePassword($updateData);
//		}

		$jwt = JsonWebToken::encode($userData->id(), $userData->name());
		return new Response(200, body: json_encode(['token' => $jwt]));
	}
}
