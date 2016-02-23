<?php

namespace EcomDev\Compiler;

interface CompilerInterface
{
    /**
     * Returns a reference in the storage
     *
     * @param  Statement\SourceInterface $source
     * @return Storage\ReferenceInterface
     */
    public function compile(Statement\SourceInterface $source);


    /**
     * Interprets storage reference
     *
     * @param  Storage\ReferenceInterface $reference
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
