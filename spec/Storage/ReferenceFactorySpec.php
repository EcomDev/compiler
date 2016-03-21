<?php

namespace spec\EcomDev\Compiler\Storage;

use EcomDev\Compiler\Source\StaticData;
use EcomDev\Compiler\SourceInterface;
use EcomDev\Compiler\Storage\ReferenceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReferenceFactorySpec extends ObjectBehavior
{
    function it_creates_a_new_reference_instance(SourceInterface $sourceOne, SourceInterface $sourceTwo)
    {
        $sourceOne->getId()->willReturn('id1');
        $sourceOne->getChecksum()->willReturn('checksum1');
        $sourceTwo->getId()->willReturn('id2');
        $sourceTwo->getChecksum()->willReturn('checksum2');
        $referenceOne = $this->create($sourceOne);
        $referenceTwo = $this->create($sourceTwo);

        $referenceOne->shouldImplement('EcomDev\Compiler\Storage\Reference');
        $referenceTwo->shouldImplement('EcomDev\Compiler\Storage\Reference');
        $referenceOne->shouldNotEqual($referenceTwo);

        $referenceOne->getId()->shouldReturn('id1');
        $referenceOne->getChecksum()->shouldReturn('checksum1');
        $referenceTwo->getId()->shouldReturn('id2');
        $referenceTwo->getChecksum()->shouldReturn('checksum2');
    }

    function it_should_be_possible_to_specify_custom_class_for_factory(
        ReferenceInterface $index,
        SourceInterface $source
    )
    {
        $indexClass = get_class($index->getWrappedObject());
        $this->beConstructedWith($indexClass);
        $this->create($source)->shouldImplement($indexClass);
    }

    function it_should_throw_an_exception_if_class_does_not_implement_index_interface()
    {
        $this->beConstructedWith('stdClass');
        $this
            ->shouldThrow(
                new \InvalidArgumentException(
                    'stdClass does not implement EcomDev\Compiler\Storage\ReferenceInterface'
                )
            )
            ->duringInstantiation();
        ;
    }
}
