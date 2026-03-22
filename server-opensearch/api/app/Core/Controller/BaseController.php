<?php

namespace SoloSearch\Core\Controller;
 
use Psr\Container\ContainerInterface;
use SoloSearch\Core\Http\ResponseFactory;
 
class BaseController
{
    protected ContainerInterface $container;
    protected ResponseFactory $responseFactory;
 
    public function __construct(
        ContainerInterface $container,
        ResponseFactory $responseFactory
    ) {
        $this->container = $container;
        $this->responseFactory = $responseFactory;
    }
}