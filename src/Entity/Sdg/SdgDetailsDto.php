<?php

namespace Xlucaspx\Dojotech\Api\Entity\Sdg;

class SdgDetailsDto
{
	public readonly int $id;
	public readonly string $name;
	public readonly string $imageUrl;

	public function __construct(Sdg $sdg)
	{
		$this->id = $sdg->id();
		$this->name = $sdg->name;
		$this->imageUrl = '/img/sdg/' . $sdg->imageUrl;
	}
}
