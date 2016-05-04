<?php

namespace EcomDev\Compiler\FileChecksum;

use EcomDev\Compiler\FileChecksumInterface;

class Md5 implements FileChecksumInterface
{
    /**
     * Returns file checksum
     *
     * @param string $file
     * @return string
     */
    public function calculate($file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(
                'Invalid argument supplied for checksum calculation, only existing files are allowed'
            );
        }

        return md5_file($file);
    }
}
