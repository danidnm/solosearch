<?php

use Slim\App;

return function (App $app) {

    // Discover module routes
    $modulesDir = __DIR__;
    if (is_dir($modulesDir)) {
        $dirs = scandir($modulesDir);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..' || !is_dir($modulesDir . '/' . $dir)) {
                continue;
            }
            
            $routeFile = $modulesDir . '/' . $dir . '/etc/routes.php';
            if (file_exists($routeFile)) {
                (require $routeFile)($app);
            }
        }
    }
};
