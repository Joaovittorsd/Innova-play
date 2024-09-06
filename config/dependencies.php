<?php

use League\Plates\Engine;

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions([
    PDO::class => function (): PDO {
        $dbPath = __DIR__ . '/../banco.sqlite';
        return new PDO("sqlite:$dbPath");
    },
    Engine::class => function () {
        $templatesPath = __DIR__ . '/../views';
        return new \League\Plates\Engine($templatesPath);
    }
]);

/** @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();

return $container;