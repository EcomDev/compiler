<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Object access statement
 */
class ObjectAccess implements StatementInterface
{
    /**
     * Object name
     *
     * @var StatementInterface
     */
    private $object;

    /**
     * Property name
     *
     * @var StatementInterface|string
     */
    private $property;

    /**
     * Constructor for object access statement
     *
     * @param StatementInterface        $object
     * @param StatementInterface|string $property
     */
    public function __construct(StatementInterface $object, $property)
    {
        $this->object = $object;
        $this->property = $property;
    }

    /**
     * Returns compiles object access
     *
     * @param ExportInterface $export
     *
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        $object = $this->object->compile($export);
        if ($this->property instanceof StatementInterface) {
            return sprintf('%s->{%s}', $object, $this->property->compile($export));
        }

        return sprintf('%s->%s', $object, $this->property);
    }
}
