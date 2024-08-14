<?php

namespace Xlucaspx\Dojotech\Api\Repository;

use Doctrine\ORM\EntityRepository;
use DomainException;
use Xlucaspx\Dojotech\Api\Entity\Project\{ListProjectDto, NewProjectDto, Project, ProjectDetailsDto, UpdateProjectDto};
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\User\User;

class ProjectRepository extends EntityRepository
{
	/** @return int Returns the generated ID */
	public function add(NewProjectDto $projectData): int
	{
		$em = $this->getEntityManager();

		$user = $em->find(User::class, $projectData->userId);
		$sdg = array_map(
			fn(int $sdgId) => $em->find(Sdg::class, filter_var($sdgId, FILTER_VALIDATE_INT)),
			$projectData->sdgIds);

		$project = new Project($projectData, $user, $sdg);

		$em->persist($project);
		$em->flush();

		return $project->id();
	}

	public function update(UpdateProjectDto $projectData): void
	{
		$em = $this->getEntityManager();

		/** @var Project $project */
		$project = $this->find($projectData->id);

		if ($projectData->userId !== $project->user()->id()) {
			throw new DomainException('Não é possível modificar o projeto de outros usuários!');
		}

		$sdg = array_map(
			fn(int $sdgId) => $em->find(Sdg::class, filter_var($sdgId, FILTER_VALIDATE_INT)),
			$projectData->sdgIds);

		$project->update($projectData, $sdg);

		$this->getEntityManager()->flush();
	}

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

			$sdg = $project->sdg();
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

	public function delete(Project $project): void
	{
		$em = $this->getEntityManager();
		$em->remove($project);
		$em->flush();
	}

	public function getReportData()
	{
		$sql = <<<END
			SELECT
				sdg.id AS 'sdg_id',
				sdg.name AS 'sdg_name',
				COUNT(project_id) AS 'total_projects'
			FROM project_sdg
				INNER JOIN sdg ON project_sdg.sdg_id = sdg.id
			GROUP BY sdg.id;
		END;

		$em = $this->getEntityManager();
		$statement = $em->getConnection()->prepare($sql);
		return $statement->executeQuery()->fetchAllAssociative();
	}
}
