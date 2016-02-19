<?php

namespace EcomDev\Compiler;

/**
 * Interface for dispersion interface
 *
 * Can be used as grouping for storage drivers
 */
interface DispersionInterface
{
    /**
     * Creates dispersion of the string
     *
     * @param string $string
     * @return string
     */
    public function calculate($string);
}
