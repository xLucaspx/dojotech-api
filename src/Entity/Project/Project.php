<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\User\User;

#[Entity, Table(name: 'project')]
class Project
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 75)]
	public readonly string $name;
	#[Column(length: 75)]
	public readonly string $cause;
	#[Column(length: 125)]
	public readonly string $goal;
	#[Column(length: 75)]
	public readonly string $target;
	#[Column(length: 50)]
	public readonly string $city;
	#[Column(length: 255)]
	public readonly string $partners;
	#[Column(type: 'text')]
	public readonly string $summary;

	#[ManyToOne(
		targetEntity: User::class,
		inversedBy: 'projects'
	)]
	public readonly User $user;

	#[ManyToMany(
		targetEntity: Sdg::class,
		mappedBy: 'projects'
	)]
	public readonly Collection $sdgs;

	public function __construct()
	{
		$this->sdgs = new ArrayCollection();
	}

	public function id(): int
	{
		return $this->id;
	}
}
