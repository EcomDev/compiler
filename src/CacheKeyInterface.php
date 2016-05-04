<?php

namespace EcomDev\Compiler;

/**
 * Generator for cache key
 */
interface CacheKeyInterface
{
    /**
     * Generates cache key based on input data
     *
     * @param string $input
     *
     * @return string
     */
    public function sanitize($input);
}
