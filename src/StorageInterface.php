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
     * @param SourceInterface $source
     *
     * @return Storage\ReferenceInterface
     */
    public function store(SourceInterface $source);

    /**
     * Tries to find reference within storage by using reference for search
     *
     * @param SourceInterface $source
     *
     * @return bool|Storage\ReferenceInterface
     */
    public function find(SourceInterface $source);


    /**
     * Tries to find reference within storage by id
     *
     * @param string $id
     *
     * @return bool|Storage\ReferenceInterface
     */
    public function findById($id);

    /**
     * Returns stored php code as a string for specified reference
     *
     * @param Storage\ReferenceInterface $referenceInterface
     *
     * @return mixed
     */
    public function get(Storage\ReferenceInterface $referenceInterface);

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
