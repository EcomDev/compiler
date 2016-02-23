<?php

namespace EcomDev\Compiler\Dispersion;

use EcomDev\Compiler\DispersionInterface;

class Simple implements DispersionInterface
{
    /**
     * Dispersion code
     *
     * @var string
     */
    private $code;

    /**
     * Simple dispersion constructor
     *
     * Allows specification of custom dispersion string
     *
     * @param string $code
     */
    public function __construct($code = 'default')
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Creates dispersion of the string
     *
     * @param string $string
     *
     * @return string
     */
    public function calculate($string)
    {
        return $this->code;
    }
}
