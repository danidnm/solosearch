<?php

use Slim\App;

return function (App $app) {

     // Discover module routes
     $container = $app->getContainer();
     /** @var \SoloSearch\Cache\Model\CacheRepository $cache */
     $cache = $container->get(\SoloSearch\Cache\Model\CacheRepository::class);
     $cacheKey = 'app_module_route_files';
     
     $routeFiles = $cache->get($cacheKey);
 
     if ($routeFiles === null) {
         $routeFiles = [];
         $modulesDir = __DIR__;
         if (is_dir($modulesDir)) {
             $dirs = scandir($modulesDir);
             foreach ($dirs as $dir) {
                 if ($dir === '.' || $dir === '..' || !is_dir($modulesDir . '/' . $dir)) {
                     continue;
                 }
                 
                 $routeFile = $modulesDir . '/' . $dir . '/etc/routes.php';
                 if (file_exists($routeFile)) {
                     $routeFiles[] = $routeFile;
                 }
             }
         }
         $cache->set($cacheKey, $routeFiles);
     }
 
     foreach ($routeFiles as $file) {
         (require $file)($app);
     }
 };
