<?php

namespace EcomDev\Compiler;

/**
 * Interface for exporting objects via Exporter model
 */
interface ExportableInterface
{
    /**
     * Should return list of constructor arguments
     * that needs to be passed to construct the same kind of object
     *
     * @return mixed[]
     */
    public function export();
}
