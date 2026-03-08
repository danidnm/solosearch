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
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
