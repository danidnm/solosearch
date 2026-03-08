<?php

namespace SoloSearch\Core\Setup;

class Install
{
    /**
     * Configuration values storage
     */
    private $config;

    /**
     * @param \SoloSearch\Core\Model\Install
     */
    public function __construct(
    ) {
        $this->preparePaths();
        $this->loadConfig();
    }

    /**
     * Returns configuration value by key
     */
    public function get($key)
    {
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
     * Prepare paths to project, urls, and so on
     */
    private function preparePaths()
    {
        $this->config['app']['path'] = realpath(__DIR__ . '/../../');
        $this->config['config_path'] = realpath(__DIR__ . '/../../../config/');
    }

    /**
     * Load env config
     */
    private function loadConfig()
    {
        $appDir = $this->config['app']['path'];
        $configDir = $this->config['config_path'];
        unset($this->config['config_path']);

        // 1. Scan and load modules config
        if (is_dir($appDir)) {
            $modules = scandir($appDir);
            foreach ($modules as $module) {
                if ($module === '.' || $module === '..') continue;

                $moduleConfig = $appDir . '/' . $module . '/etc/config.php';
                if (file_exists($moduleConfig)) {
                    $this->config = array_replace_recursive($this->config, require $moduleConfig);
                }
            }
        }

        // 2. Load main env config (priority)
        $envFile = $configDir . '/env.php';
        if (file_exists($envFile)) {
            $this->config = array_replace_recursive($this->config, require $envFile);
        }
    }
}