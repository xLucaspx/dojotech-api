<?php

namespace Xlucaspx\Dojotech\Api\Repository;

use Doctrine\ORM\EntityRepository;
use DomainException;
use Xlucaspx\Dojotech\Api\Entity\Project\{Media, NewMediaDto, Project};

class MediaRepository extends EntityRepository
{
	/** @return int Returns the generated ID */
	public function add(NewMediaDto $mediaDto): int
	{
		$em = $this->getEntityManager();

		$project = $em->find(Project::class, $mediaDto->projectId);

		$media = new Media($mediaDto, $project);

		$em->persist($media);
		$em->flush();

		return $media->id();
	}

	public function delete(Media $media): bool
	{
		$projectId = $media->project->id();
		$mediaPath = realpath(__DIR__ . "/../../public/img/project/{$projectId}/{$media->url}");

		if (!file_exists($mediaPath)) {
			throw new DomainException("O caminho da mídia não foi encontrado!");
		}

		$deleted = unlink($mediaPath);

		if (!$deleted) {
			throw new DomainException("Não foi possível deletar a mídia!");
		}

		$em = $this->getEntityManager();
		$em->remove($media);
		$em->flush();

		return true;
	}
}
