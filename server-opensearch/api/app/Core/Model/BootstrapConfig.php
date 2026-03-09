<?php

namespace SoloSearch\Core\Model;

class BootstrapConfig
{
    /**
     * @var array
     */
    private array $config = [];

    /**
     * @var string
     */
    private string $configPath;

    /** 
     * BootstrapConfig constructor.
     */
    public function __construct()
    {
        $this->configPath = realpath(__DIR__ . '/../../../config/');
        $this->loadBootstrapConfig();
    }

    /**
     * Load only essential bootstrap config (env.php)
     */
    private function loadBootstrapConfig()
    {
        $envFile = $this->configPath . '/env.php';
        if (file_exists($envFile)) {
            $this->config = require $envFile;
        }
    }

    /**
     * Returns configuration value by key
     * 
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
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
    public function getData(): array
    {
        return $this->config;
    }
}
