<?php

namespace SoloSearch\User\Model;

use SoloSearch\Core\Model\AbstractDbModel;

class User extends AbstractDbModel
{
    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->tableName = 'user';
        $this->idField = 'id';
    }
}
