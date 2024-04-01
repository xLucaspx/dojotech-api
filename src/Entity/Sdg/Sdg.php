<?php

namespace Xlucaspx\Dojotech\Api\Entity\Sdg;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'sdg')]
class Sdg
{
	#[Column, Id, GeneratedValue]
	private int $id;
	#[Column(length: 50)]
	public readonly string $name;
	#[Column(name: 'image_url', length: 175)]
	public readonly string $imageUrl;

	public function id()
	{
		return $this->id;
	}
}
