<?php

namespace spec\EcomDev\Compiler\Dispersion\Crc32;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ShortSpec extends ObjectBehavior
{

    function it_should_use_crc32_and_return_two_hex_chars_from_it()
    {
        $this->calculate('pro')->shouldReturn('6f'); // [6]bb4d6f[f]
        $this->calculate('programing')->shouldReturn('c5'); // [c]816595[5]
        $this->calculate('id_product_1')->shouldReturn('be'); // [b]1d53a8[e]
    }
}
