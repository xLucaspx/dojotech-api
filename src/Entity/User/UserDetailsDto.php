<?php

namespace Xlucaspx\Dojotech\Api\Entity\User;

use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;

class UserDetailsDto
{
	public readonly int $id;
	public readonly string $name;
	public readonly string $username;
	public readonly string $email;
	public readonly string $phone;
	public readonly string $postalCode;
	public readonly string $address;
	public readonly ?string $number;
	public readonly ?string $complement;
	public readonly string $district;
	public readonly string $city;
	public readonly string $state;
	public readonly array $projects;

	public function __construct(User $user)
	{
		$this->id = $user->id();
		$this->name = $user->name;
		$this->username = $user->username;
		$this->email = $user->email;
		$this->phone = $user->phone;
		$this->postalCode = $user->postalCode;
		$this->address = $user->address;
		$this->number = $user->number;
		$this->complement = $user->complement;
		$this->district = $user->district;
		$this->city = $user->city;
		$this->state = $user->state;
		$this->projects = $user->projects->map(fn(Project $project) => new ListProjectDto($project))->toArray();
	}
}
