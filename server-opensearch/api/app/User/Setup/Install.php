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
        if (version_compare($version, '0.0.1', '<')) {
            $this->createInitialTables();
        }

        if (version_compare($version, '0.0.3', '<')) {
            $this->addRoleColumn();
        }
    }

    /**
     * Create initial tables for version 0.0.1
     */
    private function createInitialTables()
    {
        $this->createUserTable();
        $this->createTokenTable();
    }

    /**
     * Update to version 0.0.3: Add role column
     */
    private function addRoleColumn()
    {
        $this->db->getManager()->getSchemaBuilder()->table('user', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('role', 20)->default('user')->after('password');
        });
    }

    /**
     * Create user table
     */
    private function createUserTable()
    {
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
            'role' => [
                'type' => 'string',
                'length' => 20,
                'default' => 'user'
            ],
            'first_name' => [
                'type' => 'string',
                'length' => 255,
                'nullable' => true
            ],
            'last_name' => [
                'type' => 'string',
                'length' => 255,
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
