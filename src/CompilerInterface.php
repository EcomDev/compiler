<?php

namespace EcomDev\Compiler;

interface CompilerInterface
{
    /**
     * Returns a reference in the storage
     *
     * @param SourceInterface $source
     *
     * @return Storage\ReferenceInterface
     */
    public function compile(SourceInterface $source);


    /**
     * Interprets storage reference
     *
     * @param Storage\ReferenceInterface $reference
     *
     * @return mixed
     */
    public function interpret(Storage\ReferenceInterface $reference);

    /**
     * Flushes all compiler data in the storage
     *
     * @return $this
     */
    public function flush();
}
