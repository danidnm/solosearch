<?php

namespace SoloSearch\Core\Model;

use Illuminate\Database\Schema\Blueprint;

class Db
{
    /**
     * @var \Illuminate\Database\DatabaseManager 
     */
    protected \Illuminate\Database\DatabaseManager $manager;

    /**
     * @var \Illuminate\Database\DatabaseManager 
     */
    public function __construct(\Illuminate\Database\DatabaseManager $manager)
    {
        $this->manager = $manager;
    }

    /** 
     * 
     */
    public function insert($tableName, $records)
    {
        // Ensure is array
        $records = (is_array($records)) ? $records : [$records];
        foreach ($records as $record) {
            $table = $this->getSchema()->table($tableName);
            $table->insert($record);
        }        
    }

    /**
     * Creates a table in the database if not exists
     */
    public function createTable($tableName, $columns)
    {
        // Check if table exists
        if ($this->getSchema()->hasTable($tableName)) {
            throw new \SoloSearch\Core\Model\Exception('Table already exists');
        }

        // Create table with fields definition
        $this->getSchema()->create($tableName, function (Blueprint $table) use ($columns) {
        
            // Create columns
            foreach ($columns as $columnName => $definition) {
                
                // Fix some types
                $column = null;
                $type = $definition['type'];
                if ($type == 'autoincrement') {
                    $type = 'bigIncrements';
                }
                
                if ($type == 'string') {
                    $length = $definition['length'] ?? 255;
                    $column = $table->{$type}($columnName, $length);
                }
                else {
                    $column = $table->{$type}($columnName);
                }

                // Common modifiers for non autoincrements
                if ($column) {
                    
                    if ($definition['nullable'] ?? false) {
                        $column->nullable();
                    }

                    if (isset($definition['default'])) {
                        $column->default($definition['default']);
                    }

                    if ($definition['unique'] ?? false) {
                        $column->unique();
                    }
                }
            }
        });
    }

    /**
     * Returns schema builder for the database
     */
    public function getSchema()
    {
        return $this->manager->getSchemaBuilder();
    }

    /**
     * Returns the database manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}