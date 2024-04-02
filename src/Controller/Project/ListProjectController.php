<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\ListProjectDto;
use Xlucaspx\Dojotech\Api\Entity\Project\Project;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;

class ListProjectController implements RequestHandlerInterface
{

	public function __construct(
		private ProjectRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		/** @var Project[] $projectList */
		$projectList = $this->repository->findAll();
		$dtoList = array_map(
			fn(Project $project) => new ListProjectDto($project),
			$projectList
		);

		return new Response(200, body: json_encode($dtoList));
	}
}
