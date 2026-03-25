<?php

return [
    'modules' => [
        'core' => [
            'version' => '0.0.2'
        ]
    ],
    'layout' => [
        '1column' => [
            'root' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Core/view/template/1column.twig',
            ],
            'head.styles' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'head.scripts' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'header' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'content' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'footer' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
        ],
        '1sidebar' => [
            'root' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Core/view/template/1sidebar.twig',
            ],
            'head.styles' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'head.scripts' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'header' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'sidebar' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'content' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'footer' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
        ],
        '2sidebars' => [
            'root' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Core/view/template/2sidebars.twig',
            ],
            'head.styles' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'head.scripts' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'header' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'left' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'content' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'right' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
            'footer' => ['type' => \SoloSearch\Core\Block\BlockList::class, 'parent' => 'root'],
        ]
    ]
];
