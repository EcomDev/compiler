<?php

namespace spec\EcomDev\Compiler\FileChecksum;

use EcomDev\Compiler\CacheKeyInterface;
use EcomDev\Compiler\FileChecksumInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class CachedSpec extends ObjectBehavior
{
    /**
     * File checksum calculator
     *
     * @var FileChecksumInterface
     */
    private $checksum;

    /**
     * Cache item pool interface
     *
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * Cache key model
     *
     * @var CacheKeyInterface
     */
    private $cacheKey;

    /**
     * @var CacheItemInterface
     */
    private $cacheItem;

    function let(
        CacheItemPoolInterface $cachePool,
        FileChecksumInterface $checksum,
        CacheKeyInterface $cacheKey,
        CacheItemInterface $cacheItem
    ) {
        $this->checksum = $checksum;
        $this->cachePool = $cachePool;
        $this->cacheKey = $cacheKey;
        $this->cacheItem = $cacheItem;

        $this->cacheKey->sanitize('file1.txt')
            ->willReturn('cache_key_for_file1_txt')
            ->shouldBeCalled();

        $this->cachePool
            ->getItem('cache_key_for_file1_txt')
            ->willReturn($this->cacheItem);

        $this->beConstructedWith($this->cachePool, $this->checksum, 86400, $this->cacheKey);
        $this->shouldImplement('EcomDev\Compiler\FileChecksumInterface');
    }

    function it_should_return_cached_value_for_cache_entry()
    {
        $this->cacheItem->isHit()->willReturn(true)->shouldBeCalled();
        $this->cacheItem->get()->willReturn('checksum_from_cache');

        $this->calculate('file1.txt')
            ->shouldReturn('checksum_from_cache');
    }

    function it_should_invoke_original_calculator_if_hit_is_not_met()
    {
        $this->cacheItem->isHit()->willReturn(false);
        $this->cacheItem->expiresAfter(86400)->willReturn($this->cacheItem)->shouldBeCalled();
        $this->cacheItem->set('checksum_from_original_checksum')->willReturn($this->cacheItem)->shouldBeCalled();
        $this->cachePool->saveDeferred($this->cacheItem)->shouldBeCalled();
        $this->checksum->calculate('file1.txt')
            ->willReturn('checksum_from_original_checksum')
            ->shouldBeCalled();

        $this->calculate('file1.txt')
            ->shouldReturn('checksum_from_original_checksum');
    }

}
