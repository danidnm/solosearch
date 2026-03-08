<?php

namespace SoloSearch\Core\Console;

class Install extends \SoloSearch\Core\Console\AbstractCommand
{
    /**
     * @var \SoloSearch\Core\Model\Db $db
     */
    private \SoloSearch\Core\Model\Db $db;

    /**
     * @var \SoloSearch\Core\Model\Config $config
     */
    private \SoloSearch\Core\Model\Config $config;

    /**
     * @var \Psr\Container\ContainerInterface $container
     */
    private \Psr\Container\ContainerInterface $container;

    /**
     * @var \SoloSearch\Core\Setup\Install $setupInstaller
     */
    private \SoloSearch\Core\Setup\Install $setupInstaller;

    /**
     * @param \SoloSearch\Core\Model\Db $db
     * @param \SoloSearch\Core\Model\Config $config
     * @param \Psr\Container\ContainerInterface $container
     * @param \SoloSearch\Core\Setup\Install $setupInstaller
     */
    public function __construct(
        \SoloSearch\Core\Model\Db $db,
        \SoloSearch\Core\Model\Config $config,
        \Psr\Container\ContainerInterface $container,
        \SoloSearch\Core\Setup\Install $setupInstaller
    ) {
        $this->db = $db;
        $this->config = $config;
        $this->container = $container;
        $this->setupInstaller = $setupInstaller;
    }

    /**
     * Runs command
     */
    public function run()
    {
        // 1. Initial platform setup if needed
        if (!$this->db->getSchema()->hasTable('modules')) {
            echo "Installing SoloSearch platform base...\n";
            $this->setupInstaller->install('0.0.0');
        }

        echo "Starting module configuration and updates...\n";

        // 2. Discover modules
        $appPath = $this->config->get('app/path');
        $modules = [];
        if (is_dir($appPath)) {
            $dirs = scandir($appPath);
            foreach ($dirs as $dir) {
                if ($dir === '.' || $dir === '..') continue;
                if (is_dir($appPath . '/' . $dir)) {
                    $modules[] = $dir;
                }
            }
        }

        foreach ($modules as $moduleName) {
            $this->processModule($moduleName);
        }

        echo "\nConfiguration and updates complete.\n";
    }

    /**
     * Process module installation/update
     * 
     * @param string $moduleName
     */
    private function processModule($moduleName)
    {
        $moduleKey = strtolower($moduleName);
        echo "Module: {$moduleName}... ";

        // Get target version from config
        $targetVersion = $this->config->get("modules/{$moduleKey}/version") ?: '0.0.0';

        // Get current version from DB
        $currentVersion = '0.0.0';
        $record = $this->db->getManager()->table('modules')->where('module', $moduleKey)->first();
        if ($record) {
            $currentVersion = $record->version;
        }

        if ($currentVersion === $targetVersion && $currentVersion !== '0.0.0') {
            echo "OK ({$currentVersion})\n";
            return;
        }

        echo "Updating ({$currentVersion} -> {$targetVersion})... ";

        // Run installer if exists
        $installerClass = "SoloSearch\\{$moduleName}\\Setup\\Install";
        if (class_exists($installerClass)) {
            try {
                $installer = $this->container->get($installerClass);
                $installer->install($currentVersion);
            } catch (\Exception $e) {
                echo "\n  [Error] Installer for {$moduleName}: " . $e->getMessage() . "\n";
            }
        }

        // Update module version in DB
        $this->updateModuleVersion($moduleKey, $targetVersion);
        echo "DONE\n";
    }

    /**
     * Update module version in database
     * 
     * @param string $moduleKey
     * @param string $version
     */
    private function updateModuleVersion($moduleKey, $version)
    {
        $table = $this->db->getManager()->table('modules');
        $exists = $table->where('module', $moduleKey)->exists();

        if ($exists) {
            $table->where('module', $moduleKey)->update(['version' => $version]);
        } else {
            $table->insert([
                'module' => $moduleKey,
                'version' => $version
            ]);
        }
    }
}
