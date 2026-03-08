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
        return isset($this->config[$key]) ? $this->config[$key] : '';
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
        $this->config['app_path'] = realpath(__DIR__ . '/../../../config/');
    }

    /**
     * Load env config
     */
    private function loadConfig()
    {
        $this->config = \Dotenv\Dotenv::createArrayBacked($this->config['app_path'])->load();
    }
}