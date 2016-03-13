<?php

namespace EcomDev\Compiler;

class Storage implements StorageInterface
{
    /**
     * Driver instance
     *
     * @var Storage\DriverInterface
     */
    private $driver;

    /**
     * Initializes storage with supplied driver
     */
    public function __construct(Storage\DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Stores reference in the storage
     *
     * @param SourceInterface $source
     *
     * @return Storage\ReferenceInterface
     */
    public function store(SourceInterface $source)
    {
        return $this->driver->store($source);
    }

    /**
     * Tries to find reference in driver by source
     *
     * @param SourceInterface $source
     *
     * @return bool|Storage\ReferenceInterface
     */
    public function find(SourceInterface $source)
    {
        return $this->driver->find($source);
    }

    /**
     * Tries to find reference within driver by id
     *
     * @param string $id
     *
     * @return bool|Storage\ReferenceInterface
     */
    public function findById($id)
    {
        return $this->driver->findById($id);
    }

    /**
     * Tries to interpret the existing reference
     *
     * @param Storage\ReferenceInterface $reference
     *
     * @return string
     */
    public function interpret(Storage\ReferenceInterface $reference)
    {
        return $this->driver->interpret($reference);
    }

    /**
     * Flushes the storage driver
     *
     * @return $this
     */
    public function flush()
    {
        $this->driver->flush();
        return $this;
    }
}
