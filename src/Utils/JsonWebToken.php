<?php

namespace Xlucaspx\Dojotech\Api\Utils;

use DateInterval;
use DateTimeImmutable;
use Firebase\JWT\{JWT, Key};

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
		$token = preg_replace('/^bearer\s/i', '', $token);
		return (array)JWT::decode($token, new Key($_ENV['TOKEN_SECRET'], 'HS256'));
	}
}
