<?php

namespace SoloSearch\Core\Console;

use SoloSearch\Core\Model\Config;

class ShowConfig extends AbstractCommand
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Runs command
     */
    public function run()
    {
        $data = $this->config->getData();
        
        echo "Current Configuration:\n";
        echo "----------------------\n";
        
        foreach ($data as $key => $value) {
            echo sprintf("%-20s: %s\n", $key, is_array($value) ? json_encode($value) : $value);
        }
        
        echo "----------------------\n";
    }
}
