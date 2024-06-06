<?php

namespace Xlucaspx\Dojotech\Api\Entity\Project;

class MediaDetailsDto
{
	public readonly int $id;
	public readonly string $type;
	public readonly string $url;
	public readonly ?string $alt;

	public function __construct(Media $media)
	{
		$this->id = $media->id();
		$this->type = $media->type;
		$this->url = "/img/project/{$media->project->id()}/$media->url";
		$this->alt = $media->alt;
	}
}
