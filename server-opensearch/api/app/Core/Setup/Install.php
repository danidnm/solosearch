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
        return isset($this->config[$key]) ? $this->config[$key] : '';
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