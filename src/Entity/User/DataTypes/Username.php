<?php

namespace Xlucaspx\Dojotech\Api\Entity\User\DataTypes;

use DomainException;

class Username
{
	public function __construct(public readonly string $username)
	{
		if (!preg_match('/^[\w\-]{3,20}$/', $username)) {
			throw new DomainException('O nome de usuário inserido é inválido');
		}
	}

	public function __toString(): string
	{
		return $this->username;
	}
}
