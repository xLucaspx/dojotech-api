<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Doctrine\ORM\Exception\ORMException;
use DomainException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\NewMediaDto;
use Xlucaspx\Dojotech\Api\Repository\MediaRepository;
use Xlucaspx\Dojotech\Api\Repository\ProjectRepository;
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class AddMediasController implements RequestHandlerInterface
{
	public function __construct(
		private ProjectRepository $projectRepository,
		private MediaRepository $mediaRepository
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface
	{
		try {
			$queryParams = $request->getQueryParams();

			if (!isset($queryParams['projectId'])) {
				return new Response(400, body: json_encode(['error' => 'O ID do projeto não foi informado!']));
			}

			$projectId = filter_var($queryParams['projectId'], FILTER_VALIDATE_INT);

			$project = $this->projectRepository->find($projectId);

			if (!$project) {
				return new Response(404, body: json_encode(['error' => "Nenhum projeto encontrado para o ID $projectId"]));
			}

			$token = $request->getHeaderLine('authorization');
			$decoded = JsonWebToken::decode($token);
			$userId = $decoded['sub'];

			if ($userId !== $project->user()->id()) {
				return new Response(401, body: json_encode(['error' => 'Não é possível modificar projetos de outros usuários!']));
			}

			$medias = $request->getUploadedFiles();
			$errors = [];

			foreach ($medias as $media) {
				/** @var UploadedFileInterface $media */
				if ($media->getError() !== UPLOAD_ERR_OK) {
//					return new Response(500, body: json_encode(['error' => 'Ocorreu um erro ao tentar realizar o upload das imagens!']));
					$errors[] = $media->getClientFilename();
					continue;
				}

				$finfo = new \finfo(FILEINFO_MIME);
				$tempFile = $media->getStream()->getMetadata('uri');
				$mimeType = $finfo->file($tempFile);

				$mediaUrl = '';
				// TODO: handle videos
				// TODO: check upload size
				if (str_starts_with($mimeType, 'image/')) {
					$safeFileName = uniqid('upload_') . '_' . pathinfo($media->getClientFilename(), PATHINFO_BASENAME);

					$projectImageDir = __DIR__ . "/../../../public/img/project/$projectId";
					if (!file_exists($projectImageDir)) {
						mkdir($projectImageDir, 0777, true);
					}

					$media->moveTo("$projectImageDir/$safeFileName");
					$mediaUrl = $safeFileName;
				}

				$dto = new NewMediaDto($media->getClientMediaType(), $mediaUrl, '', $projectId);
				$this->mediaRepository->add($dto);
			}

			if (!$errors) {
				return new Response(201);
			}
			$errorMessage = "Não foi possível fazer o upload dos seguintes arquivos:\n" . implode("\n", $errors);
			return new Response(202, body: json_encode(['error' => $errorMessage]));
		} catch (DomainException $e) {
			return new Response(400, body: json_encode(['error' => $e->getMessage()]));
		} catch (ORMException $e) {
			return new Response(500, body: json_encode(['error' => $e->getMessage() . PHP_EOL . $e->getTraceAsString()]));
		}
	}
}
