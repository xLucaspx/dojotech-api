<?php

namespace Xlucaspx\Dojotech\Api\Entity\User\DataTypes;

use DomainException;

class Email
{
	public function __construct(public readonly string $email)
	{
		if (!preg_match('/^\w+(?:[.\-]?\w+)*@\w+(?:[.\-]?\w+)*(?:\.\w{2,3})+$/', $email)) {
			throw new DomainException('O e-mail inserido é inválido!');
		}
	}

	public function __toString(): string
	{
		return $this->email;
	}
}
