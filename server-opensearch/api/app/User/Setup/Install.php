<?php

namespace SoloSearch\User\Setup;

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
     * Install User module
     * 
     * @param string $version
     */
    public function install($version = '0.0.0')
    {
        $this->createUserTable();
        $this->createTokenTable();
    }

    /**
     * Create user table
     */
    private function createUserTable()
    {
        if ($this->db->getSchema()->hasTable('user')) {
            return;
        }

        $this->db->createTable('user', [
            'id' => [
                'type' => 'autoincrement'
            ],
            'email' => [
                'type' => 'string',
                'length' => 255,
                'unique' => true
            ],
            'password' => [
                'type' => 'string'
            ],
            'first_name' => [
                'type' => 'string',
                'nullable' => true
            ],
            'last_name' => [
                'type' => 'string',
                'nullable' => true
            ],
            'created_at' => [
                'type' => 'timestamp'
            ],
            'updated_at' => [
                'type' => 'timestamp'
            ]
        ]);
    }

    /**
     * Create token table
     */
    private function createTokenTable()
    {
        if ($this->db->getSchema()->hasTable('token')) {
            return;
        }

        $this->db->createTable('token', [
            'id' => [
                'type' => 'autoincrement'
            ],
            'user_id' => [
                'type' => 'integer'
            ],
            'token' => [
                'type' => 'string',
                'length' => 255
            ],
            'created_at' => [
                'type' => 'timestamp'
            ],
            'expire_at' => [
                'type' => 'timestamp'
            ]
        ]);
    }
}
