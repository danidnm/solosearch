<?php

namespace SoloSearch\Core\Controller;

use SoloSearch\Core\Http\ResponseFactory;
use SoloSearch\Core\Model\Layout;
use Psr\Http\Message\ResponseInterface;

class BaseController
{
    protected ResponseFactory $responseFactory;
    protected Layout $layout;

    public function __construct(
        ResponseFactory $responseFactory,
        Layout $layout
    ) {
        $this->responseFactory = $responseFactory;
        $this->layout = $layout;
    }

    /**
     * Render layout handle to HTML response
     * 
     * @param ResponseInterface $response
     * @param string $handle
     * @param int $status
     * @return ResponseInterface
     */
    protected function render(ResponseInterface $response, string $handle = 'default', int $status = 200): ResponseInterface
    {
        $html = $this->layout->render($handle);
        return $this->responseFactory->html($response, $html, $status);
    }
}