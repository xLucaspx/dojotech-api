<?php

namespace Xlucaspx\Dojotech\Api\Entity\User\Address;

use DomainException;

final class PostalCode
{
	public readonly string $code;

	public function __construct(string $postalCode)
	{
		if (!preg_match('/^\d{5}-?\d{3}$/', $postalCode)) {
			throw new DomainException("O CEP inserido é inválido!");
		}

		$this->code = preg_replace('/^(\d{5})-?(\d{3})$/', '\1\2', $postalCode);
	}

	public function __toString(): string
	{
		return $this->code;
	}
}
