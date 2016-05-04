<?php

namespace EcomDev\Compiler\FileChecksum;

use EcomDev\Compiler\FileChecksumInterface;

/**
 * Basic file checksum calculator
 *
 * Calculates checksum based
 * on file modification time and file size
 */
class Basic implements FileChecksumInterface
{
    /**
     * Returns file checksum
     *
     * @param string $file
     *
     * @return string
     */
    public function calculate($file)
    {
        $file = new \SplFileInfo($file);
        if (!$file->isFile() && !$file->isReadable()) {
            throw new \InvalidArgumentException(
                'Invalid argument supplied for checksum calculation, only existing files are allowed'
            );
        }

        return sprintf('%s:%s', $file->getMTime(), $file->getSize());
    }
}
