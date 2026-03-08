<?php

namespace SoloSearch\Core\Model;

use SoloSearch\Core\Model\Db;

abstract class AbstractDbModel
{
    /**
     * @var Db
     */
    protected Db $db;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var string
     */
    protected string $tableName;

    /**
     * @var string
     */
    protected string $idField = 'id';

    /**
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->_construct();
    }

    /**
     * Internal constructor for child classes to define table and id field
     */
    abstract protected function _construct();

    /**
     * Set data
     * 
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Get data
     * 
     * @param string $key
     * @return mixed
     */
    public function getData($key = null)
    {
        if ($key === null) {
            return $this->data;
        }
        return $this->data[$key] ?? null;
    }

    /**
     * Magic methods for getters and setters
     */
    public function __call($name, $arguments)
    {
        $method = substr($name, 0, 3);
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', substr($name, 3)));

        if ($method === 'get') {
            return $this->getData($key);
        }

        if ($method === 'set') {
            return $this->setData($key, $arguments[0] ?? null);
        }

        throw new \Exception("Method {$name} not found in " . get_class($this));
    }

    /**
     * Load model by ID
     * 
     * @param mixed $id
     * @return $this
     */
    public function load($id)
    {
        $result = $this->db->getManager()->table($this->tableName)
            ->where($this->idField, $id)
            ->first();

        if ($result) {
            $this->setData((array)$result);
        }

        return $this;
    }

    /**
     * Save model to database
     * 
     * @return $this
     */
    public function save()
    {
        $id = $this->getId();
        $table = $this->db->getManager()->table($this->tableName);

        // Filter data to only include database columns
        $columns = $this->db->getSchema()->getColumnListing($this->tableName);
        $dataToSave = array_intersect_key($this->data, array_flip($columns));

        if ($id) {
            $table->where($this->idField, $id)->update($dataToSave);
        } else {
            $newId = $table->insertGetId($dataToSave);
            $this->setData($this->idField, $newId);
        }

        return $this;
    }

    /**
     * Get primary key value
     * 
     * @return mixed
     */
    public function getId()
    {
        return $this->getData($this->idField);
    }
}
