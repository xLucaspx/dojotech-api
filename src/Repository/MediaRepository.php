<?php

namespace Xlucaspx\Dojotech\Api\Repository;

use Doctrine\ORM\EntityRepository;
use DomainException;
use Xlucaspx\Dojotech\Api\Entity\Project\Media;

class MediaRepository extends EntityRepository
{
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
