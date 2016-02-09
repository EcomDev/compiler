<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

class Closure implements StatementInterface
{
    /**
     * List of arguments
     *
     * @var StatementInterface[]
     */
    private $arguments;

    /**
     * Body of the closure
     *
     * @var ContainerInterface
     */
    private $body;

    /**
     * Closure constructor.
     * @param StatementInterface[] $arguments
     * @param ContainerInterface $body
     */
    public function __construct(array $arguments, ContainerInterface $body)
    {
        $this->arguments = $arguments;
        foreach ($arguments as $index => $argument) {
            if ($argument instanceof StatementInterface) {
                continue;
            }

            throw new \InvalidArgumentException(
                sprintf('Argument #%d does not implement %s', $index, 'EcomDev\Compiler\StatementInterface')
            );
        }
        $this->body = $body;
    }


    public function compile(ExportInterface $export)
    {
        $arguments = [];
        $body = [];

        foreach ($this->body as $line) {
            $body[] = sprintf("%s%s;", str_pad('', 4, ' '), $line->compile($export));
        }

        foreach ($this->arguments as $argument) {
            $arguments[] = $argument->compile($export);
        }

        return sprintf("function (%s) {\n%s\n}", implode(', ', $arguments), implode("\n", $body));
    }

}
