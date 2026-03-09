<?php

namespace SoloSearch\Core\Model;

use Psr\Container\ContainerInterface;

class Config
{
    /**
     * Configuration values storage
     */
    private $config = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $configPath;

    /**
     * @var bool
     */
    private $isExtendedLoaded = false;

    /**
     * @var bool
     */
    private $isCacheApplied = false;

    /**
     * @param ContainerInterface|null $container
     */
    /**
     * @param ContainerInterface|null $container
     */
    public function __construct(?ContainerInterface $container = null) {
        $this->container = $container;
        $this->preparePaths();
        $this->loadBootstrapConfig();
    }

    /**
     * Returns configuration value by key
     */
    public function get($key)
    {
        // If not a DB connection key and extended config not loaded, load it
        if (!$this->isExtendedLoaded && strpos($key, 'app/db/') !== 0) {
            $this->loadExtendedConfig();
        }

        $parts = explode('/', $key);
        $value = $this->config;

        foreach ($parts as $part) {
            if (is_array($value) && isset($value[$part])) {
                $value = $value[$part];
            } else {
                return '';
            }
        }

        return $value;
    }

    /**
     * Returns all configuration data
     * 
     * @return array
     */
    public function getData()
    {
        if (!$this->isExtendedLoaded) {
            $this->loadExtendedConfig();
        }
        return $this->config;
    }

    /**
     * Prepare paths to project, urls, and so on
     */
    private function preparePaths()
    {
        $this->config['app']['path'] = realpath(__DIR__ . '/../../../');
        $this->configPath = realpath(__DIR__ . '/../../../config/');
    }

    /**
     * Load only essential bootstrap config (env.php)
     */
    private function loadBootstrapConfig()
    {
        $envFile = $this->configPath . '/env.php';
        if (file_exists($envFile)) {
            $this->config = array_replace_recursive($this->config, require $envFile);
        }
    }

    /**
     * Load extended config from modules, cache and database
     */
    private function loadExtendedConfig()
    {
        // Prevent recursion if called during DB initialization
        $this->isExtendedLoaded = true;

        $appDir = $this->config['app']['path'];
        $configDir = $this->configPath;

        // 1. Check Cache first
        if ($this->loadFromCache()) {
            return;
        }

        // 2. Scan and load modules config
        $modulesDir = $appDir . '/app';
        if (is_dir($modulesDir)) {
            $modules = scandir($modulesDir);
            foreach ($modules as $module) {
                if ($module === '.' || $module === '..') continue;
                
                $moduleConfig = $modulesDir . '/' . $module . '/etc/config.php';
                if (file_exists($moduleConfig)) {
                    $this->config = array_replace_recursive($this->config, require $moduleConfig);
                }
            }
        }

        // 3. Load from Database
        $this->loadFromDatabase();

        // 4. Final override with env.php (highest priority)
        $this->loadBootstrapConfig();

        // 5. Save to Cache
        $this->saveToCache();
    }

    /**
     * Load config from cache
     * 
     * @return bool
     */
    private function loadFromCache()
    {
        if (!$this->container) return false;

        try {
            /** @var \SoloSearch\Cache\Model\CacheRepository $cache */
            $cache = $this->container->get('SoloSearch\Cache\Model\CacheRepository');
            $cachedConfig = $cache->get('app_config');
            if ($cachedConfig && is_array($cachedConfig)) {
                $this->config = $cachedConfig;
                $this->isCacheApplied = true;
                return true;
            }
        } catch (\Exception $e) {
            // Silently fail if cache is not available yet
        }

        return false;
    }

    /**
     * Save config to cache
     */
    private function saveToCache()
    {
        if (!$this->container || $this->isCacheApplied) return;

        try {
            /** @var \SoloSearch\Cache\Model\CacheRepository $cache */
            $cache = $this->container->get('SoloSearch\Cache\Model\CacheRepository');
            $cache->set('app_config', $this->config);
        } catch (\Exception $e) {
            // Silently fail
        }
    }

    /**
     * Load config from config table
     */
    private function loadFromDatabase()
    {
        if (!$this->container) return;

        try {
            /** @var \SoloSearch\Core\Model\Db $db */
            $db = $this->container->get('SoloSearch\Core\Model\Db');
            
            // Check if table exists
            if (!$db->getSchema()->hasTable('config')) {
                return;
            }

            $rows = $db->getManager()->table('config')->get();
            foreach ($rows as $row) {
                $this->setConfigValueByPath($row->path, $row->value);
            }
        } catch (\Exception $e) {
            // Silently fail if DB is not ready
        }
    }

    /**
     * Utility to set nested config value by path string (e.g. "section/group/field")
     */
    private function setConfigValueByPath($path, $value)
    {
        $parts = explode('/', $path);
        $temp = &$this->config;
        foreach ($parts as $part) {
            if (!isset($temp[$part]) || !is_array($temp[$part])) {
                $temp[$part] = [];
            }
            $temp = &$temp[$part];
        }
        $temp = $value;
    }
}