<?php

namespace SoloSearch\User\Model\Resource;

use SoloSearch\Core\Model\Resource\DbModel;

class Token extends DbModel
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
