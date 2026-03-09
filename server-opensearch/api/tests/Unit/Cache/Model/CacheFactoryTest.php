<?php

namespace SoloSearch\Tests\Unit\Cache\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\Cache\Model\CacheFactory;
use SoloSearch\Cache\Model\Cache;
use DI\Container;

class CacheFactoryTest extends TestCase
{
    public function testCreateReturnsCacheInstance()
    {
        $containerMock = $this->createMock(Container::class);
        $factory = new CacheFactory($containerMock);

        $containerMock->expects($this->once())
            ->method('make')
            ->with(Cache::class)
            ->willReturn($this->createMock(Cache::class));

        $result = $factory->create();
        $this->assertInstanceOf(Cache::class, $result);
    }
}
