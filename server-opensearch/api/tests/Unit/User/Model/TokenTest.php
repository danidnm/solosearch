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

    public function testLoad(): void
    {
        $resourceMock = $this->createMock(TokenResource::class);
        $resourceMock->expects($this->once())
            ->method('load')
            ->willReturnCallback(function($model, $id) {
                $model->setData(['id' => $id, 'token' => 'test-token']);
            });

        $token = new Token($resourceMock);
        $token->load(123);

        $this->assertEquals(123, $token->getId());
        $this->assertEquals('test-token', $token->getToken());
    }

    public function testSave(): void
    {
        $resourceMock = $this->createMock(TokenResource::class);
        $resourceMock->expects($this->once())
            ->method('save');

        $token = new Token($resourceMock);
        $token->save();
    }

    public function testDelete(): void
    {
        $resourceMock = $this->createMock(TokenResource::class);
        $resourceMock->expects($this->once())
            ->method('delete');

        $token = new Token($resourceMock);
        $token->delete();
    }

    public function testUnsetDataAndHasData(): void
    {
        /** @var Token $token */
        $token = $this->container->get(Token::class);
        $token->setData('key', 'value');
        
        $this->assertTrue($token->hasData('key'));
        $this->assertEquals('value', $token->getData('key'));
        
        $token->unsetData('key');
        $this->assertFalse($token->hasData('key'));
        $this->assertNull($token->getData('key'));
    }

    public function testMagicMethods(): void
    {
        /** @var Token $token */
        $token = $this->container->get(Token::class);
        
        $token->setSomeRandomValue('test');
        $this->assertEquals('test', $token->getSomeRandomValue());
        $this->assertEquals('test', $token->getData('some_random_value'));
    }
}
