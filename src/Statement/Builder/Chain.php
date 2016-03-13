<?php

namespace EcomDev\Compiler\Statement\Builder;

use EcomDev\Compiler\Statement\Builder;
use EcomDev\Compiler\StatementInterface;

/**
 * Chain builder class
 */
class Chain implements \ArrayAccess
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
     * Flag for the last item being an assignment
     *
     * @var bool
     */
    private $isAssignment = false;

    /**
     * Creates a new chain statement
     *
     * @param StatementInterface $start
     * @param Builder            $builder
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
     *
     * @return $this
     */
    public function property($name)
    {
        $this->validateState();
        $this->current = $this->builder->objectAccess($this->current, $name);
        return $this;
    }

    /**
     * Creates method call chain item
     *
     * @param string|StatementInterface $name
     * @param array                     $arguments
     *
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
     *
     * @return $this
     */
    public function assoc($key)
    {
        $this->validateState();
        $this->current = $this->builder->arrayAccess($this->current, $key);
        return $this;
    }

    /**
     * ArrayAccess interface implementation
     *
     * Prohibits isset() calls
     *
     * @param mixed $offset
     * @return bool
     * @throws \RuntimeException
     */
    public function offsetExists($offset)
    {
        throw new \RuntimeException('You cannot check if array property is set on chain object');
    }

    /**
     * ArrayAccess interface implementation to make possible assoc() in a more natural way
     *
     * @param mixed $offset
     * @return bool
     * @throws \RuntimeException
     */
    public function offsetGet($offset)
    {
        return $this->assoc($offset);
    }

    /**
     * Allows setting array value
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->assoc($offset);
        $this->assign($value);
    }

    /**
     * Creates a set operation for property
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->property($name);
        $this->assign($value);
    }

    /**
     * Allows chained method calls
     *
     * @param string $name
     * @param array $arguments
     * @return $this
     */
    public function __call($name, $arguments = [])
    {
        $this->method($name, $arguments);
        return $this;
    }

    /**
     * Allows chained property access
     *
     * @param $name
     * @return $this
     */
    public function __get($name)
    {
        $this->property($name);
        return $this;
    }

    /**
     * Adds assign statement to chain
     *
     * @param string $value
     * @return $this
     */
    private function assign($value)
    {
        $this->isAssignment = true;
        $this->current = $this->builder->assign($this->end(), $value);
        return $this;
    }

    /**
     * Validates current object state before invoking new chain addition
     *
     * @return $this
     * @throws \RuntimeException if something is wrong with object state
     */
    private function validateState()
    {
        if ($this->isAssignment) {
            throw new \RuntimeException('You cannot add items to chain after assignment');
        }

        return $this;
    }


    /**
     * ArrayAccess implementation
     *
     * Prohibits unset() calls on object
     *
     * @param mixed $offset
     *
     * @throws \RuntimeException
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('You cannot unset array property on chain object');
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
