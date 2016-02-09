<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Call statement
 *
 * 
 */
class Call implements StatementInterface
{
    /**
     * Callee of the statement
     *
     * @var StatementInterface|string
     */
    private $callee;

    /**
     * List of callee arguments
     *
     * @var mixed[]
     */
    private $arguments;

    /**
     * Call statement constructor
     *
     * @param $callee
     * @param array $arguments
     */
    public function __construct($callee, array $arguments = [])
    {
        $this->callee = $callee;
        $this->arguments = $arguments;
    }

    /**
     * Compiles a call statement
     *
     * @param ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        $callee = $this->callee;
        if ($callee instanceof StatementInterface) {
            $callee = $this->callee->compile($export);
        }

        $arguments = [];
        foreach ($this->arguments as $argument) {
            $arguments[] = $export->export($argument);
        }

        return sprintf('%s(%s)', $callee, implode(', ', $arguments));
    }
}
