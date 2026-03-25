<?php

namespace SoloSearch\Core\Model;

use DI\Container;
use SoloSearch\Core\Block\BlockInterface;

class Layout
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var Container
     */
    protected Container $container;

    public function __construct(Config $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }

    public function build(string $handle = 'default'): ?BlockInterface
    {
        $layouts = $this->config->get('layout');
        if (!isset($layouts[$handle])) {
            return null;
        }

        $handleConfig = $layouts[$handle];
        
        // Find root blocks (blocks at the top level of the handle)
        $rootBlocks = [];
        foreach ($handleConfig as $alias => $blockConfig) {
            $block = $this->createBlock($alias, $blockConfig);
            if ($block) {
                $rootBlocks[] = $block;
            }
        }

        if (count($rootBlocks) === 1) {
            return $rootBlocks[0];
        }

        // If multiple roots, wrap them in a BlockList
        $wrapper = $this->container->make(\SoloSearch\Core\Block\BlockList::class, [
            'name' => 'root_wrapper',
            'data' => []
        ]);
        
        foreach ($rootBlocks as $block) {
            $wrapper->addChild($block->getName(), $block);
        }

        return $wrapper;
    }

    protected function createBlock(string $alias, array $config): ?BlockInterface
    {
        $type = $config['type'] ?? \SoloSearch\Core\Block\BlockList::class;

        try {
            /** @var BlockInterface $block */
            $block = $this->container->make($type, [
                'name' => $alias,
                'data' => $config
            ]);

            if (isset($config['childs']) && is_array($config['childs'])) {
                foreach ($config['childs'] as $childAlias => $childConfig) {
                    $childBlock = $this->createBlock($childAlias, $childConfig);
                    if ($childBlock) {
                        $position = $childConfig['position'] ?? 0;
                        $block->addChild($childAlias, $childBlock, (int)$position);
                    }
                }
            }

            return $block;
        } catch (\Exception $e) {
            // Log error or handle missing block types
            error_log('Error creating block ' . $alias . ': ' . $e->getMessage());
            return null;
        }
    }

    public function render(string $handle = 'default'): string
    {
        $root = $this->build($handle);
        return $root ? $root->toHtml() : '';
    }
}
