<?php

namespace Xlucaspx\Dojotech\Api\Controller\User;

use DomainException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\UpdateUserDto;
use Xlucaspx\Dojotech\Api\Exception\DuplicateKeyException;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class UpdateUserController implements RequestHandlerInterface
{
	public function __construct(
		private UserRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		try {
			$queryParams = $request->getQueryParams();

			if (!isset($queryParams['id'])) {
				return new Response(400, body: json_encode(['error' => 'O ID do usuário não foi informado!']));
			}

			$queryId = filter_var($queryParams['id'], FILTER_VALIDATE_INT);

			$token = $request->getHeaderLine('authorization');
			$decoded = JsonWebToken::decode($token);
			$userId = $decoded['sub'];

			if ($userId !== $queryId) {
				return new Response(401, body: json_encode(['error' => 'Não é possível modificar os dados de outros usuários!']));
			}

			$body = json_decode($request->getBody(), true);

			$name = filter_var($body['name']);
			$email = filter_var($body['email'], FILTER_VALIDATE_EMAIL);
			$username = filter_var($body['username']);
			$phone = filter_var($body['phone']);
			$postalCode = filter_var($body['postalCode']);
			$address = filter_var($body['address']);
			$district = filter_var($body['district']);
			$number = filter_var($body['number']);
			$complement = filter_var($body['complement']);
			$city = filter_var($body['city']);
			$state = filter_var($body['state']);

			$password = isset($body['password']) ? filter_var($body['password']) : null;

			$address = new AddressDto($postalCode, $address, $district, $number, $complement, $city, $state);
			$dto = new UpdateUserDto($userId, $name, $email, $username, $phone, $address, $password);

			$this->repository->update($dto);
			return new Response(204);
		} catch (DuplicateKeyException $e) {
			return new Response(409, body: json_encode(['error' => $e->getMessage()]));
		} catch (DomainException $e) {
			return new Response(400, body: json_encode(['error' => $e->getMessage()]));
		}
	}
}
