<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToMany, ManyToOne, OneToMany, Table};
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\User\User;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;

#[Entity(repositoryClass: ProjectRepository::class), Table(name: 'project')]
class Project
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 75)]
	private string $name;
	#[Column(length: 75)]
	private string $cause;
	#[Column(length: 125)]
	private string $goal;
	#[Column(length: 75)]
	private string $target;
	#[Column(length: 50)]
	private string $city;
	#[Column(length: 255, nullable: true)]
	private string $partners;
	#[Column(type: 'text')]
	private string $summary;

	#[ManyToOne(
		targetEntity: User::class,
		inversedBy: 'projects',
	), JoinColumn(nullable: false)]
	private User $user;

	#[ManyToMany(
		targetEntity: Sdg::class,
		inversedBy: 'projects',
		fetch: 'EAGER',
	)]
	private Collection $sdg;

	#[OneToMany(
		targetEntity: Media::class,
		mappedBy: 'project',
		cascade: ["persist", "remove"],
		fetch: 'LAZY'
	)]
	private Collection $medias;

	/** @param Sdg[] $sdg */
	public function __construct(NewProjectDto $dto, User $user, array $sdg)
	{
		$this->name = $dto->name;
		$this->cause = $dto->cause;
		$this->goal = $dto->goal;
		$this->target = $dto->target;
		$this->city = $dto->city;
		$this->partners = $dto->partners;
		$this->summary = $dto->summary;
		$this->user = $user;

		$this->sdg = new ArrayCollection($sdg);
		$this->medias = new ArrayCollection();
	}

	/** @param Sdg[] $sdg */
	public function update(UpdateProjectDto $dto, array $sdg)
	{
		$this->name = $dto->name;
		$this->cause = $dto->cause;
		$this->goal = $dto->goal;
		$this->target = $dto->target;
		$this->city = $dto->city;
		$this->partners = $dto->partners;
		$this->summary = $dto->summary;

		$this->sdg = new ArrayCollection($sdg);
	}

	public function id(): ?int
	{
		return $this->id;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function cause(): string
	{
		return $this->cause;
	}

	public function goal(): string
	{
		return $this->goal;
	}

	public function target(): string
	{
		return $this->target;
	}

	public function city(): string
	{
		return $this->city;
	}

	public function partners(): string
	{
		return $this->partners;
	}

	public function summary(): string
	{
		return $this->summary;
	}

	public function user(): User
	{
		return $this->user;
	}

	public function sdg(): Collection
	{
		return $this->sdg;
	}

	public function medias(): Collection
	{
		return $this->medias;
	}
}
