<?php

namespace SoloSearch\User\Model\Resource;

use SoloSearch\Core\Model\Resource\DbModelAbstract;

class User extends DbModelAbstract
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
