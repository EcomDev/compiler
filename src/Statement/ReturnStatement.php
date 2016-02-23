<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

class ReturnStatement
{
    /**
     * Expression for return statement
     *
     * @var mixed
     */
    private $expression;

    /**
     * Configure a return statement
     *
     * @param mixed|StatementInterface $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Exports a return statement
     *
     * @param ExportInterface $exporter
     *
     * @return string
     */
    public function compile(ExportInterface $exporter)
    {
        $statement = 'return %s';

        if ($this->expression instanceof StatementInterface) {
            return sprintf($statement, $this->expression->compile($exporter));
        }

        return sprintf($statement, $exporter->export($this->expression));
    }
}
