<?php

namespace spec\EcomDev\Compiler\Storage;

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

    function it_thorws_an_exception_if_unkown_identifier_is_requested()
    {
        $this->shouldThrow(
            new \InvalidArgumentException('Reference with identifier "identifier" does not exists in current index')
        )->duringGet('identifier');
    }

    function it_should_return_exact_number_of_references(
        ReferenceInterface $one,
        ReferenceInterface $two,
        ReferenceInterface $three
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

    function it_should_be_possible_to_contruct_it_with_references_as_arguments(
        ReferenceInterface $one,
        ReferenceInterface $two,
        ReferenceInterface $three
    )
    {
        $one->getId()->shouldNotBeCalled();
        $two->getId()->shouldNotBeCalled();
        $three->getId()->shouldNotBeCalled();

        $this->beConstructedWith(['one' => $one, 'two' => $two, 'three' => $three]);

        $this->get('one')->shouldReturn($one);
        $this->get('two')->shouldReturn($two);
        $this->get('three')->shouldReturn($three);
    }

    function it_returns_exportable_instance(
        ReferenceInterface $one,
        ReferenceInterface $two,
        ReferenceInterface $three
    )
    {
        $this->beConstructedWith(['one' => $one, 'two' => $two, 'three' => $three]);
        $this->export()->shouldReturn(
            [
                'data' => [
                    'one' => $one->getWrappedObject(),
                    'two' => $two->getWrappedObject(),
                    'three' => $three->getWrappedObject()
                ]
            ]
        );
    }

    function it_checks_for_modifications_and_returns_false_if_it_is_not_changed(
        ReferenceInterface $one
    )
    {
        $this->beConstructedWith(['one' => $one]);
        $this->isChanged()->shouldReturn(false);
        $this->get('one')->shouldReturn($one);
        $this->has('one')->shouldReturn(true);
        $this->isChanged()->shouldReturn(false);
    }

    function it_checks_for_modifications_and_returns_true_if_any_addition_has_been_done(
        ReferenceInterface $one,
        ReferenceInterface $two,
        ReferenceInterface $three
    )
    {
        $this->beConstructedWith(['one' => $one]);
        $this->isChanged()->shouldReturn(false);
        $this->add($two);
        $this->isChanged()->shouldReturn(true);
        $this->add($three);
        $this->isChanged()->shouldReturn(true);
    }

    function it_is_possible_to_inspect_all_added_reference_identifiers(
        ReferenceInterface $one,
        ReferenceInterface $two,
        ReferenceInterface $three
    )
    {
        $this->beConstructedWith(['one' => $one, 'two' => $two, 'three' => $three]);
        $this->inspect()->shouldReturn(['one', 'two', 'three']);
    }

    function it_is_possible_to_remove_one_of_the_references_and_it_notifies_change_flag(
        ReferenceInterface $one,
        ReferenceInterface $two,
        ReferenceInterface $three
    )
    {
        $this->beConstructedWith(['one' => $one, 'two' => $two, 'three' => $three]);
        $this->isChanged()->shouldReturn(false);
        $this->remove('one')->shouldReturn($this);
        $this->isChanged()->shouldReturn(true);
        $this->inspect()->shouldReturn(['two', 'three']);
        $this->has('one')->shouldReturn(false);
    }
}
