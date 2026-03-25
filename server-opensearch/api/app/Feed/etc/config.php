<?php

return [
    'modules' => [
        'feed' => [
            'version' => '0.0.1'
        ]
    ],
    'layout' => [
        'feed_index' => [
            'root' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Core/view/layout/base.twig',
                'childs' => [
                    'body' => [
                        'type' => \SoloSearch\Core\Block\Template::class,
                        'template' => 'Feed/view/feed.twig',
                        'message' => '¡Hola desde el nuevo sistema de bloques!'
                    ]
                ]
            ]
        ]
    ]
];
