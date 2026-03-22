<?php

namespace SoloSearch\Feed\Controller\Api\V1;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use SoloSearch\Core\Controller\Api\ApiBaseController;
 
class FeedController extends ApiBaseController
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
        return $this->jsonResponse($response, ['message' => 'GET']);
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

        return $this->jsonResponse($response, $result);
    }

    public function put(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->jsonResponse($response, ['message' => 'PUT']);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->jsonResponse($response, ['message' => 'DELETE']);
    }
}