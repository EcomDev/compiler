<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\Statement\Builder\Chain;
use EcomDev\Compiler\StatementInterface;
use PDepend\Source\AST\State;

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

        foreach ($arguments as $index => $argument) {
            if (is_string($argument)) {
                $arguments[$index] = $this->variable($argument);
            }
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

    /**
     * Creates and operator statement on all passed arguments
     *
     * @return Operator
     */
    public function andX()
    {
        return $this->chainMultiOperator(func_get_args(), Operator::BOOL_AND);
    }

    /**
     * Creates or operator statement on all passed arguments
     *
     * @return Operator
     */
    public function orX()
    {
        return $this->chainMultiOperator(func_get_args(), Operator::BOOL_OR);
    }

    /**
     * Chains multiple operands on operator together
     *
     * @param array $operands
     * @param $operator
     *
     * @return Operator
     */
    private function chainMultiOperator(array $operands, $operator)
    {
        $operands = array_reverse($operands);
        foreach ($operands as $index => $operand) {
            if (!$operand instanceof StatementInterface) {
                $operands[$index] = $this->scalar($operand);
            }
        }

        $right = array_shift($operands);

        while (count($operands) > 1) {
            $left = array_shift($operands);
            $right = $this->operator($left, $right, $operator);
        }

        $left = array_shift($operands);
        return $this->operator($left, $right, $operator);
    }

    /**
     * This chain creation
     *
     * @return Chain
     */
    public function this()
    {
        return $this->chain($this->variable('this'));
    }

    /**
     * Creates a new return statement
     *
     * @param string $value
     *
     * @return ReturnStatement
     */
    public function returnValue($value)
    {
        return new ReturnStatement($value);
    }

    /**
     * Returns a return closure statement
     *
     * @param StatementInterface[]|string[] $arguments
     * @param StatementInterface[] $body
     *
     * @return ReturnStatement
     */
    public function returnClosure(array $arguments, array $body = [])
    {
        return $this->returnValue(
            $this->closure($arguments, $this->container($body))
        );
    }
}
