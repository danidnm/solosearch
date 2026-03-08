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
}
