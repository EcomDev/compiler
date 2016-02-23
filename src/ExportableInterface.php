<?php

namespace EcomDev\Compiler;

/**
 * Interface for exportable objects via __set_state magic static method
 */
interface ExportableInterface
{
    /**
     * Implements exportable interface
     *
     * @param  array $objectState
     * @return ExportableInterface
     */
    public static function __set_state($objectState);
}
