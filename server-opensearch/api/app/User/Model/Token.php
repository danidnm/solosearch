<?php

namespace SoloSearch\User\Model;

use SoloSearch\Core\Model\AbstractModel;
use SoloSearch\User\Model\Resource\Token as TokenResource;

class Token extends AbstractModel
{
    /**
     * @var TokenResource
     */
    protected TokenResource $resource;

    /**
     * @param TokenResource $resource
     */
    public function __construct(TokenResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Load token by ID
     * 
     * @param mixed $id
     * @return $this
     */
    public function load($id)
    {
        $this->resource->load($this, $id);
        return $this;
    }

    /**
     * Save token to database
     * 
     * @return $this
     */
    public function save()
    {
        $this->resource->save($this);
        return $this;
    }

    /**
     * Delete token from database
     * 
     * @return $this
     */
    public function delete()
    {
        $this->resource->delete($this);
        return $this;
    }
}
