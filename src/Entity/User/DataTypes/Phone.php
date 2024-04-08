<?php

namespace Xlucaspx\Dojotech\Api\Entity\User\DataTypes;

use DomainException;

final class Phone
{
	public readonly string $number;

	public function __construct(string $phoneNumber)
	{
		if (!preg_match('/^[(]?(?:[14689][1-9]|2[12478]|3[1234578]|5[1345]|7[134579])[)]? ?(?:[2-8]|9 ?[1-9])[0-9]{3}[\- ]?[0-9]{4}$/', $phoneNumber)) {
			throw new DomainException("O telefone inserido é inválido!");
		}

		$this->number = strtr($phoneNumber, [
			' ' => '',
			'-' => '',
			'(' => '',
			')' => ''
		]);
	}

	public function __toString(): string
	{
		return $this->number;
	}
}
