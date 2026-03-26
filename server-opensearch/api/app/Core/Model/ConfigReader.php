<?php

namespace SoloSearch\Core\Model;

/**
 * Class ConfigReader
 * Responsible for reading and merging PHP array configuration files from modules.
 */
class ConfigReader
{
    /**
     * Reads and merges configuration arrays from modules.
     *
     * @param string $modulesDir The base directory containing all modules (e.g. /path/to/app)
     * @param string $moduleSubPath The path within each module to look for (e.g. 'etc/config.php' or 'view/layout')
     * @param bool $isDirectory Whether the $moduleSubPath is a directory containing multiple .php files
     * @return array The merged configuration
     */
    public function read(string $modulesDir, string $moduleSubPath, bool $isDirectory = false): array
    {
        $mergedConfig = [];

        if (!is_dir($modulesDir)) {
            return $mergedConfig;
        }

        $modules = scandir($modulesDir);
        foreach ($modules as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $targetPath = rtrim($modulesDir, '/') . '/' . $module . '/' . ltrim($moduleSubPath, '/');

            if ($isDirectory) {
                if (is_dir($targetPath)) {
                    $files = scandir($targetPath);
                    foreach ($files as $file) {
                        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                            $key = basename($file, '.php');
                            $configData = require $targetPath . '/' . $file;

                            if (is_array($configData)) {
                                if (!isset($mergedConfig[$key])) {
                                    $mergedConfig[$key] = [];
                                }
                                $mergedConfig[$key] = array_replace_recursive($mergedConfig[$key], $configData);
                            }
                        }
                    }
                }
            } else {
                if (file_exists($targetPath)) {
                    $configData = require $targetPath;
                    if (is_array($configData)) {
                        $mergedConfig = array_replace_recursive($mergedConfig, $configData);
                    }
                }
            }
        }

        return $mergedConfig;
    }
}
