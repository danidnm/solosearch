<?php

namespace SoloSearch\Core\Model\Resource;

use SoloSearch\Core\Model\Db;
use SoloSearch\Core\Model\AbstractModel;

abstract class DbModel
{
    /**
     * @var Db
     */
    protected Db $db;

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
     * Load model by ID
     * 
     * @param AbstractModel $model
     * @param mixed $id
     * @return $this
     */
    public function load(AbstractModel $model, $id)
    {
        $result = $this->db->getManager()->table($this->tableName)
            ->where($this->idField, $id)
            ->first();

        if ($result) {
            $model->setData((array)$result);
        }

        return $this;
    }

    /**
     * Save model to database
     * 
     * @param AbstractModel $model
     * @return $this
     */
    public function save(AbstractModel $model)
    {
        $id = $model->getData($this->idField);
        $table = $this->db->getManager()->table($this->tableName);

        // Filter data to only include database columns
        $columns = $this->db->getSchema()->getColumnListing($this->tableName);
        $dataToSave = array_intersect_key($model->getData(), array_flip($columns));

        if ($id) {
            $table->where($this->idField, $id)->update($dataToSave);
        } else {
            $newId = $table->insertGetId($dataToSave);
            $model->setData($this->idField, $newId);
        }

        return $this;
    }

    /**
     * Delete model from database
     * 
     * @param AbstractModel $model
     * @return $this
     */
    public function delete(AbstractModel $model)
    {
        $id = $model->getData($this->idField);
        if ($id) {
            $this->db->getManager()->table($this->tableName)
                ->where($this->idField, $id)
                ->delete();
            $model->setData($this->idField, null);
        }
        return $this;
    }
}
