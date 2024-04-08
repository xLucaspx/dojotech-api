<?php

namespace Xlucaspx\Dojotech\Api\Entity\User\Address;

class AddressDto
{
	public readonly string $postalCode;

	public function __construct(
		string $postalCode,
		public readonly string $address,
		public readonly string $district,
		public readonly ?string $number,
		public readonly ?string $complement,
		public readonly string $city,
		public readonly string $state)
	{
		$this->postalCode = (new PostalCode($postalCode))->code;
	}
}
