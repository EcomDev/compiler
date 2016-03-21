<?php

namespace EcomDev\Compiler;

/**
 * Parses any data into statements
 */
interface ParserInterface
{
    /**
     * Parses data into statements
     *
     * @param mixed $data
     *
     * @return Statement\ContainerInterface
     */
    public function parse($data);
}
