<?php

namespace spec\EcomDev\Compiler\Dispersion;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EvenSpec extends ObjectBehavior
{
    function it_returns_every_second_letter_on_string()
    {
        $this->calculate('something')->shouldReturn('oeh');
        $this->calculate('obc')->shouldReturn('boc');
        $this->calculate('ob')->shouldReturn('bbb');
    }

    function it_is_possible_to_use_custom_length()
    {
        $this->beConstructedWith(5);
        $this->calculate('something')->shouldReturn('oehns');
        $this->calculate('abc')->shouldReturn('bacba');
        $this->calculate('ob')->shouldReturn('bbbbb');
    }
}
