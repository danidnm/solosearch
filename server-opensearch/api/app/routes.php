<?php

use Slim\App;

return function (App $app) {

    // API v1 (Private, Token-based)
    $app->group('/api/v1', function ($group) {
        $group->post('/feed', \SoloSearch\Feed\Controller\Api\V1\FeedController::class . ':post');
        $group->get('/feed', \SoloSearch\Feed\Controller\Api\V1\FeedController::class . ':get');
        $group->put('/feed', \SoloSearch\Feed\Controller\Api\V1\FeedController::class . ':put');
        $group->delete('/feed', \SoloSearch\Feed\Controller\Api\V1\FeedController::class . ':delete');
    })->add(\SoloSearch\Core\Middleware\AuthMiddleware::class);

    // Web (Public, Browser-based)
    $app->get('/feed', \SoloSearch\Feed\Controller\Web\FeedController::class . ':index');
};
