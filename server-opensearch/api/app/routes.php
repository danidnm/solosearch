<?php

use Slim\App;

return function (App $app) {

    // Feeds manageement
    $app->get('/feeds', \SoloSearch\Feeds\Controller\FeedsController::class . ':get');
    $app->post('/feeds', \SoloSearch\Feeds\Controller\FeedsController::class . ':post');
    $app->put('/feeds', \SoloSearch\Feeds\Controller\FeedsController::class . ':put');
    $app->delete('/feeds', \SoloSearch\Feeds\Controller\FeedsController::class . ':delete');
};
