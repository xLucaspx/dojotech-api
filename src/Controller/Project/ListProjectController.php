<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;

class ListProjectController implements RequestHandlerInterface
{

	public function __construct(
		private ProjectRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		$queryParams = $request->getQueryParams();
		$filter = [];

		if ($queryParams) {
			$key = array_key_first($queryParams);
			$value = $queryParams[$key];
			$filter['filter'] = $key;
			$filter['value'] = $value;
		}

		$projectList = $this->repository->filterBy($filter);
		return new Response(200, body: json_encode($projectList));
	}
}
