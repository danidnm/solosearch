<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\DatabaseManager;
use Psr\Container\ContainerInterface;

return [

    // // This factory creates the Capsule, boots Eloquent,
    // // and makes it available globally (which some models still rely on)
    // \SoloSearch\Core\Model\BootstrapConfig::class => function (): \SoloSearch\Core\Model\BootstrapConfig {
    //     return new \SoloSearch\Core\Model\BootstrapConfig();
    // },

    // This factory creates the Capsule, boots Eloquent,
    // and makes it available globally (which some models still rely on)
    Capsule::class => function (ContainerInterface $c): Capsule {
        $config = $c->get(\SoloSearch\Core\Model\BootstrapConfig::class);
        $driver = $config->get('app/db/driver');
        $host = $config->get('app/db/host');
        $database = $config->get('app/db/database');
        $user = $config->get('app/db/username');
        $pass = $config->get('app/db/password');
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => $driver,
            'host'      => $host,
            'database'  => $database,
            'username'  => $user,
            'password'  => $pass,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    },

    // This is what you'll actually inject: the DatabaseManager
    DatabaseManager::class => function (ContainerInterface $c): DatabaseManager {
        return $c->get(Capsule::class)->getDatabaseManager();
    },

    // Inject Twig template engine
    \Slim\Views\Twig::class => function (ContainerInterface $c): \Slim\Views\Twig {
        // Specify the path to your Twig templates
        // We'll use the app directory as a base, so templates can be structured by module
        return \Slim\Views\Twig::create(__DIR__ . '/../app', ['cache' => false]);
    },
];
