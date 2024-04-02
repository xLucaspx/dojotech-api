<?php

namespace Xlucaspx\Dojotech\Api\Repository;

use Doctrine\ORM\EntityRepository;
use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Entity\Project\ProjectDetailsDto;

class ProjectRepository extends EntityRepository
{
	private string $projectClass = Project::class;

	/** @return ProjectDetailsDto[] */
	public function filterBy(array $filter = []): array
	{
		$dql = $this->createQueryBuilder('project')
			->addSelect('user')
			->addSelect('sdg')
			->addSelect('media')
			->leftJoin('project.user', 'user')
			->leftJoin('project.sdg', 'sdg')
			->leftJoin('project.medias', 'media');

		if ($filter) {
			if ($filter['filter'] === 'sdg') {
				return $this->filterBySdg($filter['value']);
			}

			$dql->where("project.{$filter['filter']} LIKE :value")
				->setParameter(':value', "%{$filter['value']}%");
		}

		$projects = $dql->getQuery()->getResult();

		$list = array_map(
			fn(Project $project) => new ListProjectDto($project),
			$projects
		);

		return $list;
	}

	/** @return ProjectDetailsDto[] */
	public function filterBySdg(string $sdgName): array
	{
		$projects = $this->findAll();
		$filtered = [];

		/** @var Project $project */
		foreach ($projects as $project) {
			if (isset($filtered[$project->id()])) {
				continue;
			}

			$sdg = $project->sdg;
			foreach ($sdg as $value) {
				$name = "{$value->id()} - {$value->name}";
				if (preg_match("/$sdgName/i", $name)) {
					$filtered[$project->id()] = $project;
				}
			}
		}

		$list = array_map(
			fn(Project $project) => new ListProjectDto($project),
			array_values($filtered)
		);

		return $list;
	}
}
