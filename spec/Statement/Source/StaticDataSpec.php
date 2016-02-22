<?php

namespace spec\EcomDev\Compiler\Statement\Source;

use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\Scalar;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StaticDataSpec extends ObjectBehavior
{
    /**
     * @var Container
     */
    private $container;

    function let()
    {
        $this->container = new Container();

        $this->beConstructedWith('identifier', 'checksum', $this->container);
    }

    function it_returns_passed_identifier()
    {
        $this->getId()->shouldReturn('identifier');
    }

    function it_returns_passed_checksum()
    {
        $this->getChecksum()->shouldReturn('checksum');
    }

    function it_returns_passed_container()
    {
        $this->load()->shouldReturn($this->container);
    }

    function it_serializes_only_identifier_checksum_and_container()
    {
        $this->serialize()->shouldReturn(serialize([
            'id' => 'identifier',
            'checksum' => 'checksum',
            'container' => $this->container
        ]));
    }

    function it_unserializes_identifier_checksum_and_container()
    {
        $this->container->add(new Scalar(true));

        $this->unserialize(serialize([
            'id' => 'identifier2',
            'checksum' => 'checksum2',
            'container' => $this->container
        ]));

        $this->getId()->shouldReturn('identifier2');
        $this->getChecksum()->shouldReturn('checksum2');
        $container = $this->load();
        $container->shouldImplement('EcomDev\Compiler\Statement\Container');
        $iterator = $container->getIterator();
        $iterator->rewind();
        $iterator->current()->shouldBeLike(new Scalar(true));
    }
}
