<?php

declare(strict_types=1);

namespace Innova\Mvc\Controller;

use Innova\Mvc\Helper\FleashMessageTrait;
use Innova\Mvc\Repository\UserRepository;
use Nyholm\Psr7\Response;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginController implements RequestHandlerInterface
{
    use FleashMessageTrait;

    private PDO $pdo;

    public function __construct(private UserRepository $userRepository)
    { 
        $dbPath = __DIR__ . '/../../banco.sqlite';
        $this->pdo = new PDO("sqlite:$dbPath");
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();
        $email = filter_var($requestBody['email'] ?? null, FILTER_VALIDATE_EMAIL);
        $password = $requestBody['password'] ?? '';

         if (!$email || !$password) {
            $this->addErrorMessage('E-mail ou senha não pode ser vazio.');
            return new Response(400, ['Location' => '/login']);
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user === null || !$this->userRepository->verifyPassword($user, $password)) {
            $this->addErrorMessage('Usuário ou senhas inválidos.');
            return new Response(404, ['Location' => '/login']);
        }

        if ($this->userRepository->needsRehash($user->password)) {
            $this->userRepository->rehashPassword($user, $password);
        }

        $_SESSION['logado'] = true;

        return new Response(302, ['Location' => '/']);
    }
}