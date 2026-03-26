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
     * 
     * @param array $argv
     */
    public function run(array $argv)
    {
        $handle = $argv[2] ?? 'default';

        $data = $this->layout->getTree($handle);

        if (empty($data)) {
            echo "No layout configuration found for handle '{$handle}'.\n";
            return;
        }

        echo "Layout tree for handle '{$handle}':\n";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
