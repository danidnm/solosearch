<?php

namespace SoloSearch\Core\Model;

use DI\Container;
use SoloSearch\Core\Block\BlockInterface;
use SoloSearch\Cache\Model\CacheRepository;

/**
 * Class Layout
 * Responsible for parsing the 'layout' configuration arrays and 
 * instantiating the hierarchy of Block objects using the DI container.
 */
class Layout
{
    /**
     * Cache key prefix
     */
    public const LAYOUT_FLAT_CACHE_PREFIX = 'layout_flat_';

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var CacheRepository
     */
    protected CacheRepository $cache;

    /**
     * @var ConfigReader
     */
    protected ConfigReader $configReader;

    /** @var BlockInterface[] */
    protected array $blocks = [];

    /**
     * Initializes the Layout instance with the required dependencies.
     *
     * @param Config $config The application configuration object holding layout definitions
     * @param Container $container The dependency injection container for instantiating blocks
     * @param CacheRepository $cache Repository for caching layout configurations
     * @param ConfigReader $configReader Reader for merging layout configuration files
     */
    public function __construct(Config $config, Container $container, CacheRepository $cache, ConfigReader $configReader)
    {
        $this->config = $config;
        $this->container = $container;
        $this->cache = $cache;
        $this->configReader = $configReader;
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

    /**
     * Retrieves the tree structure of the blocks for the current handle.
     *
     * @param string $handle The layout handle requested
     * @return array The layout tree structure
     */
    public function getTree(string $handle = 'default'): array
    {
        $root = $this->build($handle);
        if (!$root) {
            return [];
        }

        return $this->buildBlockTree($root);
    }

    /**
     * Builds the block hierarchy for a specific layout handle.
     * It delegates to configuration preparation and block generation.
     *
     * @param string $handle The layout handle to build (e.g., 'feed_index')
     * @return BlockInterface|null The root block of the layout, or null if no configuration exists
     */
    protected function build(string $handle = 'default'): ?BlockInterface
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
        $cacheKey = self::LAYOUT_FLAT_CACHE_PREFIX . $handle;
        $cachedFlat = $this->loadFromCache($cacheKey);
        if ($cachedFlat !== null) {
            return $cachedFlat;
        }

        // Load layout files
        $layouts = $this->loadLayouts();
        
        // Merge handles and flatten the configuration
        $mergedConfig = $this->getMergedHandleConfig($layouts, $handle);
        if (empty($mergedConfig)) {
            return [];
        }

        // Flatten the configuration array, converting nested 'childs' into 'parent' references
        $flatConfig = $this->flattenConfig($mergedConfig);

        $this->saveToCache($cacheKey, $flatConfig);

        return $flatConfig;
    }

    /**
     * Loads layout configurations from all modules' view/layout/*.php files.
     *
     * @return array The loaded and merged layout configurations
     */
    protected function loadLayouts(): array
    {
        $modulesDir = $this->config->get('app/modules_path');
        return $this->configReader->read($modulesDir, 'view/layout', true);
    }
    
    /**
     * Merges the components of a specific handle, including the default handle.
     *
     * @param array $layouts All available layouts
     * @param string $handle The requested handle
     * @return array The merged configuration for the handle
     */
    protected function getMergedHandleConfig(array $layouts, string $handle): array
    {
        // Merge default handle with current handle
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
        return array_replace_recursive($defaultConfig, $handleConfig);
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
     * Recursively builds an array representation of a block and its children.
     * 
     * @param BlockInterface $block
     * @return array
     */
    protected function buildBlockTree(BlockInterface $block): array
    {
        $data = method_exists($block, 'getData') ? $block->getData() : [];
        
        $node = [
            'name' => $block->getName(),
            'class' => get_class($block),
            'data' => $data,
        ];

        if (method_exists($block, 'getSortedChilds')) {
            $childs = $block->getSortedChilds();
            if (!empty($childs)) {
                $node['childs'] = [];
                foreach ($childs as $child) {
                    $node['childs'][$child->getName()] = $this->buildBlockTree($child);
                }
            }
        } elseif (method_exists($block, 'getChilds')) {
            $childs = $block->getChilds();
            if (!empty($childs)) {
                $node['childs'] = [];
                foreach ($childs as $alias => $child) {
                    $node['childs'][$alias] = $this->buildBlockTree($child);
                }
            }
        }

        return $node;
    }

    /**
     * Load data from cache
     * 
     * @param string $key
     * @return array|null
     */
    protected function loadFromCache(string $key): ?array
    {
        try {
            $cachedData = $this->cache->get($key);
            if ($cachedData && is_array($cachedData)) {
                return $cachedData;
            }
        } catch (\Exception $e) {
            // Silently ignore cache read errors
        }

        return null;
    }

    /**
     * Save data to cache
     * 
     * @param string $key
     * @param array $data
     */
    protected function saveToCache(string $key, array $data): void
    {
        try {
            $this->cache->set($key, $data);
        } catch (\Exception $e) {
            // Silently ignore cache write errors
        }
    }
}
