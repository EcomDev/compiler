<?php

namespace EcomDev\Compiler\Statement\Builder;

use EcomDev\Compiler\Statement\Builder;
use EcomDev\Compiler\StatementInterface;

/**
 * Chain builder class
 *
 */
class Chain
{
    /**
     * Current item in the chain
     *
     * @var StatementInterface
     */
    private $current;

    /**
     * Builder instance
     *
     * @var Builder
     */
    private $builder;

    /**
     * Creates a new chain statement
     *
     * @param StatementInterface $start
     * @param Builder $builder
     */
    public function __construct(StatementInterface $start, Builder $builder)
    {
        $this->current = $start;
        $this->builder = $builder;
    }

    /**
     * Creates property chain item
     *
     * @param string|StatementInterface $name
     * @return $this
     */
    public function property($name)
    {
        $this->current = $this->builder->objectAccess($this->current, $name);
        return $this;
    }

    /**
     * Creates method call chain item
     *
     * @param string|StatementInterface $name
     * @param array $arguments
     * @return $this
     */
    public function method($name, array $arguments = [])
    {
        $this->property($name);
        $this->current = $this->builder->call($this->current, $arguments);
        return $this;
    }

    /**
     * Creates array chain item
     *
     * @param string|StatementInterface $key
     * @return $this
     */
    public function assoc($key)
    {
        $this->current = $this->builder->arrayAccess($this->current, $key);
        return $this;
    }

    /**
     * Returns last chain object
     *
     * @return StatementInterface
     */
    public function end()
    {
        return $this->current;
    }

}
