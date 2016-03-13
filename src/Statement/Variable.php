<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Variable statement
 */
class Variable implements StatementInterface
{
    /**
     * Name of the variable or its expression
     *
     * @var StatementInterface|string
     */
    private $name;

    /**
     * Variable statement
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Compiles variable statement
     *
     * @param ExporterInterface $export
     *
     * @return string
     */
    public function compile(ExporterInterface $export)
    {
        if ($this->name instanceof StatementInterface) {
            return sprintf('${%s}', $this->name->compile($export));
        }

        return sprintf('$%s', $this->name);
    }
}
