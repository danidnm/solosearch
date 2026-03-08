<?php

namespace SoloSearch\Tests\Unit\User\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\User\Model\Token;
use SoloSearch\User\Model\Resource\Token as TokenResource;

class TokenTest extends TestCase
{
    public function testTokenDataSettlement(): void
    {
        /** @var Token $token */
        $token = $this->container->get(Token::class);
        
        $token->setData('token', 'abc-123');
        $this->assertEquals('abc-123', $token->getData('token'));
        
        $token->setUserId(1);
        $this->assertEquals(1, $token->getUserId());
    }

    public function testGetIdReturnsIdFromData(): void
    {
        /** @var Token $token */
        $token = $this->container->get(Token::class);
        $token->setData('id', 456);
        
        $this->assertEquals(456, $token->getId());
    }
}
