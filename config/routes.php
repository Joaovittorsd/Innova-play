<?php

declare(strict_types=1);

use Innova\Mvc\Controller\DeleteVideoController;
use Innova\Mvc\Controller\EditVideoController;
use Innova\Mvc\Controller\LoginController;
use Innova\Mvc\Controller\LoginFormController;
use Innova\Mvc\Controller\LogoutController;
use Innova\Mvc\Controller\NewVideoController;
use Innova\Mvc\Controller\RemoveCoverVideoController;
use Innova\Mvc\Controller\VideoFormController;
use Innova\Mvc\Controller\VideoListController;

return [
    'GET|/' => VideoListController::class,
    'GET|/novo-video' => VideoFormController::class,
    'POST|/novo-video' => NewVideoController::class,
    'GET|/editar-video' => VideoFormController::class,
    'POST|/editar-video' => EditVideoController::class,
    'GET|/remover-video' => DeleteVideoController::class,
    'GET|/login' => LoginFormController::class,
    'POST|/login' => LoginController::class,
    'GET|/logout' => LogoutController::class,
    'GET|/remover-capa' => RemoveCoverVideoController::class,
];