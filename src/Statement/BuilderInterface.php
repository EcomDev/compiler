<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;

/**
 * Statement builder interface
 *
 */
interface BuilderInterface
{
    /**
     * Adds a statement type
     *
     * @param string $type
     * @param string $class
     * @return $this
     */
    public function addStatementType($type, $class);

    /**
     * Creates a statement based on called method
     *
     * Alias to build method
     *
     * @param string $name
     * @param array $arguments
     * @return StatementInterface
     */
    public function __call($name, array $arguments);

    /**
     * Builds a statement based on type and arguments
     *
     * @param $type
     * @param $arguments
     * @return StatementInterface
     */
    public function build($type, array $arguments);
}
