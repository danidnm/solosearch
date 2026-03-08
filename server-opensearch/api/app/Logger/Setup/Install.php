<?php

namespace SoloSearch\Logger\Setup;

use SoloSearch\Core\Model\Config;

use SoloSearch\Core\Setup\InstallerInterface;

class Install implements InstallerInterface
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Install module
     * 
     * @param string $currentVersion
     */
    public function install($version = '0.0.0')
    {
        $appPath = $this->config->get('app/path');
        $logDir = $appPath . '/var/log';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        if (!is_writable($logDir)) {
            chmod($logDir, 0777);
        }
    }
}
