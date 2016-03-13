<?php

namespace EcomDev\Compiler\Source;

use EcomDev\Compiler\Statement\Call;
use EcomDev\Compiler\Statement\ContainerInterface;
use EcomDev\Compiler\SourceInterface;

/**
 * Static data source provider
 */
class StaticData extends AbstractSource
{
    /**
     * Container with statements
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Data constructor
     *
     * @param string $id
     * @param string $checksum
     * @param ContainerInterface $container
     */
    public function __construct($id, $checksum, ContainerInterface $container)
    {
        parent::__construct($id, $checksum);
        $this->container = $container;
    }

    /**
     * Returns assigned container
     *
     * @return ContainerInterface
     */
    public function load()
    {
        return $this->container;
    }

    /**
     * Returns list of arguments
     *
     * @return array
     */
    public function export()
    {
        return [
            'id' => $this->getId(),
            'checksum' => $this->getChecksum(),
            'container' => new Call('unserialize', [serialize($this->container)])
        ];
    }
}
