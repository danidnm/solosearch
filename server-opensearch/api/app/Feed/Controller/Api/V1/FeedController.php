<?php

namespace SoloSearch\Feed\Controller\Api\V1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FeedController extends \SoloSearch\Core\Controller\BaseController
{
    /**
     * @var \SoloSearch\Core\Model\Config
     */
    private \SoloSearch\Core\Model\Config $config;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param \SoloSearch\Core\Model\Config $config
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
        $data = $request->getParsedBody();
        $channel = $data['channel'] ?? 'default';
        
        $result = [
            'status' => 'success',
            'message' => 'Feed updated',
            'channel' => $channel
        ];

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
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