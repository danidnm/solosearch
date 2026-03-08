<?php

namespace SoloSearch\Cache\Console;

use SoloSearch\Core\Console\CommandInterface;
use SoloSearch\Cache\Model\CacheRepository;

class Clear implements CommandInterface
{
    /**
     * @var CacheRepository
     */
    private CacheRepository $cacheRepository;

    /**
     * @param CacheRepository $cacheRepository
     */
    public function __construct(CacheRepository $cacheRepository)
    {
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * Runs the command to clear cache
     */
    public function run()
    {
        echo "Clearing cache...\n";
        
        try {
            $this->cacheRepository->clear();
            echo "Cache cleared successfully.\n";
        } catch (\Exception $e) {
            echo "Error clearing cache: " . $e->getMessage() . "\n";
        }
    }
}
