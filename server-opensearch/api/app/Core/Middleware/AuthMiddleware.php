<?php

namespace SoloSearch\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use SoloSearch\User\Model\TokenRepository;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var TokenRepository
     */
    private TokenRepository $tokenRepository;

    /**
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $tokenValue = $request->getHeaderLine('X-Auth-Token');

        if (empty($tokenValue)) {
            return $this->unauthorizedResponse('X-Auth-Token header missing');
        }

        $token = $this->tokenRepository->getByToken($tokenValue);

        if (!$token || !$token->getId()) {
            return $this->unauthorizedResponse('Invalid token');
        }

        // Attach user ID to request attributes for later use in controllers
        $request = $request->withAttribute('user_id', $token->getData('user_id'));

        return $handler->handle($request);
    }

    /**
     * Create an unauthorized response
     * 
     * @param string $message
     * @return ResponseInterface
     */
    private function unauthorizedResponse(string $message): ResponseInterface
    {
        $response = new Response();
        $result = [
            'status' => 'error',
            'message' => $message
        ];
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }
}
