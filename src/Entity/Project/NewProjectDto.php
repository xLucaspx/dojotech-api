<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

class NewProjectDto
{
	/** @param int[] $sdgIds */
	public function __construct(
		public readonly string $name,
		public readonly string $cause,
		public readonly string $goal,
		public readonly string $target,
		public readonly string $city,
		public readonly string $partners,
		public readonly string $summary,
		public readonly int $userId,
		public readonly array $sdgIds) {}
}
