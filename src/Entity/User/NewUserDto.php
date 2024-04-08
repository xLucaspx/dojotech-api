<?php

namespace Xlucaspx\Dojotech\Api\Entity\User;

use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\DataTypes\Email;
use Xlucaspx\Dojotech\Api\Entity\User\DataTypes\Phone;
use Xlucaspx\Dojotech\Api\Entity\User\DataTypes\Username;

class NewUserDto
{
	public readonly Email $email;
	public readonly Username $username;
	public readonly Phone $phone;
	public readonly string $passwordHash;

	public function __construct(
		public readonly string $name,
		string $email,
		string $username,
		string $phone,
		string $password,
		public readonly AddressDto $address)
	{
		$this->username = new Username($username);
		$this->email = new Email($email);
		$this->phone = new Phone($phone);
		$this->passwordHash = password_hash($password, PASSWORD_ARGON2ID);
	}
}
