<?php

namespace EcomDev\Compiler;

/**
 * Object builder interface
 *
 * Used by default driver storage to export and process exportable objects
 */
interface ObjectBuilderInterface
{
    /**
     * Build a compiled representation of exportable interface
     *
     * @param ExportableInterface $exportable
     *
     * @return string
     */
    public function build(ExportableInterface $exportable);

    /**
     * Needs to bind itself to closure,
     * within which compiled code should run
     *
     * @param \Closure $closure
     *
     * @return \Closure
     */
    public function bind(\Closure $closure);
}
