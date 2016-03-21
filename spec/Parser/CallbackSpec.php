<?php

namespace spec\EcomDev\Compiler\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallbackSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function () {
            // Dummy closure
        });
    }

    function it_implements_parser_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\ParserInterface');
    }

    function it_uses_callback_to_invoke_the_parser()
    {
        $this->beConstructedWith(function ($data) {
            return (object)['data' => $data];
        });

        $this->parse(true)->shouldBeLike((object)['data' => true]);
        $this->parse(['bla', 'bla', 'bla'])->shouldBeLike((object)['data' => ['bla', 'bla', 'bla']]);
    }
}
