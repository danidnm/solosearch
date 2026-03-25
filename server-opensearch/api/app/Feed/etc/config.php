<?php

return [
    'modules' => [
        'feed' => [
            'version' => '0.0.1'
        ]
    ],
    'layout' => [
        // 1. Modificamos GLOBALMENTE el handle 1sidebar para añadir nuestro widget
        '1sidebar' => [
            'root' => [
                'childs' => [
                    'sidebar' => [
                        'childs' => [
                            'feed.sidebar.widget' => [
                                'type' => \SoloSearch\Core\Block\Template::class,
                                'template' => 'Feed/view/sidebar_widget.twig',
                                'title' => 'Novedades',
                                'position' => 10 // Opcional, para ordenarlo respecto a otros
                            ]
                        ]
                    ]
                ]
            ]
        ],
        
        // 2. Usamos 1sidebar para nuestra página de feed, y como hemos modificado 
        // 1sidebar arriba, ¡nuestro widget aparecerá mágicamente!
        'feed_index' => [
            'update' => '1sidebar',
            'root' => [
                'childs' => [
                    'content' => [
                        'childs' => [
                            'feed.updates' => [
                                'type' => \SoloSearch\Core\Block\Template::class,
                                'template' => 'Feed/view/feed.twig',
                                'message' => '¡Hola desde el nuevo sistema de bloques heredando de 1sidebar!'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
