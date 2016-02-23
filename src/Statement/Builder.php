<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\StatementInterface;

/**
 * Statement builder class
 */
class Builder
{

    /**
     * Returns a scalar statement
     *
     * @param mixed $value
     *
     * @return Scalar
     */
    public function scalar($value)
    {
        return new Scalar($value);
    }

    /**
     * Returns a new call statement
     *
     * @param string                       $call
     * @param mixed[]|StatementInterface[] $arguments
     *
     * @return Call
     */
    public function call($call, array $arguments = [])
    {
        return new Call($call, $arguments);
    }

    /**
     * Returns a new operator statement
     *
     * @param mixed|StatementInterface $left
     * @param mixed|StatementInterface $right
     * @param string                   $operator
     *
     * @return Operator
     */
    public function operator($left, $right, $operator)
    {
        if (!$left instanceof StatementInterface) {
            $left = $this->scalar($left);
        }

        if (!$right instanceof StatementInterface) {
            $right = $this->scalar($right);
        }

        return new Operator($left, $right, $operator);
    }

    /**
     * Returns a new assign statement
     *
     * @param StatementInterface       $left
     * @param StatementInterface|mixed $right
     *
     * @return Operator
     */
    public function assign(StatementInterface $left, $right)
    {
        return $this->operator($left, $right, Operator::ASSIGN);
    }

    /**
     * Returns a new container with list of statements
     *
     * @param StatementInterface[] $statements
     *
     * @return Container
     */
    public function container(array $statements = [])
    {
        return new Container($statements);
    }

    /**
     * Creates a new array list statement
     *
     * @param array $list
     *
     * @return ArrayList
     */
    public function arrayList(array $list = [])
    {
        return new ArrayList($list);
    }

    /**
     * Returns array access instance
     *
     * @param StatementInterface $statement
     * @param mixed              $value
     *
     * @return ArrayAccess
     */
    public function arrayAccess(StatementInterface $statement, $value)
    {
        return new ArrayAccess($statement, $value);
    }

    /**
     * Returns a variable statement
     *
     * @param string|StatementInterface $name
     *
     * @return Variable
     */
    public function variable($name)
    {
        return new Variable($name);
    }

    /**
     * Returns object access statement
     *
     * @param StatementInterface        $object
     * @param string|StatementInterface $property
     *
     * @return ObjectAccess
     */
    public function objectAccess(StatementInterface $object, $property)
    {
        return new ObjectAccess($object, $property);
    }

    /**
     * Returns closure statement
     *
     * @param array          $arguments
     * @param Container|null $body
     *
     * @return Closure
     */
    public function closure(array $arguments = [], Container $body = null)
    {
        if ($body === null) {
            $body = $this->container();
        }

        return new Closure($arguments, $body);
    }

    /**
     * Return instance statement
     *
     * @param string                       $className
     * @param mixed[]|StatementInterface[] $arguments
     *
     * @return Instance
     */
    public function instance($className, array $arguments = [])
    {
        return new Instance($className, $arguments);
    }

    /**
     * Returns a new chain builder instance
     *
     * @param StatementInterface $start
     *
     * @return Builder\Chain
     */
    public function chain(StatementInterface $start)
    {
        return new Builder\Chain($start, $this);
    }
}
