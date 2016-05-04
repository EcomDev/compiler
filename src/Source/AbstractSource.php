<?php

namespace EcomDev\Compiler\Source;

use EcomDev\Compiler\ChecksumInterface;
use EcomDev\Compiler\SourceInterface;

abstract class AbstractSource implements SourceInterface
{
    /**
     * Identifier of the source
     *
     * @var string
     */
    private $id;

    /**
     * Checksum of the source
     *
     * @var string
     */
    private $checksum;


    /**
     * Source constructor
     *
     * @param string $id
     * @param string $checksum
     */
    public function __construct($id, $checksum)
    {
        $this->id = $id;
        $this->checksum = $checksum;
    }

    /**
     * Returns identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns checksum
     *
     * @return string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }
}
