<?php

namespace Xlucaspx\Dojotech\Api\Entity\User;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, OneToMany, Table};
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Repository\UserRepository;

#[Entity(repositoryClass: UserRepository::class), Table(name: 'user')]
class User
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 75)]
	public readonly string $name;
	#[Column(length: 50, unique: true)]
	public readonly string $email;
	#[Column(length: 20, unique: true)]
	public readonly string $username;
	#[Column(length: 13)]
	public readonly string $phone;
	#[Column(name: 'password_hash', length: 255)]
	public readonly string $passwordHash;

	#[OneToMany(
		targetEntity: Project::class,
		mappedBy: 'user',
		cascade: ["persist", "remove"],
		fetch: 'LAZY'
	)]
	public readonly Collection $projects;

	// TODO: address DTO
	#[Column(name: 'postal_code', length: 8)]
	public readonly string $postalCode;
	#[Column(length: 100)]
	public readonly string $address;
	#[Column(length: 50)]
	public readonly string $district;
	#[Column(length: 10, nullable: true)]
	public readonly string $number;
	#[Column(length: 50, nullable: true)]
	public readonly string $complement;
	#[Column(length: 50)]
	public readonly string $city;
	#[Column(length: 2)]
	public readonly string $state;

	public function __construct()
	{
		$this->projects = new ArrayCollection();
	}

	public function id(): int
	{
		return $this->id;
	}
}
