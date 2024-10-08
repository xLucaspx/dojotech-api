<?php

namespace Xlucaspx\Dojotech\Api\Controller\Project;

use Doctrine\ORM\Exception\ORMException;
use DomainException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface, UploadedFileInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Xlucaspx\Dojotech\Api\Entity\Project\NewMediaDto;
use Xlucaspx\Dojotech\Api\Repository\{MediaRepository, ProjectRepository};
use Xlucaspx\Dojotech\Api\Utils\JsonWebToken;

class AddMediasController implements RequestHandlerInterface
{
	private static float $MAX_UPLOAD_SIZE_BYTES = 15 * 1e+6; // 15MB

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
					// Em caso de erro, lembrar de verificar php.ini (post_max_size e upload_max_filesize)
					$errors[] = sprintf("%s => UPLOAD_ERR: %d", $media->getClientFilename(), $media->getError());
					continue;
				}

				if ($media->getSize() > AddMediasController::$MAX_UPLOAD_SIZE_BYTES) {
					$maxUploadSizeMb = AddMediasController::$MAX_UPLOAD_SIZE_BYTES / 1e+6;
					$errors[] = sprintf("%s => Tamanho máximo de %.1f MB excedido", $media->getClientFilename(), $maxUploadSizeMb);
					continue;
				}

				$finfo = new \finfo(FILEINFO_MIME);
				$tempFile = $media->getStream()->getMetadata('uri');
				$mimeType = $finfo->file($tempFile);

				if (!(str_starts_with($mimeType, 'image/') || str_starts_with($mimeType, 'video/'))) {
					$errors[] = "{$media->getClientFilename()} => Formato de arquivo inválido";
					continue;
				}

				$safeFileName = uniqid('upload_') . '_' . pathinfo($media->getClientFilename(), PATHINFO_BASENAME);
				$projectImageDir = __DIR__ . "/../../../public/img/project/$projectId";

				if (!file_exists($projectImageDir)) {
					mkdir($projectImageDir, 0777, true);
				}

				$media->moveTo("$projectImageDir/$safeFileName");
				$mediaUrl = $safeFileName;

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
