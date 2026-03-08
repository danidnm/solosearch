<?php

namespace SoloSearch\Cache\Model;

use SoloSearch\Core\Model\AbstractModel;
use SoloSearch\Cache\Model\Resource\Cache as CacheResource;

class Cache extends AbstractModel
{
    /**
     * @var CacheResource
     */
    protected CacheResource $resource;

    /**
     * @param CacheResource $resource
     */
    public function __construct(CacheResource $resource)
    {
        $this->resource = $resource;
    }
}
