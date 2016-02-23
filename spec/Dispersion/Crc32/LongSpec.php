<?php

namespace spec\EcomDev\Compiler\Dispersion\Crc32;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LongSpec extends ObjectBehavior
{
    function it_should_use_crc32_and_return_three_hex_chars_from_it()
    {
        $this->calculate('pro')->shouldReturn('6b6f'); // [6]b[b]4d[6]f[f]
        $this->calculate('programing')->shouldReturn('c195'); // [c]8[1]65[9]5[5]
        $this->calculate('id_product_1')->shouldReturn('bdae'); // [b]1[d]53[a]8[e]
    }
}
