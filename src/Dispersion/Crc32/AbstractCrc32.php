<?php


namespace EcomDev\Compiler\Dispersion\Crc32;

use EcomDev\Compiler\DispersionInterface;

abstract class AbstractCrc32 implements DispersionInterface
{
    /**
     * Returns checksum value
     *
     * @param string $value
     * @return string
     */
    protected function checksum($value)
    {
        return dechex(crc32($value));
    }
}
