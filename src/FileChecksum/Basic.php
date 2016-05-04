<?php

namespace EcomDev\Compiler\FileChecksum;

use EcomDev\Compiler\FileChecksumInterface;

class Basic implements FileChecksumInterface
{
    /**
     * Returns file checksum
     *
     * @param string $file
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
