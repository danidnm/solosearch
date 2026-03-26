<?php

namespace SoloSearch\Core\Console;

use SoloSearch\Core\Model\Layout;

class ShowLayout implements CommandInterface
{
    /**
     * @var Layout
     */
    private Layout $layout;

    /**
     * @param Layout $layout
     */
    public function __construct(
        Layout $layout
    ) {
        $this->layout = $layout;
    }

    /**
     * Runs command
     */
    public function run()
    {
        global $argv;
        $handle = $argv[2] ?? 'default';

        $this->layout->build($handle);
        $data = $this->layout->getHandleConfig();

        if (empty($data)) {
            echo "No layout configuration found for handle '{$handle}'.\n";
            return;
        }

        echo "Layout configuration for handle '{$handle}':\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
