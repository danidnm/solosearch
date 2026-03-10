<?php

namespace SoloSearch\Tests\Unit\User\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\User\Model\TokenFactory;
use SoloSearch\User\Model\Token;

class TokenFactoryTest extends TestCase
{
    public function testCreateReturnsTokenInstance(): void
    {
        /** @var TokenFactory $factory */
        $factory = $this->container->get(TokenFactory::class);
        
        $token = $factory->create();
        
        $this->assertInstanceOf(Token::class, $token);
    }
}
