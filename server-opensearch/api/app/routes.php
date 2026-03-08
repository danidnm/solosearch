<?php

use Slim\App;

return function (App $app) {

    // API v1
    $app->group('/api/v1', function ($group) {
        $group->post('/feed', \SoloSearch\Feed\Controller\V1\FeedController::class . ':post');
        
        // Other feed routes if needed
        $group->get('/feed', \SoloSearch\Feed\Controller\V1\FeedController::class . ':get');
        $group->put('/feed', \SoloSearch\Feed\Controller\V1\FeedController::class . ':put');
        $group->delete('/feed', \SoloSearch\Feed\Controller\V1\FeedController::class . ':delete');
    })->add(\SoloSearch\Core\Middleware\AuthMiddleware::class);
};
