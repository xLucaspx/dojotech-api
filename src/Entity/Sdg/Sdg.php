<?php

namespace Xlucaspx\Dojotech\Api\Entity\Sdg;

use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, ManyToMany, Table};
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Repository\SdgRepository;

#[Entity(repositoryClass: SdgRepository::class), Table(name: 'sdg')]
class Sdg
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 50)]
	public readonly string $name;
	#[Column(name: 'image_url', length: 175)]
	public readonly string $imageUrl;

	#[ManyToMany(
		targetEntity: Project::class,
		mappedBy: 'sdg',
	)]
	public readonly Collection $projects;

	public function __construct()
	{
		$this->projects = new ArrayCollection();
	}

	public function id(): int
	{
		return $this->id;
	}
}
