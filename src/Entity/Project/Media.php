<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'media')]
class Media
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 25)]
	public readonly string $type;
	#[Column(length: 175)]
	public readonly string $url;
	#[Column(length: 255, nullable: true)]
	public readonly string $alt;
	#[ManyToOne(
		targetEntity: Project::class,
		inversedBy: 'medias',
	), JoinColumn(nullable: false)]
	public readonly Project $project;

	public function id(): int
	{
		return $this->id;
	}
}
