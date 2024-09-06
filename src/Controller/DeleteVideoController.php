<?php

declare(strict_types=1);

namespace Innova\Mvc\Controller;

use Innova\Mvc\Helper\FleashMessageTrait;
use Innova\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteVideoController implements RequestHandlerInterface
{
    use FleashMessageTrait;

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            $this->addErrorMessage('Error ao tentar deletar vídeo.');
            return new Response(303, [
                'Location' => '/'
            ]);
        }

        $success = $this->videoRepository->remove($id);

        if ($success === false) {
            $this->addErrorMessage('Error ao tentar deletar vídeo.');
            return new Response(303, [
                'Location' => '/'
            ]);
        } else {
            return new Response(303, [
                'Location' => '/'
            ]);
        }
    }
}