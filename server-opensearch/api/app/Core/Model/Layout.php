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

    /** @var array */
    protected array $handleConfig = [];
    
    /** @var BlockInterface[] */
    protected array $blocks = [];

    /**
     * Initializes the Layout instance with the required dependencies.
     *
     * @param Config $config The application configuration object holding layout definitions
     * @param Container $container The dependency injection container for instantiating blocks
     */
    public function __construct(Config $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * Retrieves all instantiated blocks for the current layout handle.
     * 
     * @return BlockInterface[] Array of blocks indexed by their alias
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * Retrieves the flattened and merged configuration array for the current handle.
     *
     * @return array The layout configuration data
     */
    public function getHandleConfig(): array
    {
        return $this->handleConfig;
    }

    /**
     * Builds the block hierarchy for a specific layout handle.
     * It delegates to configuration preparation and block generation.
     *
     * @param string $handle The layout handle to build (e.g., 'feed_index')
     * @return BlockInterface|null The root block of the layout, or null if no configuration exists
     */
    public function build(string $handle = 'default'): ?BlockInterface
    {
        $flatConfig = $this->prepareConfiguration($handle);
        
        if (empty($flatConfig)) {
            return null;
        }

        return $this->generateBlocks($flatConfig);
    }

    /**
     * Prepares, merges and flattens the layout configuration for the requested handle.
     * 
     * @param string $handle The layout handle requested
     * @return array The flattened configuration array
     */
    protected function prepareConfiguration(string $handle): array
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

        // Return early if neither the requested handle nor the default handle exists
        if (empty($defaultConfig) && empty($handleConfig)) {
            return [];
        }

        // Merge the requested handle on top of the default handle
        $this->handleConfig = array_replace_recursive($defaultConfig, $handleConfig);
        
        // Flatten the configuration array, converting nested 'childs' into 'parent' references
        return $this->flattenConfig($this->handleConfig);
    }

    /**
     * Instantiates all blocks and links them to their parents to form the block tree.
     * 
     * @param array $flatConfig The flattened configuration array
     * @return BlockInterface|null The root block
     */
    protected function generateBlocks(array $flatConfig): ?BlockInterface
    {
        $this->blocks = [];
        $rootBlocks = [];
        
        // Instantiate all blocks from the flattened configuration
        foreach ($flatConfig as $alias => $blockConfig) {
            $type = $blockConfig['type'] ?? \SoloSearch\Core\Block\BlockList::class;
            try {
                $block = $this->container->make($type, [
                    'name' => $alias,
                    'data' => $blockConfig
                ]);
                $this->blocks[$alias] = $block;
            } catch (\Exception $e) {
                error_log("Error creating block {$alias}: " . $e->getMessage());
            }
        }

        // Link blocks to their respective parents
        foreach ($flatConfig as $alias => $blockConfig) {
            if (!isset($this->blocks[$alias])) continue;

            $block = $this->blocks[$alias];
            $parentAlias = $blockConfig['parent'] ?? null;
            $position = (int)($blockConfig['position'] ?? 0);

            if ($parentAlias && isset($this->blocks[$parentAlias])) {
                $this->blocks[$parentAlias]->addChild($alias, $block, $position);
            } elseif (!$parentAlias || $parentAlias === '') {
                // Blocks without a parent are considered root blocks
                $rootBlocks[] = $block;
            }
        }

        // Return the root block
        if (count($rootBlocks) === 1) {
            return $rootBlocks[0];
        } elseif (count($rootBlocks) === 0) {
            return null;
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
     * Recursively merges layout handles using the 'update' key.
     * If a handle defines an 'update' key pointing to a parent handle, 
     * it merges the parent's configuration into its own.
     *
     * @param array $layouts The complete layout configuration array
     * @param string $handle The current handle being processed
     * @return array The merged configuration for the handle
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

    /**
     * Recursively flattens a nested block configuration tree into a 1D array,
     * assigning the 'parent' property to children.
     *
     * @param array $blocks Current level of blocks to process
     * @param string|null $parentAlias Alias of the parent block, if any
     * @param array &$flatConfig Reference to the accumulator array
     * @return array The flattened configuration array
     */
    protected function flattenConfig(array $blocks, ?string $parentAlias = null, array &$flatConfig = []): array
    {
        foreach ($blocks as $alias => $config) {
            // Apply the parent alias to blocks defined iteratively within 'childs' arrays
            if ($parentAlias !== null) {
                $config['parent'] = $parentAlias;
            }
            
            $childs = [];
            if (isset($config['childs']) && is_array($config['childs'])) {
                $childs = $config['childs'];
                unset($config['childs']);
            }

            // Merge block definitions to resolve conflicts between flat and nested declarations
            if (isset($flatConfig[$alias])) {
                $flatConfig[$alias] = array_replace_recursive($flatConfig[$alias], $config);
            } else {
                $flatConfig[$alias] = $config;
            }

            if (!empty($childs)) {
                $this->flattenConfig($childs, $alias, $flatConfig);
            }
        }
        
        return $flatConfig;
    }

    /**
     * Renders a layout handle to HTML.
     * First builds the layout tree and then calls `toHtml()` on the root block.
     *
     * @param string $handle The layout handle to render
     * @return string The generated HTML output
     */
    public function render(string $handle = 'default'): string
    {
        $root = $this->build($handle);
        return $root ? $root->toHtml() : '';
    }
}
