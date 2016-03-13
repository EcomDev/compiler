<?php

namespace EcomDev\Compiler;

/**
 * Parses any data into statements
 *
 */
interface ParserInterface
{
    /**
     * Parses data into statements
     *
     * @param mixed $data
     * @return StatementInterface[]
     */
    public function parse($data);
}
