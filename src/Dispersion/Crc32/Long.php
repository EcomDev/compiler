<?php

namespace EcomDev\Compiler\Dispersion\Crc32;

/**
 * Simple string disperser
 */
class Long extends AbstractCrc32
{
    /**
     * Calculates string dispersion
     *
     * @param string $string
     *
     * @return string
     */
    public function calculate($string)
    {
        $string = $this->checksum($string);
        return $string[0] . $string[2] . $string[5] . $string[7];
    }
}
