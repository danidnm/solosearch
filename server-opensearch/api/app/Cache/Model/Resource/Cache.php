<?php

namespace SoloSearch\Cache\Model\Resource;

use SoloSearch\Core\Model\Resource\DbModelAbstract;

class Cache extends DbModelAbstract
{
    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->tableName = 'cache';
        $this->idField = 'id';
    }
}
