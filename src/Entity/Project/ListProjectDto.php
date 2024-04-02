<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

use Doctrine\Common\Collections\Collection;
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;

class ListProjectDto
{
	public readonly int $id;
	public readonly string $name;
	public readonly string $city;
	public readonly string $cause;
	public readonly string $target;
	public readonly string $coverImageUrl;
	public readonly array $sdg;

	public function __construct(Project $project)
	{
		$this->id = $project->id();
		$this->name = $project->name;
		$this->city = $project->city;
		$this->cause = $project->cause;
		$this->target = $project->target;

		$images = $project->medias->filter(fn(Media $media) => preg_match('/^image\/\w+$/', $media->type));
		/** @var Media|null $coverImage */
		$coverImage = $images->get(0);
		$coverImageUrl = $coverImage ? "{$this->id}/{$coverImage->url}" : 'no-media.webp';

		$this->coverImageUrl = "/img/project/{$coverImageUrl}";

		$this->sdg = $project->sdgs->map(fn(Sdg $sdg) => $sdg->id())->toArray();
	}
}
