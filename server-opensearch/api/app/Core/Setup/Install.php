<?php

namespace SoloSearch\Core\Setup;

class Install implements InstallerInterface
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
     * Install core module
     * 
     * @param string $version
     */
    public function install($version = '0.0.0')
    {
        // If version is 0.0.0, it means the platform or module is being installed from scratch
        if ($version === '0.0.0') {
            $this->createModulesTable();
        }

        if (version_compare($version, '0.0.2', '<')) {
            $this->createConfigTable();
        }
    }

    /**
     * Create modules table
     */
    private function createModulesTable()
    {
        // Check if table exists before creating
        if ($this->db->getSchema()->hasTable('modules')) {
            return;
        }

        $this->db->createTable('modules', [
            'id'      => [
                'type' => 'autoincrement'
            ],
            'module'  => [
                'type' => 'string', 
                'length' => 64
            ],
            'version' => [
                'type' => 'string', 
                'length' => 64
            ]
        ]);
    }

    /**
     * Create config table
     */
    private function createConfigTable()
    {
        if ($this->db->getSchema()->hasTable('config')) {
            return;
        }

        $this->db->createTable('config', [
            'id'    => ['type' => 'autoincrement'],
            'path'  => ['type' => 'string', 'length' => 255, 'unique' => true],
            'value' => ['type' => 'text', 'nullable' => true]
        ]);
    }
}
