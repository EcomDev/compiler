<?php

namespace EcomDev\Compiler;

/**
 * Interface for exporting objects via Export model
 */
interface ExportableInterface
{
    /**
     * Implements exportable interface
     *
     * @return StatementInterface|mixed
     */
    public function export();
}
