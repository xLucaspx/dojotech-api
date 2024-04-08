<?php

namespace Xlucaspx\Dojotech\Api\Entity\User;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, OneToMany, Table};
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Entity\User\Address\AddressDto;
use Xlucaspx\Dojotech\Api\Entity\User\Address\PostalCode;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;

#[Entity(repositoryClass: UserRepository::class), Table(name: 'user')]
class User
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 75)]
	private string $name;
	#[Column(length: 50, unique: true)]
	private string $email;
	#[Column(length: 20, unique: true)]
	private string $username;
	#[Column(length: 13)]
	private string $phone;
	#[Column(name: 'password_hash', length: 255)]
	private string $passwordHash;

	#[OneToMany(
		targetEntity: Project::class,
		mappedBy: 'user',
		cascade: ["persist", "remove"],
		fetch: 'LAZY'
	)]
	private Collection $projects;

	// TODO: address DTO
	#[Column(name: 'postal_code', length: 8)]
	private string $postalCode;
	#[Column(length: 100)]
	private string $address;
	#[Column(length: 50)]
	private string $district;
	#[Column(length: 10, nullable: true)]
	private ?string $number;
	#[Column(length: 50, nullable: true)]
	private ?string $complement;
	#[Column(length: 50)]
	private string $city;
	#[Column(length: 2)]
	private string $state;

	public function __construct(NewUserDto $dto)
	{
		$this->name = $dto->name;
		$this->email = $dto->email->email;
		$this->username = $dto->username->username;
		$this->phone = $dto->phone->number;
		$this->passwordHash = $dto->passwordHash;

		$this->postalCode = $dto->address->postalCode;
		$this->address = $dto->address->address;
		$this->district = $dto->address->district;
		$this->number = $dto->address->number;
		$this->complement = $dto->address->complement;
		$this->city = $dto->address->city;
		$this->state = $dto->address->state;

		$this->projects = new ArrayCollection();
	}

	public function update(UpdateUserDto $data): void
	{
		$this->name = $data->name;
		$this->email = $data->email->email;
		$this->username = $data->username->username;
		$this->phone = $data->phone->number;

		$this->postalCode = $data->address->postalCode;
		$this->address = $data->address->address;
		$this->district = $data->address->district;
		$this->number = $data->address->number;
		$this->complement = $data->address->complement;
		$this->city = $data->address->city;
		$this->state = $data->address->state;

		if (isset($data->passwordHash)) {
			$this->passwordHash = $data->passwordHash;
		}
	}

	public function id(): int
	{
		return $this->id;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function email(): string
	{
		return $this->email;
	}

	public function username(): string
	{
		return $this->username;
	}

	public function phone(): string
	{
		return $this->phone;
	}

	public function passwordHash(): string
	{
		return $this->passwordHash;
	}

	public function address(): AddressDto
	{
		return new AddressDto($this->postalCode,
			$this->address,
			$this->district,
			$this->number,
			$this->complement,
			$this->city,
			$this->state);
	}

	public function projects(): Collection
	{
		return $this->projects;
	}
}
