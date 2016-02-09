<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;

interface ContainerInterface
    extends \IteratorAggregate
{
    /**
     * Adds a new statement to a container
     *
     * @param StatementInterface $statement
     * @return $this
     */
    public function add(StatementInterface $statement);

    /**
     * Returns available statements via iterator
     *
     * @return StatementInterface[]
     */
    public function getIterator();
}
