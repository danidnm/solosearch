<?php

namespace SoloSearch\Tests\Unit\Cache\Model;

use SoloSearch\Tests\TestCase;
use SoloSearch\Cache\Model\CacheRepository;
use SoloSearch\Cache\Model\CacheFactory;
use SoloSearch\Cache\Model\Resource\Cache as CacheResource;
use SoloSearch\Cache\Model\Cache;
use PHPUnit\Framework\MockObject\MockObject;

class CacheRepositoryTest extends TestCase
{
    /** @var CacheResource|MockObject */
    private $cacheResourceMock;

    /** @var CacheFactory|MockObject */
    private $cacheFactoryMock;

    /** @var CacheRepository */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheResourceMock = $this->createMock(CacheResource::class);
        $this->cacheFactoryMock = $this->createMock(CacheFactory::class);

        $this->repository = new CacheRepository(
            $this->cacheFactoryMock,
            $this->cacheResourceMock
        );
    }

    public function testGetReturnsFromInMemoryCache()
    {
        $key = 'test_key';
        $value = 'test_value';

        // Set value first (this will store it in-memory)
        $cacheModelMock = $this->createMock(Cache::class);
        $this->cacheFactoryMock->method('create')->willReturn($cacheModelMock);
        
        $this->repository->set($key, $value);

        // Verification: resource load should NOT be called
        $this->cacheResourceMock->expects($this->never())->method('loadByField');

        $result = $this->repository->get($key);
        $this->assertEquals($value, $result);
    }

    public function testGetLoadsFromResourceIfNotInMemory()
    {
        $key = 'db_key';
        $value = 'db_value';
        $serializedValue = serialize($value);

        $cacheModelMock = $this->createMock(Cache::class);
        $cacheModelMock->method('getData')->with('cache_value')->willReturn($serializedValue);

        $this->cacheFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($cacheModelMock);

        $this->cacheResourceMock->expects($this->once())
            ->method('loadByField')
            ->with($cacheModelMock, 'cache_key', $key);

        $result = $this->repository->get($key);
        $this->assertEquals($value, $result);

        // Subsequent call should return from memory
        $this->cacheResourceMock->expects($this->never())->method('loadByField');
        $this->assertEquals($value, $this->repository->get($key));
    }

    public function testGetWithSkipCacheTrue()
    {
        $key = 'test_key';
        $value = 'test_value';

        // Set value in-memory
        $cacheModelMock = $this->createMock(Cache::class);
        $this->cacheFactoryMock->method('create')->willReturn($cacheModelMock);
        $this->repository->set($key, $value);

        // With skipCache = true, it should call resource again
        $this->cacheResourceMock->expects($this->once())
            ->method('loadByField')
            ->with($cacheModelMock, 'cache_key', $key);
        
        $cacheModelMock->method('getData')->with('cache_value')->willReturn(serialize('new_value'));

        $result = $this->repository->get($key, true);
        $this->assertEquals('new_value', $result);
    }

    public function testGetReturnsNullIfNotFound()
    {
        $key = 'missing_key';

        $cacheModelMock = $this->createMock(Cache::class);
        $cacheModelMock->method('getData')->with('cache_value')->willReturn(null);

        $this->cacheFactoryMock->method('create')->willReturn($cacheModelMock);

        $result = $this->repository->get($key);
        $this->assertNull($result);
    }

    public function testSetPersistsToResource()
    {
        $key = 'save_key';
        $value = ['foo' => 'bar'];
        $serializedValue = serialize($value);

        $cacheModelMock = $this->createMock(Cache::class);
        
        $this->cacheFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($cacheModelMock);

        $this->cacheResourceMock->expects($this->once())
            ->method('loadByField')
            ->with($cacheModelMock, 'cache_key', $key);

        // Expectations for setData (key, value, timestamps)
        $setDataCalls = [];
        $cacheModelMock->method('setData')
            ->willReturnCallback(function($key, $value) use (&$setDataCalls) {
                $setDataCalls[$key] = $value;
                return $this;
            });
        
        $cacheModelMock->method('hasData')->with('created_at')->willReturn(false);

        $this->cacheResourceMock->expects($this->once())
            ->method('saveByField')
            ->with($cacheModelMock, 'cache_key');

        $this->repository->set($key, $value);

        $this->assertEquals($key, $setDataCalls['cache_key']);
        $this->assertEquals($serializedValue, $setDataCalls['cache_value']);
        $this->assertArrayHasKey('created_at', $setDataCalls);
        $this->assertArrayHasKey('updated_at', $setDataCalls);
    }

    public function testClearEmptiesMemoryAndTruncatesTable()
    {
        $this->cacheResourceMock->expects($this->once())->method('truncate');
        
        $this->repository->clear();
    }
}
