<?php

namespace EcomDev\Compiler;

/**
 * Export interface
 */
interface ExportInterface
{
    /**
     * Exports php value into var export statement
     *
     * @param  mixed $value
     * @return string
     */
    public function export($value);
}
