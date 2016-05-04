<?php

namespace EcomDev\Compiler\FileChecksum;

use EcomDev\Compiler\CacheKeyInterface;
use EcomDev\Compiler\FileChecksumInterface;
use Psr\Cache\CacheItemPoolInterface;

class Cached implements FileChecksumInterface
{
    /**
     * Cache time to live for file checksum calculation
     *
     * @var int
     */
    private $ttl;

    /**
     * Cache item pool interface
     *
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * Slow file checksum model
     *
     * @var FileChecksumInterface
     */
    private $fileChecksum;

    /**
     * Cache identifier pattern
     *
     * @var string
     */
    private $cacheKey;

    /**
     * Cached file checksum calculator constructor
     *
     * @param CacheItemPoolInterface $cacheItemPool cache item pool
     * @param FileChecksumInterface $fileChecksum checksum calculator
     * @param int $ttl time to live for cache entry
     * @param CacheKeyInterface $cacheKey
     */
    public function __construct(
        CacheItemPoolInterface $cacheItemPool,
        FileChecksumInterface $fileChecksum,
        $ttl,
        CacheKeyInterface $cacheKey
    ) {
        $this->cacheItemPool = $cacheItemPool;
        $this->fileChecksum = $fileChecksum;
        $this->ttl = $ttl;
        $this->cacheKey = $cacheKey;
    }

    /**
     * Calculates file checksum via slow cache backend
     * if there is no cached version
     *
     * @param string $file
     *
     * @return string
     */
    public function calculate($file)
    {
        $cacheKey = $this->cacheKey->sanitize($file);
        $cacheItem = $this->cacheItemPool->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $value = $this->fileChecksum->calculate($file);
        $cacheItem = $cacheItem->set($value)->expiresAfter($this->ttl);
        $this->cacheItemPool->saveDeferred($cacheItem);
        return $value;
    }
}
