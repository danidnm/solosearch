<?php

namespace SoloSearch\Core\Model;

class Config
{
    /**
     * Configuration values storage
     */
    private $config;

    /**
     * @param \Psr\Container\ContainerInterface $container
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
     * Returns all configuration data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->config;
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
        $envFile = $this->config['config_path'] . '/env.php';
        unset($this->config['config_path']);
        
        if (file_exists($envFile)) {
            $this->config = array_merge_recursive($this->config, require $envFile);
        }
    }
}