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
        $this->config['app_path'] = realpath(__DIR__ . '/../../../config/');
    }

    /**
     * Load env config
     */
    private function loadConfig()
    {
        $envFile = $this->config['app_path'] . '/env.php';
        if (file_exists($envFile)) {
            $this->config = array_merge($this->config, require $envFile);
        }
    }
}