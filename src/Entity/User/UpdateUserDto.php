<?php

namespace Xlucaspx\Dojotech\Api\Entity\User;

use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\DataTypes\{Email, Phone, Username};

class UpdateUserDto
{
	public readonly Email $email;
	public readonly Username $username;
	public readonly Phone $phone;
	public readonly ?string $passwordHash;

	public function __construct(
		public readonly int $id,
		public readonly string $name,
		string $email,
		string $username,
		string $phone,
		public readonly AddressDto $address,
		?string $password = null)
	{
		$this->email = new Email($email);
		$this->username = new Username($username);
		$this->phone = new Phone($phone);
		if ($password) {
			$this->passwordHash = password_hash($password, PASSWORD_ARGON2ID);
		}
	}
}
