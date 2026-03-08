<?php

namespace SoloSearch\Cache\Setup;

use SoloSearch\Core\Model\Db;
use SoloSearch\Core\Setup\InstallerInterface;

class Install implements InstallerInterface
{
    /**
     * @var Db
     */
    private Db $db;

    /**
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * Install Cache module
     * 
     * @param string $version
     */
    public function install($version = '0.0.0')
    {
        $this->createCacheTable();
    }

    /**
     * Create cache table
     */
    private function createCacheTable()
    {
        if ($this->db->getSchema()->hasTable('cache')) {
            return;
        }

        $this->db->createTable('cache', [
            'id' => [
                'type' => 'autoincrement'
            ],
            'cache_key' => [
                'type' => 'string',
                'length' => 255,
                'unique' => true
            ],
            'cache_value' => [
                'type' => 'text'
            ],
            'created_at' => [
                'type' => 'timestamp'
            ],
            'updated_at' => [
                'type' => 'timestamp'
            ]
        ]);
    }
}
