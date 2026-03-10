<?php

namespace SoloSearch\Tests\Unit\User\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\User\Model\TokenRepository;
use SoloSearch\User\Model\TokenFactory;
use SoloSearch\User\Model\Resource\Token as TokenResource;
use SoloSearch\User\Model\Token;
use PHPUnit\Framework\MockObject\MockObject;

class TokenRepositoryTest extends TestCase
{
    /** @var TokenResource|MockObject */
    private $tokenResourceMock;

    /** @var TokenFactory|MockObject */
    private $tokenFactoryMock;

    /** @var TokenRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenResourceMock = $this->createMock(TokenResource::class);
        $this->tokenFactoryMock = $this->createMock(TokenFactory::class);

        $this->repository = new TokenRepository(
            $this->tokenFactoryMock,
            $this->tokenResourceMock
        );
    }

    public function testGetByTokenReturnsTokenFromResource()
    {
        $tokenValue = 'abc-123';
        $tokenMock = $this->createMock(Token::class);
        $tokenMock->method('getId')->willReturn(1);

        $this->tokenFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($tokenMock);

        $this->tokenResourceMock->expects($this->once())
            ->method('loadByField')
            ->with($tokenMock, 'token', $tokenValue);

        $result = $this->repository->getByToken($tokenValue);

        $this->assertSame($tokenMock, $result);
    }

    public function testGetByTokenReturnsNullIfNotFound()
    {
        $tokenValue = 'non-existent';
        $tokenMock = $this->createMock(Token::class);
        $tokenMock->method('getId')->willReturn(null);

        $this->tokenFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($tokenMock);

        $this->tokenResourceMock->expects($this->once())
            ->method('loadByField')
            ->with($tokenMock, 'token', $tokenValue);

        $result = $this->repository->getByToken($tokenValue);

        $this->assertNull($result);
    }

    public function testSaveCallsResource()
    {
        $tokenMock = $this->createMock(Token::class);

        $this->tokenResourceMock->expects($this->once())
            ->method('save')
            ->with($tokenMock);

        $this->repository->save($tokenMock);
    }

    public function testDeleteCallsResource()
    {
        $tokenMock = $this->createMock(Token::class);

        $this->tokenResourceMock->expects($this->once())
            ->method('delete')
            ->with($tokenMock);

        $this->repository->delete($tokenMock);
    }
}
