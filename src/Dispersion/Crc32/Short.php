<?php

namespace EcomDev\Compiler\Dispersion\Crc32;

use EcomDev\Compiler\DispersionInterface;

/**
 * Short crc32 string disperser
 */
class Short extends AbstractCrc32
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
        return $string[0] . $string[7];
    }
}
