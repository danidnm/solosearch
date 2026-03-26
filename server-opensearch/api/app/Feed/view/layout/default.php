<?php

return [
    'feed.sidebar.widget' => [
        'type' => \SoloSearch\Core\Block\Template::class,
        'template' => 'Feed/view/sidebar_widget.twig',
        'title' => 'Novedades',
        'parent' => 'sidebar',
        'position' => 10
    ]
];
