<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Operator statement
 *
 */
class Operator implements StatementInterface
{
    const ADD = 'add';
    const SUB = 'sub';
    const MULTIPLY = 'multiply';

    const EQUAL = 'equal';
    const EQUAL_STRICT = 'equal_strict';

    const NOT_EQUAL = 'not_equal';
    const NOT_EQUAL_STRICT = 'not_equal_strict';

    const ASSIGN = 'assign';
    const ASSIGN_ADD = 'assign_add';
    const ASSIGN_SUB = 'assign_sub';
    const ASSIGN_MULTIPLY = 'assign_multiply';

    const BOOL_AND = 'and';
    const BOOL_OR = 'or';

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
     * Available operator types
     *
     * @var string[]
     */
    protected $availableOperators = [
        self::ASSIGN => '=',
        self::ASSIGN_ADD => '+=',
        self::ASSIGN_SUB => '-=',
        self::ASSIGN_MULTIPLY => '*=',
        self::ADD => '+',
        self::SUB => '-',
        self::MULTIPLY => '*',
        self::EQUAL => '==',
        self::EQUAL_STRICT => '===',
        self::NOT_EQUAL => '!=',
        self::NOT_EQUAL_STRICT => '!==',
        self::BOOL_AND => '&&',
        self::BOOL_OR => '||'
    ];

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

        if (!isset($this->availableOperators[$operator])) {
            throw new \InvalidArgumentException(
                sprintf('Unknown operator type "%s"', $operator)
            );
        }

        $this->operator = $this->availableOperators[$operator];
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
