<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;

/**
 * Statements Container
 */
class Container implements ContainerInterface
{
    /**
     * @var StatementInterface[]
     */
    private $statements;

    /**
     * Statements container
     *
     * @param StatementInterface[] $statements
     */
    public function __construct(array $statements = [])
    {
        $this->statements = $statements;
    }

    /**
     * Adds statement to container
     *
     * @param  StatementInterface $statement
     * @return $this
     */
    public function add(StatementInterface $statement)
    {
        $this->statements[] = $statement;
        return $this;
    }

    /**
     * Returns iterator with statements
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->statements);
    }
}
