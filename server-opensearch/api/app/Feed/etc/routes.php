<?php
 
 use Slim\App;
 use SoloSearch\Core\Middleware\AuthMiddleware;
 use SoloSearch\Feed\Controller\Api\V1\FeedController;
 use SoloSearch\Feed\Controller\Web\FeedController as WebFeedController;
 
 return function (App $app) {
 
     // API v1 (Private, Token-based)
     $app->group('/api/v1', function ($group) {
         $group->post('/feed', FeedController::class . ':post');
         $group->get('/feed', FeedController::class . ':get');
         $group->put('/feed', FeedController::class . ':put');
         $group->delete('/feed', FeedController::class . ':delete');
     })->add(AuthMiddleware::class);
 
     // Web (Public, Browser-based)
     $app->get('/feed', WebFeedController::class . ':index');
 };
