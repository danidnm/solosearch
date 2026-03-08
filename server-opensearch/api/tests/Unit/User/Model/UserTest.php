<?php

namespace SoloSearch\Tests\Unit\User\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\User\Model\User;
use SoloSearch\User\Model\Resource\User as UserResource;

class UserTest extends TestCase
{
    public function testUserDataSettlement(): void
    {
        /** @var User $user */
        $user = $this->container->get(User::class);
        
        $user->setData('username', 'testuser');
        $this->assertEquals('testuser', $user->getData('username'));
        
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testGetIdReturnsIdFromData(): void
    {
        /** @var User $user */
        $user = $this->container->get(User::class);
        $user->setData('id', 123);
        
        $this->assertEquals(123, $user->getId());
    }
}
