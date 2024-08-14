<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

use Xlucaspx\Dojotech\Api\Entity\Sdg\{Sdg, SdgDetailsDto};

class ProjectDetailsDto
{
	public readonly int $id;
	public readonly string $name;
	public readonly string $city;
	public readonly string $cause;
	public readonly string $goal;
	public readonly string $target;
	public readonly string $summary;
	public readonly string $partners;
	public readonly array $medias;
	public readonly array $sdg;
	public readonly int $userId;

	public function __construct(Project $project)
	{
		$this->id = $project->id();
		$this->name = $project->name();
		$this->city = $project->city();
		$this->cause = $project->cause();
		$this->goal = $project->goal();
		$this->target = $project->target();
		$this->summary = $project->summary();
		$this->partners = $project->partners();
		$this->medias = $project->medias()->map(fn(Media $media) => new MediaDetailsDto($media))->toArray();
		$this->sdg = $project->sdg()->map(fn(Sdg $sdg) => new SdgDetailsDto($sdg))->toArray();
		$this->userId = $project->user()->id();
	}
}
