<?php

namespace spec\EcomDev\Compiler\Storage;

use EcomDev\Compiler\Storage\IndexInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexFactorySpec extends ObjectBehavior
{
    function it_creates_a_new_index_instance()
    {
        $indexOne = $this->create();
        $indexTwo = $this->create();
        $indexOne->shouldImplement('EcomDev\Compiler\Storage\Index');
        $indexTwo->shouldImplement('EcomDev\Compiler\Storage\Index');
        $indexOne->shouldNotEqual($indexTwo);
    }

    function it_should_be_possible_to_specify_custom_class_for_factory(IndexInterface $index)
    {
        $indexClass = get_class($index->getWrappedObject());
        $this->beConstructedWith($indexClass);

        $this->create()->shouldImplement($indexClass);
    }

    function it_should_throw_an_exception_if_class_does_not_implement_index_interface()
    {
        $this->beConstructedWith('stdClass');
        $this
            ->shouldThrow(
                new \InvalidArgumentException(
                    'stdClass does not implement EcomDev\Compiler\Storage\IndexInterface'
                )
            )
            ->duringInstantiation();
        ;
    }
}
