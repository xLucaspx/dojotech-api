<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, Table};
use Xlucaspx\Dojotech\Api\Repository\MediaRepository;

#[Entity(repositoryClass: MediaRepository::class), Table(name: 'media')]
class Media
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 25)]
	public readonly string $type;
	#[Column(length: 175)]
	public readonly string $url;
	#[Column(length: 255, nullable: true)]
	public readonly ?string $alt;
	#[ManyToOne(
		targetEntity: Project::class,
		inversedBy: 'medias',
	), JoinColumn(nullable: false)]
	public readonly Project $project;

	public function __construct(NewMediaDto $dto, Project $project)
	{
		$this->type = $dto->type;
		$this->url = $dto->url;
		$this->alt = $dto->alt;
		$this->project = $project;
	}

	public function id(): int
	{
		return $this->id;
	}
}
