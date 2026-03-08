<?php

namespace SoloSearch\User\Model\Resource;

use SoloSearch\Core\Model\Resource\DbModel;

class User extends DbModel
{
    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->tableName = 'user';
        $this->idField = 'id';
    }
}
