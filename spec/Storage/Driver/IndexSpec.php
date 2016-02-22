<?php

namespace spec\EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\Storage\ReferenceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexSpec extends ObjectBehavior
{
    public function it_adds_a_new_reference(ReferenceInterface $reference)
    {
        $reference->getId()->willReturn('identifier');
        $this->add($reference)->shouldReturn($this);
        $this->has('identifier')->shouldReturn(true);
        $this->get('identifier')->shouldReturn($reference);
    }

    public function it_returns_false_if_reference_is_not_added()
    {
        $this->has('identifier')->shouldReturn(false);
    }

    public function it_thorws_an_exception_if_unkown_identifier_is_requested()
    {
        $this->shouldThrow(
            new \InvalidArgumentException('Reference with identifier "identifier" does not exists in current index')
        )->duringGet('identifier');
    }

    public function it_should_return_exact_number_of_references(
        ReferenceInterface $one, ReferenceInterface $two, ReferenceInterface $three
    )
    {
        $one->getId()->willReturn('one');
        $two->getId()->willReturn('two');
        $three->getId()->willReturn('three');

        $this->add($one);
        $this->count()->shouldReturn(1);
        $this->add($two);
        $this->count()->shouldReturn(2);
        $this->add($three);
        $this->count()->shouldReturn(3);
    }

}
