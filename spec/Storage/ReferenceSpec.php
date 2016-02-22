<?php

namespace spec\EcomDev\Compiler\Storage;

use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\Scalar;
use EcomDev\Compiler\Statement\Source\StaticData;
use EcomDev\Compiler\Statement\SourceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    function it_should_return_serialized_reference_with_source()
    {
        $source = new StaticData('identifier', 'checksum', new Container());

        $this->beConstructedWith('identifier', 'checksum', $source);

        $this->serialize()->shouldReturn(serialize([
            'id' => 'identifier',
            'checksum' => 'checksum',
            'source' => $source
        ]));
    }

    function it_should_process_serialized_content()
    {
        $container = new Container([new Scalar(true)]);
        $source = new StaticData('identifier2', 'checksum2', $container);

        $this->unserialize(serialize([
            'id' => 'identifier2',
            'checksum' => 'checksum2',
            'source' => $source
        ]));

        $this->getId()->shouldReturn('identifier2');
        $this->getChecksum()->shouldReturn('checksum2');
        $this->getSource()->shouldBeLike($source);
    }
}
