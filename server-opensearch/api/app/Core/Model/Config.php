<?php

namespace SoloSearch\Core\Model;

use Illuminate\Database\DatabaseManager;
use SoloSearch\Cache\Model\CacheRepository;

class Config
{
    /**
     * Configuration values storage
     */
    private $config = [];

    /**
     * @var BootstrapConfig
     */
    private $bootstrapConfig;

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * @var DatabaseManager
     */
    private $dbManager;

    /**
     * @var ConfigReader
     */
    private $configReader;

    /**
     * @var bool
     */
    private $isExtendedLoaded = false;

    /**
     * @var bool
     */
    private $isCacheApplied = false;

    /**
     * @param BootstrapConfig $bootstrapConfig
     * @param CacheRepository $cache
     * @param DatabaseManager $dbManager
     * @param ConfigReader $configReader
     */
    public function __construct(
        BootstrapConfig $bootstrapConfig,
        CacheRepository $cache,
        DatabaseManager $dbManager,
        ConfigReader $configReader
    ) {
        $this->bootstrapConfig = $bootstrapConfig;
        $this->cache = $cache;
        $this->dbManager = $dbManager;
        $this->configReader = $configReader;
        $this->preparePaths();
        $this->loadBootstrapConfig();
    }

    /**
     * Returns configuration value by key
     */
    public function get($key)
    {
        // If not a DB connection key and extended config not loaded, load it
        // We exclude app/db/ because that's what's used for bootstrapping DB
        if (!$this->isExtendedLoaded) {
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
    }

    /**
     * Load only essential bootstrap config (env.php)
     */
    private function loadBootstrapConfig()
    {
        $this->config = array_replace_recursive($this->config, $this->bootstrapConfig->getData());
    }

    /**
     * Load extended config from modules, cache and database
     */
    private function loadExtendedConfig()
    {
        // Prevent recursion if called during DB initialization
        $this->isExtendedLoaded = true;

        $appDir = $this->config['app']['path'];

        // Check Cache first
        if ($this->loadFromCache()) {
            return;
        }

        // Scan and load modules config
        $modulesDir = $appDir . '/app';
        
        $moduleConfig = $this->configReader->read($modulesDir, 'etc/config.php', false);
        $this->config = array_replace_recursive($this->config, $moduleConfig);

        // Load from Database
        $this->loadFromDatabase();

        // Final override with bootstrap config (highest priority)
        $this->loadBootstrapConfig();

        // Save to Cache
        $this->saveToCache();
    }

    /**
     * Load config from cache
     * 
     * @return bool
     */
    private function loadFromCache()
    {
        try {
            $cachedConfig = $this->cache->get('app_config');
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
        if ($this->isCacheApplied) return;

        try {
            $this->cache->set('app_config', $this->config);
        } catch (\Exception $e) {
            // Silently fail
        }
    }

    /**
     * Load config from config table
     */
    private function loadFromDatabase()
    {
        try {
            // Check if table exists
            if (!$this->dbManager->connection()->getSchemaBuilder()->hasTable('config')) {
                return;
            }

            $rows = $this->dbManager->table('config')->get();
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