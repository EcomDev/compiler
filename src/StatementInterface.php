<?php

namespace EcomDev\Compiler;

/**
 * Statement interface
 */
interface StatementInterface
{
    /**
     * Returns a valid PHP code
     *
     * @param  ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export);
}
