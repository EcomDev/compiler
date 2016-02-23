<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Scalar statement
 *
 * Used to output a regular PHP value
 */
class Scalar implements StatementInterface
{
    /**
     * Scalar value
     *
     * @var
     */
    private $value;

    /**
     * Scalar constructor.
     *
     * @param int|string|array|float $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Compiles normal scalar value
     *
     * @param  ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        return $export->export($this->value);
    }
}
