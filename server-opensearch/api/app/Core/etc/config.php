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
                'template' => 'Core/view/layout/1column.twig',
                'childs' => [
                    'head.styles' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'head.scripts' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'header' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'content' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'footer' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                ]
            ]
        ],
        '1sidebar' => [
            'root' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Core/view/layout/1sidebar.twig',
                'childs' => [
                    'head.styles' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'head.scripts' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'header' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'sidebar' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'content' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'footer' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                ]
            ]
        ],
        '2sidebars' => [
            'root' => [
                'type' => \SoloSearch\Core\Block\Template::class,
                'template' => 'Core/view/layout/2sidebars.twig',
                'childs' => [
                    'head.styles' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'head.scripts' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'header' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'left' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'content' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'right' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                    'footer' => ['type' => \SoloSearch\Core\Block\BlockList::class],
                ]
            ]
        ]
    ]
];
