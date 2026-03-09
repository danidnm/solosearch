<?php

namespace SoloSearch\Tests\Unit\Cache\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\Cache\Model\Cache;

class CacheTest extends TestCase
{
    public function testSetAndGetData()
    {
        $resourceMock = $this->createMock(\SoloSearch\Cache\Model\Resource\Cache::class);
        $cache = new Cache($resourceMock);
        $cache->setData('cache_key', 'some_key');
        $this->assertEquals('some_key', $cache->getData('cache_key'));
        
        $cache->setData(['foo' => 'bar']);
        $this->assertEquals('bar', $cache->getData('foo'));
    }
}
