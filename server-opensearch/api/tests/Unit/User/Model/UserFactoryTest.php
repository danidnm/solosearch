<?php

namespace SoloSearch\Tests\Unit\User\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\User\Model\UserFactory;
use SoloSearch\User\Model\User;

class UserFactoryTest extends TestCase
{
    public function testCreateReturnsUserInstance(): void
    {
        /** @var UserFactory $factory */
        $factory = $this->container->get(UserFactory::class);
        
        $user = $factory->create();
        
        $this->assertInstanceOf(User::class, $user);
    }
}
