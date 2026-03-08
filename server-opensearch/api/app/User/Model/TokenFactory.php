<?php

namespace SoloSearch\User\Model;

use DI\Container;

class TokenFactory
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create new Token model instance
     * 
     * @return Token
     */
    public function create()
    {
        return $this->container->make(Token::class);
    }
}
