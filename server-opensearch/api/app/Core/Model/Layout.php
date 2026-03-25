<?php

namespace SoloSearch\Core\Model;

use DI\Container;
use SoloSearch\Core\Block\BlockInterface;

/**
 * Class Layout
 * Responsible for parsing the 'layout' configuration arrays and 
 * instantiating the hierarchy of Block objects using the DI container.
 */
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

        $handleConfig = $this->mergeLayoutHandles($layouts, $handle);
        
        /** @var BlockInterface[] $blocks */
        $blocks = [];
        $rootBlocks = [];
        
        // 1. Instanciar todos los bloques definidos en este handle
        foreach ($handleConfig as $alias => $blockConfig) {
            $type = $blockConfig['type'] ?? \SoloSearch\Core\Block\BlockList::class;
            try {
                $block = $this->container->make($type, [
                    'name' => $alias,
                    'data' => $blockConfig
                ]);
                $blocks[$alias] = $block;
            } catch (\Exception $e) {
                error_log("Error creating block {$alias}: " . $e->getMessage());
            }
        }

        // 2. Vincular los bloques a sus padres ('parent')
        foreach ($handleConfig as $alias => $blockConfig) {
            if (!isset($blocks[$alias])) continue;

            $block = $blocks[$alias];
            $parentAlias = $blockConfig['parent'] ?? null;
            $position = (int)($blockConfig['position'] ?? 0);

            if ($parentAlias && isset($blocks[$parentAlias])) {
                $blocks[$parentAlias]->addChild($alias, $block, $position);
            } elseif (!$parentAlias || $parentAlias === '') {
                // Si no tiene padre, se considera un bloque raíz
                $rootBlocks[] = $block;
            }
        }

        // 3. Devolver la raíz
        if (count($rootBlocks) === 1) {
            return $rootBlocks[0];
        }
        
        foreach ($rootBlocks as $block) {
            $wrapper->addChild($block->getName(), $block);
        }

        return $wrapper;
    }

    /**
     * Recursively merge layout handles using the 'update' key
     */
    protected function mergeLayoutHandles(array $layouts, string $handle): array
    {
        $config = $layouts[$handle] ?? [];
        if (isset($config['update'])) {
            $parentHandle = $config['update'];
            unset($config['update']);
            if (isset($layouts[$parentHandle])) {
                $parentConfig = $this->mergeLayoutHandles($layouts, $parentHandle);
                $config = array_replace_recursive($parentConfig, $config);
            }
        }
        return $config;
    }



    public function render(string $handle = 'default'): string
    {
        $root = $this->build($handle);
        return $root ? $root->toHtml() : '';
    }
}
