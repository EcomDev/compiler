<?php

namespace EcomDev\Compiler;

class Compiler implements CompilerInterface
{
    /**
     * Storage for compiler
     *
     * @var StorageInterface
     */
    private $storage;

    /**
     * Constructs a compiler
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Returns a reference in the storage
     * after compiling source
     *
     * @param SourceInterface $source
     *
     * @return Storage\ReferenceInterface
     */
    public function compile(SourceInterface $source)
    {
        $reference = $this->storage->find($source);
        if ($reference === false
            || $reference->getChecksum() !== $source->getChecksum()
        ) {
            $reference = $this->storage->store($source);
        }

        return $reference;
    }

    /**
     * Interprets storage reference
     *
     * @param Storage\ReferenceInterface $reference
     *
     * @return mixed
     */
    public function interpret(Storage\ReferenceInterface $reference)
    {
        return $this->storage->interpret($reference);
    }

    /**
     * Flushes compiler storage
     *
     * @return $this
     */
    public function flush()
    {
        $this->storage->flush();
        return $this;
    }
}
