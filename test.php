<?php

use Xlucaspx\Dojotech\Api\Entity\Sdg\Sdg;
use Xlucaspx\Dojotech\Api\Entity\Sdg\SdgDetailsDto;
use Xlucaspx\Dojotech\Api\Helper\EntityManagerCreator;

require_once __DIR__ . '/vendor/autoload.php';

$em = EntityManagerCreator::createEntityManager();
$repository = $em->getRepository(Sdg::class);

$sdgList = $repository->findAll();

foreach ($sdgList as $sdg) {
	$sdgDetails = new SdgDetailsDto($sdg->id(), $sdg->name, $sdg->imageUrl);
	echo json_encode($sdgDetails) . PHP_EOL;
}
