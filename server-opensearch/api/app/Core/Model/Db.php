<?php

namespace SoloSearch\Core\Model;

class Db
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(
        \Psr\Container\ContainerInterface $container
    ) {
        $this->container = $container;
    }

}