<?php

namespace EcomDev\Compiler;

interface FileChecksumInterface
{
    /**
     * Returns file checksum
     *
     * @param string $file
     * @return string
     */
    public function calculate($file);
}
