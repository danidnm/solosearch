<?php

namespace SoloSearch\Core\Controller;

use SoloSearch\Core\Http\ResponseFactory;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;

class BaseController
{
    protected ResponseFactory $responseFactory;
    protected Twig $view;

    public function __construct(
        ResponseFactory $responseFactory,
        Twig $view
    ) {
        $this->responseFactory = $responseFactory;
        $this->view = $view;
    }

    /**
     * Render Twig template
     * 
     * @param ResponseInterface $response
     * @param string $template
     * @param array $data
     * @return ResponseInterface
     */
    protected function render(ResponseInterface $response, string $template = 'default.twig', array $data = []): ResponseInterface
    {
        return $this->view->render($response, $template, $data);
    }
}