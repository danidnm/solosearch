<?php

namespace SoloSearch\User\Model;

use DI\Container;

class UserFactory
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create new User model instance
     * 
     * @return User
     */
    public function create()
    {
        return $this->container->make(User::class);
    }
}
