<?php

namespace Xlucaspx\Dojotech\Api\Utils;

use DateInterval;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JsonWebToken
{
	public static function encode(int $userId, string $userName): string
	{
		$date = new DateTimeImmutable();
		$unixNow = $date->format('U');
		$unixExp = $date->add(new DateInterval("PT6H"))->format('U');

		$payload = [
			'iss' => 'dojotech_api',
			'aud' => 'dojotech',
			'iat' => (int)$unixNow,
			'exp' => (int)$unixExp,
			'sub' => $userId,
			'name' => $userName
		];

		return JWT::encode($payload, $_ENV['TOKEN_SECRET'], 'HS256');
	}

	public static function decode(string $token): array
	{
		/*
		if (!token){
			return false;
		}

		try {
			token = token.replace(/bearer\s?/gi, "");

    	if (token.length === 0) return false;

    	return jwt.verify(token, process.env.TOKEN_SECRET);
  	} catch (error) {
			throw new UnauthorizedError("Token de autorização inválido!");
		}
		*/
		return (array)JWT::decode($token, new Key($_ENV['TOKEN_SECRET'], 'HS256'));
	}
}
