<?php

namespace spec\EcomDev\Compiler\Storage;

use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\Instance;
use EcomDev\Compiler\Statement\Scalar;
use EcomDev\Compiler\Source\StaticData;
use EcomDev\Compiler\SourceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class ReferenceSpec extends ObjectBehavior
{
    function let(SourceInterface $source)
    {
        $this->beConstructedWith('identifier', 'checksum', $source);
    }

    function it_returns_identifier()
    {
        $this->getId()->shouldReturn('identifier');
    }

    function it_returns_checksum()
    {
        $this->getChecksum()->shouldReturn('checksum');
    }

    function it_returns_source(SourceInterface $source)
    {
        $this->getSource()->shouldReturn($source);
    }

    function it_should_return_arguments_for_constructing_itself_on_export(SourceInterface $source)
    {
        $this->export()->shouldReturn(
            ['id' => 'identifier', 'checksum' => 'checksum', 'source' => $source->getWrappedObject()]
        );
    }
}
