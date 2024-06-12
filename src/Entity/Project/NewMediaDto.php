<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

class NewMediaDto
{
	public function __construct(
		public readonly string $type,
		public readonly string $url,
		public readonly ?string $alt,
		public readonly int $projectId
	) {}
}
