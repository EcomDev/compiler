<?php

namespace EcomDev\Compiler;

/**
 * Exporter interface
 */
interface ExporterInterface
{
    /**
     * Exports php value into var export statement
     *
     * @param mixed $value
     *
     * @return string
     */
    public function export($value);
}
