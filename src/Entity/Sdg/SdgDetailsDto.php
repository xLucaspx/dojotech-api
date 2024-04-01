<?php

namespace Xlucaspx\Dojotech\Api\Entity\Sdg;

class SdgDetailsDto
{
	public readonly string $imageUrl;

	public function __construct(
		public readonly int $id,
		public readonly string $name,
		string $imageUrl
	)
	{
		$this->imageUrl = '/img/sdg/' . $imageUrl;
	}
}
