<?php

declare(strict_types=1);

namespace Innova\Mvc\Controller;

use Innova\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RemoveCoverVideoController implements RequestHandlerInterface
{

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            return new Response(400, [
                'Location' => '/'
            ]);
        }

        $success = $this->videoRepository->removeCover($id);

        if ($success === false) {
            return new Response(404, [
                'Location' => '/'
            ]);
        } else {
            return new Response(303, [
                'Location' => '/'
            ]);
        }
    }
}