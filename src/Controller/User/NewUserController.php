<?php

namespace Xlucaspx\Dojotech\Api\Controller\User;

use Doctrine\ORM\Exception\ORMException;
use DomainException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\NewUserDto;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;

class NewUserController implements RequestHandlerInterface
{
	public function __construct(
		private UserRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		try {
			$body = json_decode($request->getBody(), true);

			$name = filter_var($body['name']);
			$email = filter_var($body['email'], FILTER_VALIDATE_EMAIL);
			$username = filter_var($body['username']);
			$phone = filter_var($body['phone']);
			$password = filter_var($body['password']);
			$postalCode = filter_var($body['postalCode']);
			$address = filter_var($body['address']);
			$district = filter_var($body['district']);
			$number = filter_var($body['number']);
			$complement = filter_var($body['complement']);
			$city = filter_var($body['city']);
			$state = filter_var($body['state']);

			if ($this->repository->existsByEmail($email)) {
				return new Response(409, body: json_encode(['error' => "O E-mail $email não está disponível!"]));
			}

			if ($this->repository->existsByUsername($username)) {
				return new Response(409, body: json_encode(['error' => "O nome de usuário $username não está disponível!"]));
			}

			$address = new AddressDto($postalCode, $address, $district, $number, $complement, $city, $state);
			$dto = new NewUserDto($name, $email, $username, $phone, $password, $address);

			$id = $this->repository->add($dto);
			return new Response(201, body: json_encode(['id' => $id]));
		} catch (DomainException $e) {
			return new Response(400, body: json_encode(['error' => $e->getMessage()]));
		} catch (ORMException $e) {
			return new Response(500, body: json_encode(['error' => $e->getMessage()]));
		}
	}
}
