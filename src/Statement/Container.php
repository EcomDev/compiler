<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;

class Container implements ContainerInterface
{
    private $statements;

    public function __construct(array $statements = [])
    {
        $this->statements = $statements;
    }

    public function add(StatementInterface $statement)
    {
        $this->statements[] = $statement;
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->statements);
    }
}
