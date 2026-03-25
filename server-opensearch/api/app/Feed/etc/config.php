<?php

return [
    'modules' => [
        'feed' => [
            'version' => '0.0.1'
        ]
    ],
    'layout' => [
        'feed_index' => [
            'update' => '1column',
            'root' => [
                'childs' => [
                    'content' => [
                        'childs' => [
                            'feed.updates' => [
                                'type' => \SoloSearch\Core\Block\Template::class,
                                'template' => 'Feed/view/feed.twig',
                                'message' => '¡Hola desde el nuevo sistema de bloques heredando de 1column!'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
