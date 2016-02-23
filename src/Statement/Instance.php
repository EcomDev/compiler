<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Class instance statement
 */
class Instance implements StatementInterface
{
    /**
     * Class name for instance
     *
     * @var string
     */
    private $className;

    /**
     * Arguments for instance
     *
     * @var StatementInterface[]|mixed[]
     */
    private $arguments;

    /**
     * Instance statement constructor
     *
     * @param string                       $className
     * @param StatementInterface[]|mixed[] $arguments
     */
    public function __construct($className, array $arguments = [])
    {
        $this->className = $className;
        $this->arguments = $arguments;
    }

    /**
     * Compiles statement
     *
     * @param ExportInterface $export
     *
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        $className = $this->className;
        if ($className instanceof StatementInterface) {
            $className = $className->compile($export);
        }

        $arguments = [];

        foreach ($this->arguments as $argument) {
            $arguments[] = $export->export($argument);
        }

        return sprintf('new %s(%s)', $className, implode(', ', $arguments));
    }
}
