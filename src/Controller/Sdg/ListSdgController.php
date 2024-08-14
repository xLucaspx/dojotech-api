<?php

namespace Xlucaspx\Dojotech\Api\Controller\Sdg;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Sdg\{Sdg, SdgDetailsDto};
use Xlucaspx\Dojotech\Api\Repository\SdgRepository;

class ListSdgController implements RequestHandlerInterface
{
	public function __construct(
		private SdgRepository $repository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		/** @var Sdg[] $sdgList */
		$sdgList = $this->repository->findAll();
		$dtoList = array_map(
			fn(Sdg $sdg) => new SdgDetailsDto($sdg),
			$sdgList
		);

		return new Response(200, body: json_encode($dtoList));
	}
}
