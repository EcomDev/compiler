<?php

namespace spec\EcomDev\Compiler\Dispersion;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Crc32Spec extends ObjectBehavior
{

    function it_should_use_crc32_and_return_three_hex_chars_from_it()
    {
        $this->calculate('pro')->shouldReturn('64f');
        $this->calculate('programing')->shouldReturn('c65');
        $this->calculate('id_product_1')->shouldReturn('b58');
    }
}
