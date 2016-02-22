<?php

namespace EcomDev\Compiler;

use EcomDev\Compiler\Storage\ReferenceInterface;

/**
 * Storage for sources
 *
 * Source is stored with particular reference
 *
 */
interface StorageInterface
{
    /**
     * @param Statement\SourceInterface $source
     * @return ReferenceInterface
     */
    public function store(Statement\SourceInterface $source);

    /**
     * Tries to find reference within multiple storage
     *
     * @param Statement\SourceInterface $source
     */
    public function find(Statement\SourceInterface $source);

    /**
     * Tries to interpret the existing reference
     *
     * @param ReferenceInterface $reference
     * @return string
     */
    public function interpret(ReferenceInterface $reference);

    /**
     * Flushes the storage driver
     *
     * @return $this
     */
    public function flush();
}
