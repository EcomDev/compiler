<?php

namespace EcomDev\Compiler;

/**
 * Interface for calculation of file checksum
 *
 * It is vital part for source implementation
 * that depends on file modifications
 */
interface FileChecksumInterface
{
    /**
     * Returns file checksum
     *
     * @param string $file
     *
     * @return string
     */
    public function calculate($file);
}
