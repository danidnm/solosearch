<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\DatabaseManager;
use Psr\Container\ContainerInterface;

return [

    // This factory creates the Capsule, boots Eloquent,
    // and makes it available globally (which some models still rely on)
    Capsule::class => function (ContainerInterface $c): Capsule {

        $config = $c->get('SoloSearch\Core\Model\Config');
        $driver = $config->get('app/db/driver');
        $host = $config->get('app/db/db_host');
        $database = $config->get('app/db/db_database');
        $user = $config->get('app/db/db_username');
        $pass = $config->get('app/db/db_password');
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
];
