<?php

namespace SoloSearch\Core\Console;

class Install extends \SoloSearch\Core\Console\AbstractCommand
{
    /**
     * @var \SoloSearch\Core\Model\Db $db
     */
    private \SoloSearch\Core\Model\Db $db;

    /**
     * @param \SoloSearch\Core\Model\Db $db
     */
    public function __construct(
        \SoloSearch\Core\Model\Db $db
    ) {
        $this->db = $db;
    }

    /**
     * Runs command
     */
    public function run()
    {
        // Install base if not installed
        if (!$this->db->getSchema()->hasTable('modules')) {
            $this->install();
        }
    }

    /**
     * Install base tables
     */
    private function install()
    {
        // Create modules table
        $this->db->createTable('modules', [
            'id'      => [
                'type' => 'autoincrement'
            ],
            'module'  => [
                'type' => 'string', 
                'lenght' => 64
            ],
            'version' => [
                'type' => 'string', 
                'lenght' => 64
            ]
        ]);

        // Insert core module record
        $this->db->insert('modules', [
            'module' => 'core',
            'version' => '1.0'
        ]);
    }
}
