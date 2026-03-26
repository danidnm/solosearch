#!/usr/bin/env php
<?php

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

// Container builder
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

if ($argc < 2) {
    echo "Usage: ./bin/solosearch.php <command>\n";
    echo "Example: ./bin/solosearch.php core:install\n";
    exit(1);
}

$parts = explode(':', $argv[1]);

if (count($parts) !== 2) {
    echo "Error: Invalid command format. Expected format is 'group:command'.\n";
    exit(1);
}

$group = ucfirst(strtolower($parts[0]));
$commandPart = $parts[1];
$commandName = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $commandPart)));
$className = "SoloSearch\\{$group}\\Console\\{$commandName}";

// Get command class and run
try {
    $command = $container->get($className);
    $command->run($argv);
} catch (\DI\NotFoundException $e) {
    echo "Error: Command '{$argv[1]}' not found (Class: {$className}).\n";
    exit(1);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}


