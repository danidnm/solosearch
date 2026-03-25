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
        
        $defaultConfig = [];
        if ($handle !== 'default' && isset($layouts['default'])) {
            $defaultConfig = $this->mergeLayoutHandles($layouts, 'default');
        }

        $handleConfig = [];
        if (isset($layouts[$handle])) {
            $handleConfig = $this->mergeLayoutHandles($layouts, $handle);
        }

        // Si no existe ni default ni el handle pedido, no hay nada que hacer
        if (empty($defaultConfig) && empty($handleConfig)) {
            return null;
        }

        // Fusionar: El handle específico sobreescribe/añade al default
        $handleConfig = array_replace_recursive($defaultConfig, $handleConfig);
        
        // 1. Aplanar la configuración: convertir 'childs' anidados en referencias 'parent'
        $flatConfig = [];
        $flatten = function(array $blocks, ?string $parentAlias = null) use (&$flatten, &$flatConfig) {
            foreach ($blocks as $alias => $config) {
                // Si fue declarado iterativamente dentro de 'childs', le forzamos el parent
                if ($parentAlias !== null) {
                    $config['parent'] = $parentAlias;
                }
                
                $childs = [];
                if (isset($config['childs']) && is_array($config['childs'])) {
                    $childs = $config['childs'];
                    unset($config['childs']);
                }

                // Si por alguna razón un módulo definió un bloque plano y luego otro módulo
                // lo redefinió anidado, los fusionamos a favor del último
                if (isset($flatConfig[$alias])) {
                    $flatConfig[$alias] = array_replace_recursive($flatConfig[$alias], $config);
                } else {
                    $flatConfig[$alias] = $config;
                }

                if (!empty($childs)) {
                    $flatten($childs, $alias);
                }
            }
        };
        $flatten($handleConfig);

        /** @var BlockInterface[] $blocks */
        $blocks = [];
        $rootBlocks = [];
        
        // 2. Instanciar todos los bloques del array plano
        foreach ($flatConfig as $alias => $blockConfig) {
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

        // 3. Vincular los bloques a sus padres ('parent')
        foreach ($flatConfig as $alias => $blockConfig) {
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

        // 4. Devolver la raíz
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
