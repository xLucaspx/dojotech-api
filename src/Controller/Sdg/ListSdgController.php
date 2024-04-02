<?php

namespace Xlucaspx\Dojotech\Api\Controller\Sdg;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\Sdg\SdgDetailsDto;
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
			fn(Sdg $sdg) => new SdgDetailsDto($sdg->id(), $sdg->name, $sdg->imageUrl),
			$sdgList
		);

		return new Response(200, body: json_encode($dtoList));
	}
}
