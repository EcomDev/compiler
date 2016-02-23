<?php

namespace EcomDev\Compiler\Storage;

use EcomDev\Compiler\Statement\SourceInterface;

/**
 * Reference for storage data
 */
interface ReferenceInterface
{
    /**
     * Identifier of the reference
     *
     * @return string
     */
    public function getId();

    /**
     * Checksum of the stored file by reference
     *
     * @return string
     */
    public function getChecksum();

    /**
     * Returns a source instance associated to this reference
     *
     * @return SourceInterface
     */
    public function getSource();
}
