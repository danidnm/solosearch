<?php

return [
    'modules' => [
        'feed' => [
            'version' => '0.0.1'
        ]
    ],
    'layout' => [
        // 1. Modificamos GLOBALMENTE el handle 'default' para inyectar este widget en todos lados
        // Si el layout final tiene un bloque llamado 'sidebar', este widget aparecerá allí.
        'default' => [
            'feed.sidebar.widget' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Feed/view/sidebar_widget.twig',
                'title' => 'Novedades',
                'parent' => 'sidebar',
                'position' => 10
            ]
        ],
        
        // 2. Definimos nuestro propio handle referenciando 'content' como parent
        'feed_index' => [
            'update' => '1sidebar',
            'feed.updates' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Feed/view/feed.twig',
                'parent' => 'content',
                'message' => '¡Hola desde el nuevo sistema de bloques heredando de 1sidebar y usando referencias planas!'
            ]
        ]
    ]
];
