<?php

namespace EcomDev\Compiler\Parser;

use EcomDev\Compiler\ParserInterface;
use EcomDev\Compiler\Statement\ContainerInterface;

/**
 * Implements Callback parser interface
 */
class Callback implements ParserInterface
{
    /**
     * It implements callback for item
     *
     * @var \Closure
     */
    private $callback;

    /**
     * Creates a callback parser
     *
     * @param \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Parses data via calling callback on top of it
     *
     * @param mixed $data
     *
     * @return ContainerInterface
     */
    public function parse($data)
    {
        $callback = $this->callback;
        return $callback($data);
    }
}
