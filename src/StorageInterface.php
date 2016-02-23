<?php

namespace EcomDev\Compiler;

/**
 * Storage for sources
 *
 * Source is stored with particular reference
 */
interface StorageInterface
{
    /**
     * Stores reference in the storage
     *
     * @param Statement\SourceInterface $source
     *
     * @return Storage\ReferenceInterface
     */
    public function store(Statement\SourceInterface $source);

    /**
     * Tries to find reference within multiple storage
     *
     * @param Statement\SourceInterface $source
     *
     * @return bool|Storage\ReferenceInterface
     */
    public function find(Statement\SourceInterface $source);

    /**
     * Tries to interpret the existing reference
     *
     * @param Storage\ReferenceInterface $reference
     *
     * @return string
     */
    public function interpret(Storage\ReferenceInterface $reference);

    /**
     * Flushes the storage driver
     *
     * @return $this
     */
    public function flush();
}
