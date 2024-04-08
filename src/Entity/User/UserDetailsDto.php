<?php

namespace Xlucaspx\Dojotech\Api\Entity\User;

use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;

class UserDetailsDto
{
	public readonly int $id;
	public readonly string $name;
	public readonly string $username;
	public readonly string $email;
	public readonly string $phone;
	public readonly AddressDto $address;

	public function __construct(User $user)
	{
		$this->id = $user->id();
		$this->name = $user->name();
		$this->username = $user->username();
		$this->email = $user->email();
		$this->phone = $user->phone();
		$this->address = $user->address();
	}
}
