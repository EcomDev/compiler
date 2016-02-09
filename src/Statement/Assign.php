<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Assign statement
 *
 */
class Assign implements StatementInterface
{
    const ADD = 'add';
    const SUB = 'sub';
    const MULTIPLY = 'multiply';
    const EQUAL = 'equal';

    /**
     * Left assignment part
     *
     * @var StatementInterface
     */
    private $left;

    /**
     * Left assignment part
     *
     * @var StatementInterface
     */
    private $right;

    /**
     * Operator for assignment
     *
     * @var string
     */
    private $operator;

    /**
     * Assign statement constructor
     *
     * @param StatementInterface $left
     * @param StatementInterface $right
     * @param string $operator
     */
    public function __construct(
        StatementInterface $left,
        StatementInterface $right,
        $operator = self::EQUAL
    )
    {
        $this->left = $left;
        $this->right = $right;

        $availableOperators = [
            self::EQUAL => '=',
            self::ADD => '+=',
            self::SUB => '-=',
            self::MULTIPLY => '*='
        ];

        if (!isset($availableOperators[$operator])) {
            throw new \InvalidArgumentException(
                sprintf('Unknown operator type "%s"', $operator)
            );
        }

        $this->operator = $availableOperators[$operator];
    }

    /**
     * Compiles an assignment statement
     *
     * @param ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        return sprintf(
            '%s %s %s',
            $this->left->compile($export),
            $this->operator,
            $this->right->compile($export)
        );
    }
}
