<?php

return [
    'root' => [
        'type' => \SoloSearch\Core\Block\Template::class,
        'template' => 'Core/view/template/2sidebars.twig',
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
];
