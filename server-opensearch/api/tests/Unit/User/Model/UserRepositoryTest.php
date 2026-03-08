<?php

namespace SoloSearch\Tests\Unit\User\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\User\Model\UserRepository;
use SoloSearch\User\Model\UserFactory;
use SoloSearch\User\Model\Resource\User as UserResource;
use SoloSearch\User\Model\User;
use PHPUnit\Framework\MockObject\MockObject;

class UserRepositoryTest extends TestCase
{
    /** @var UserResource|MockObject */
    private $userResourceMock;

    /** @var UserFactory|MockObject */
    private $userFactoryMock;

    /** @var UserRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userResourceMock = $this->createMock(UserResource::class);
        $this->userFactoryMock = $this->createMock(UserFactory::class);

        $this->repository = new UserRepository(
            $this->userFactoryMock,
            $this->userResourceMock
        );
    }

    public function testGetReturnsUserFromResource()
    {
        $userId = 1;
        $userMock = $this->createMock(User::class);
        $userMock->method('getId')->willReturn($userId);

        $this->userFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($userMock);

        $this->userResourceMock->expects($this->once())
            ->method('load')
            ->with($userMock, $userId);

        $result = $this->repository->get($userId);

        $this->assertSame($userMock, $result);
    }

    public function testGetReturnsNullIfUserNotFound()
    {
        $userId = 999;
        $userMock = $this->createMock(User::class);
        $userMock->method('getId')->willReturn(null);

        $this->userFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($userMock);

        $this->userResourceMock->expects($this->once())
            ->method('load')
            ->with($userMock, $userId);

        $result = $this->repository->get($userId);

        $this->assertNull($result);
    }

    public function testSaveCallsResourceAndCachesUser()
    {
        $userMock = $this->createMock(User::class);
        $userMock->method('getId')->willReturn(1);
        
        // Mocking date for save method internal calls
        $userMock->expects($this->atLeastOnce())
            ->method('setData');

        $this->userResourceMock->expects($this->once())
            ->method('save')
            ->with($userMock);

        $this->repository->save($userMock);

        // Verify it was cached by calling get (it should NOT call resource again)
        $this->userResourceMock->expects($this->never())
            ->method('load');

        $result = $this->repository->get(1);
        $this->assertSame($userMock, $result);
    }

    public function testDeleteCallsResourceAndUnsetsCache()
    {
        $userId = 1;
        $userMock = $this->createMock(User::class);
        $userMock->method('getId')->willReturn($userId);

        // Pre-fill cache by saving
        $this->repository->save($userMock);

        $this->userResourceMock->expects($this->once())
            ->method('delete')
            ->with($userMock);

        $this->repository->delete($userMock);

        // Verify it was removed from cache (it SHOULD call resource again)
        $this->userFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($userMock);

        $this->repository->get($userId);
    }
}
