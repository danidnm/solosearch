<?php

namespace SoloSearch\Cache\Model;

use Psr\Container\ContainerInterface;

class CacheFactory
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Create new Cache model instance
     * 
     * @return Cache
     */
    public function create()
    {
        return $this->container->make(Cache::class);
    }
}
