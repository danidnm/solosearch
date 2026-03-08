<?php

namespace SoloSearch\User\Model;

use SoloSearch\Core\Model\AbstractModel;
use SoloSearch\User\Model\Resource\User as UserResource;

class User extends AbstractModel
{
    /**
     * @var UserResource
     */
    protected UserResource $resource;

    /**
     * @param UserResource $resource
     */
    public function __construct(UserResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Load user by ID
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
     * Save user to database
     * 
     * @return $this
     */
    public function save()
    {
        $this->resource->save($this);
        return $this;
    }

    /**
     * Delete user from database
     * 
     * @return $this
     */
    public function delete()
    {
        $this->resource->delete($this);
        return $this;
    }
}
