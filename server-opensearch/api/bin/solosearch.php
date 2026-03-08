<?php

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

// Container builder
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

if ($argc < 2) {
    echo "Usage: php bin/solosearch <command>\n";
    echo "Example: php bin/solosearch core:install\n";
    exit(1);
}

$parts = explode(':', $argv[1]);

if (count($parts) !== 2) {
    echo "Error: Invalid command format. Expected format is 'group:command'.\n";
    exit(1);
}

$group = ucfirst(strtolower($parts[0]));
$commandName = ucfirst($parts[1]);
$className = "SoloSearch\\{$group}\\Console\\{$commandName}";

// Get command class and run
$command = $container->get($className);
$command->run();


