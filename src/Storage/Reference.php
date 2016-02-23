<?php

namespace EcomDev\Compiler\Storage;

use EcomDev\Compiler\Statement\Instance;
use EcomDev\Compiler\Statement\SourceInterface;

class Reference implements ReferenceInterface
{
    /**
     * Identifier of the reference
     *
     * @var string
     */
    private $id;

    /**
     * Checksum of the checksum
     *
     * @var string
     */
    private $checksum;

    /**
     * Source instance
     *
     * @var SourceInterface
     */
    private $source;

    public function __construct($id, $checksum, SourceInterface $source)
    {
        $this->id = $id;
        $this->checksum = $checksum;
        $this->source = $source;
        return $this;
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

    /**
     * Returns source instance
     *
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Returns an exportable PHP code for a reference
     *
     * @return Instance
     */
    public function export()
    {
        return new Instance(get_class($this), [$this->id, $this->checksum, $this->source]);
    }
}
