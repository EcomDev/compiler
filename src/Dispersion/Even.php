<?php

namespace EcomDev\Compiler\Dispersion;

use EcomDev\Compiler\DispersionInterface;

class Even implements DispersionInterface
{
    /**
     * Size of the dispersion
     *
     * @var int
     */
    private $size;

    /**
     * Constructor for dispersion
     *
     * @param int $size
     */
    public function __construct($size = 3)
    {
        $this->size = $size;
    }

    /**
     * Calculates dispersion based on every second char
     *
     * @param string $string
     * @return string
     */
    public function calculate($string)
    {
        $chars = '';
        $total = $this->size;
        $offset = -1;
        $length = strlen($string);

        while ($total > 0) {
            $total --;
            $offset += 2;
            if (!isset($string[$offset])) {
                $chars .= $string[$offset % $length];
            } else {
                $chars .= $string[$offset];
            }
        }

        return $chars;
    }
}
