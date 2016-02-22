<?php

namespace EcomDev\Compiler\Storage;

use EcomDev\Compiler\Statement\SourceInterface;

class Reference implements ReferenceInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $checksum;

    /**
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

    public function getId()
    {
        return $this->id;
    }

    public function getChecksum()
    {
        return $this->checksum;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'checksum' => $this->checksum,
            'source' => $this->source
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->id = $data['id'];
        $this->checksum = $data['checksum'];
        $this->source = $data['source'];
    }
}
