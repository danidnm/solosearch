<?php

namespace SoloSearch\Core\Controller;

use Psr\Container\ContainerInterface;

class BaseController
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    public function __construct(\Psr\Container\ContainerInterface $container
    ) {
        $this->container = $container;
    }
}