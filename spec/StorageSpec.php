<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\SourceInterface;
use EcomDev\Compiler\Storage\DriverInterface;
use EcomDev\Compiler\Storage\ReferenceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StorageSpec extends ObjectBehavior
{
    function let(DriverInterface $driver)
    {
        $this->beConstructedWith($driver);
    }

    function it_uses_driver_for_storing_source(
        DriverInterface $driver,
        SourceInterface $source,
        ReferenceInterface $reference
    )
    {
        $driver->store($source)->willReturn($reference);
        $this->store($source)->shouldReturn($reference);
    }

    function it_uses_driver_for_finding_reference(
        DriverInterface $driver,
        SourceInterface $source,
        ReferenceInterface $reference
    )
    {
        $driver->find($source)->willReturn($reference);
        $this->find($source)->shouldReturn($reference);
    }

    function it_uses_driver_for_finding_reference_by_id(
        DriverInterface $driver,
        ReferenceInterface $reference
    )
    {
        $driver->findById('id')->willReturn($reference);
        $this->findById('id')->shouldReturn($reference);
    }

    function it_uses_driver_for_getting_code(
        DriverInterface $driver,
        ReferenceInterface $reference
    )
    {
        $driver->interpret($reference)->willReturn(true);
        $this->interpret($reference)->shouldReturn(true);
    }

    function it_uses_driver_to_flush_data(DriverInterface $driver)
    {
        $driver->flush()->shouldBeCalled();
        $this->flush()->shouldReturn($this);
    }
}
