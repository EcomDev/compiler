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
     * @param ExporterInterface $export
     *
     * @return string
     */
    public function compile(ExporterInterface $export);
}
