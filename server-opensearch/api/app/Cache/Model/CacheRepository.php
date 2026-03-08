<?php

namespace SoloSearch\Cache\Model;

use SoloSearch\Core\Model\AbstractModel;
use SoloSearch\Cache\Model\Resource\Cache as CacheResource;

class CacheRepository extends AbstractModel
{
    /**
     * @var CacheFactory
     */
    private CacheFactory $cacheFactory;

    /**
     * @var CacheResource
     */
    private CacheResource $cacheResource;

    /**
     * @param CacheFactory $cacheFactory
     * @param CacheResource $cacheResource
     */
    public function __construct(
        CacheFactory $cacheFactory,
        CacheResource $cacheResource
    ) {
        $this->cacheFactory = $cacheFactory;
        $this->cacheResource = $cacheResource;
    }

    /**
     * Get value from cache
     * 
     * @param string $key
     * @param bool $skipCache
     * @return mixed|null
     */
    public function get(string $key, bool $skipCache = false)
    {
        // Check in-memory cache
        if (!$skipCache && $this->hasData($key)) {
            return $this->getData($key);
        }

        // Load from database
        $cacheModel = $this->cacheFactory->create();
        $this->cacheResource->loadByField($cacheModel, 'cache_key', $key);
        
        // Extract the value
        $value = $cacheModel->getData('cache_value');
        if ($value === null) {
            return null;
        }

        try {
            $unserializedValue = unserialize($value);
            // Store in-memory
            $this->setData($key, $unserializedValue);
            return $unserializedValue;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Set value in cache
     * 
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        // Update in-memory
        $this->setData($key, $value);

        // Persist to database
        $cacheModel = $this->cacheFactory->create();
        $this->cacheResource->loadByField($cacheModel, 'cache_key', $key);
        
        $cacheModel->setData('cache_key', $key);
        $cacheModel->setData('cache_value', serialize($value));
        
        if (!$cacheModel->hasData('created_at')) {
            $cacheModel->setData('created_at', date('Y-m-d H:i:s'));
        }
        $cacheModel->setData('updated_at', date('Y-m-d H:i:s'));
        
        $this->cacheResource->saveByField($cacheModel, 'cache_key');

        return $this;
    }
}
