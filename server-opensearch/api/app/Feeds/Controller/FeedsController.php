<?php

namespace SoloSearch\Feeds\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FeedsController extends \SoloSearch\Core\Controller\BaseController
{
    /**
     * @var \SoloSearch\Core\Model\Config
     */
    private \SoloSearch\Core\Model\Config $config;

    /**
     * @var \Psr\Container\ContainerInterface
     */
    public function __construct(
        \Psr\Container\ContainerInterface $container,
        \SoloSearch\Core\Model\Config $config
    ) {
        parent::__construct($container);

        $this->config = $config;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write('GET');
        return $response;
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write("POST");
        return $response;
    }

    public function put(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write("PUT");
        return $response;
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write("DELETE");
        return $response;
    }
}