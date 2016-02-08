<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;

interface ContainerInterface
    extends \Iterator
{
    /**
     * Returns current statement form the list
     *
     * @return StatementInterface
     */
    public function current();
}
