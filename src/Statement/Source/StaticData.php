<?php

namespace EcomDev\Compiler\Statement\Source;

use EcomDev\Compiler\Statement\ContainerInterface;
use EcomDev\Compiler\Statement\SourceInterface;

class StaticData implements SourceInterface
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
     * @var ContainerInterface
     */
    private $container;

    public function __construct($id, $checksum, ContainerInterface $container)
    {
        $this->id = $id;
        $this->checksum = $checksum;
        $this->container = $container;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getChecksum()
    {
        return $this->checksum;
    }

    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'checksum' => $this->checksum,
            'container' => $this->container
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->id = $data['id'];
        $this->checksum = $data['checksum'];
        $this->container = $data['container'];
    }

    public function load()
    {
        return $this->container;
    }

}
