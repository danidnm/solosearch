<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Container builder
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// Routes
(require __DIR__ . '/routes.php')($app);

return $app;
