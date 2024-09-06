<?php

declare(strict_types=1);

namespace Innova\Mvc\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface Controller
{
    public function processRequisicao(ServerRequestInterface $request): ResponseInterface;
}