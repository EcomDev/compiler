<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Array access statement
 */
class ArrayAccess implements StatementInterface
{
    /**
     * Statement
     *
     * @var StatementInterface
     */
    private $array;

    /**
     * Array key
     *
     * @var string|int|null|StatementInterface
     */
    private $key;

    /**
     * Constructor for statement access
     *
     * @param StatementInterface                 $array
     * @param string|int|null|StatementInterface $key
     */
    public function __construct(StatementInterface $array, $key = null)
    {
        $this->array = $array;
        $this->key = $key;
    }

    /**
     * Compiles a statement
     *
     * @param  ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        $array = $this->array->compile($export);
        $key = $this->key;

        if ($key === null) {
            return sprintf('%s[]', $array);
        }

        return sprintf('%s[%s]', $array, $export->export($key));
    }
}
