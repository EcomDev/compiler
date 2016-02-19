<?php

namespace EcomDev\Compiler\Dispersion;

use EcomDev\Compiler\DispersionInterface;

/**
 * Simple string disperser
 *
 *
 */
class Crc32 implements DispersionInterface
{
    /**
     * Calculates string dispersion
     *
     * @param string $string
     * @return string
     */
    public function calculate($string)
    {
        $string = dechex(crc32($string));
        return $string[0] . $string[3] . $string[6];
    }
}
