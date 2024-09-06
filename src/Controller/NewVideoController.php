<?php

declare(strict_types=1);

namespace Innova\Mvc\Controller;

use Innova\Mvc\Entity\Video;
use Innova\Mvc\Helper\FleashMessageTrait;
use Innova\Mvc\Repository\VideoRepository;
use Innova\Mvc\Service\FileUploader;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NewVideoController implements RequestHandlerInterface
{
    use FleashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    { 
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();
        $url = filter_var($requestBody['url'], FILTER_VALIDATE_URL);
        if ($url === false) {
            $this->addErrorMessage('Url Inválida.');
            return new Response(404, [
                'Location' => '/editar-video'
            ]);
        }

        $titulo = filter_var($requestBody['titulo']);
        if ($titulo === false) {
            $this->addErrorMessage('Título não informado.');
            return new Response(404, [
                'Location' => '/editar-video'
            ]);
        }

        $video = new Video($url, $titulo);

        $files = $request->getUploadedFiles();
        /** @var UploadedFileInterface $uploadedImage */
        $uploadedImage = $files['image'] ?? null;

        if ($uploadedImage && $uploadedImage->getError() === UPLOAD_ERR_OK) {
            $fileUpload = new FileUploader($uploadedImage);
            $uploadedFileName = $fileUpload->upload();

            if ($uploadedFileName !== null) {
                $video->setFilePath($uploadedFileName);
            }
        }
        
        $success = $this->videoRepository->add($video);
        if ($success === false) {
            $this->addErrorMessage('Error ao cadastrar vídeo.');
            return new Response(404, [
                'Location' => '/novo-video'
            ]);
        } else {
            return new Response(302, [
                'Location' => '/'
            ]);
        }
    }
}
