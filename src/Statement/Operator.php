<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Operator statement
 */
class Operator implements StatementInterface
{
    /**
     * Addition operator
     *
     * @var string
     */
    const ADD = 'add';

    /**
     * Deduction operator
     *
     * @var string
     */
    const SUB = 'sub';

    /**
     * Multiplication operator
     *
     * @var string
     */
    const MULTIPLY = 'multiply';

    /**
     * Equal operator
     *
     * @var string
     */
    const EQUAL = 'equal';

    /**
     * Strict equal operator
     *
     * @var string
     */
    const EQUAL_STRICT = 'equal_strict';

    /**
     * Not equal operator
     *
     * @var string
     */
    const NOT_EQUAL = 'not_equal';

    /**
     * Strict not equal operator
     *
     * @var string
     */
    const NOT_EQUAL_STRICT = 'not_equal_strict';

    /**
     * Assign operator
     *
     * @var string
     */
    const ASSIGN = 'assign';

    /**
     * Assign addition operator
     *
     * @var string
     */
    const ASSIGN_ADD = 'assign_add';

    /**
     * Assign deduction operator
     *
     * @var string
     */
    const ASSIGN_SUB = 'assign_sub';

    /**
     * Assign multiply operator
     *
     * @var string
     */
    const ASSIGN_MULTIPLY = 'assign_multiply';

    /**
     * Boolean and operator
     *
     * @var string
     */
    const BOOL_AND = 'and';

    /**
     * Boolean or operator
     *
     * @var string
     */
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
     * @param string             $operator
     */
    public function __construct(
        StatementInterface $left,
        StatementInterface $right,
        $operator = self::EQUAL
    ) {
    
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
     * @param  ExportInterface $export
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
