<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Variable statement
 *
 */
class Variable implements StatementInterface
{
    /**
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
     * @param ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        if ($this->name instanceof StatementInterface) {
            return sprintf('${%s}', $this->name->compile($export));
        }

        return sprintf('$%s', $this->name);
    }
}
