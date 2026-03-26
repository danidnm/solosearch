<?php

return [
    'update' => '1sidebar',
    'feed.updates' => [
        'type' => \SoloSearch\Core\Block\Template::class,
        'template' => 'Feed/view/feed.twig',
        'parent' => 'content',
        'message' => '¡Hola desde el nuevo sistema de bloques heredando de 1sidebar y usando referencias planas!'
    ]
];
