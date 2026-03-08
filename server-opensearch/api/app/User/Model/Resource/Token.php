<?php

namespace SoloSearch\User\Model\Resource;

use SoloSearch\Core\Model\Resource\DbModelAbstract;

class Token extends DbModelAbstract
{
    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->tableName = 'token';
        $this->idField = 'id';
    }
}
