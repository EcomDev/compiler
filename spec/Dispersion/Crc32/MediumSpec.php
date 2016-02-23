<?php

namespace spec\EcomDev\Compiler\Dispersion\Crc32;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MediumSpec extends ObjectBehavior
{
    function it_should_use_crc32_and_return_three_hex_chars_from_it()
    {
        $this->calculate('pro')->shouldReturn('64f'); // [6]bb[4]d6f[f]
        $this->calculate('programing')->shouldReturn('c65'); // [c]81[6]595[5]
        $this->calculate('id_product_1')->shouldReturn('b5e'); // [b]1d[5]3a8[e]
    }
}
