<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\DatabaseManager;
use Psr\Container\ContainerInterface;

return [

    // This factory creates the Capsule, boots Eloquent,
    // and makes it available globally (which some models still rely on)
    Capsule::class => function (ContainerInterface $c): Capsule {

        $settings = $c->get('settings')['db'];
        $capsule = new Capsule;
        $capsule->addConnection($settings);
        $capsule->setAsGlobal(); // Necessary for some Eloquent features
        $capsule->bootEloquent();

        return $capsule;
    },

    // This is what you'll actually inject: the DatabaseManager
    DatabaseManager::class => function (ContainerInterface $c): DatabaseManager {
        return $c->get(Capsule::class)->getDatabaseManager();
    },
];
