<?php

namespace SoloSearch\Core\Controller;
 
use SoloSearch\Core\Http\ResponseFactory;
 
class BaseController
{
    protected ResponseFactory $responseFactory;
 
    public function __construct(
        ResponseFactory $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }
}