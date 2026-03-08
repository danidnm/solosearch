<?php

namespace SoloSearch\User\Model;

use SoloSearch\Core\Model\AbstractDbModel;

class Token extends AbstractDbModel
{
    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->tableName = 'token';
        $this->idField = 'id';
    }
}
