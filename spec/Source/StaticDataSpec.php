<?php

namespace spec\EcomDev\Compiler\Source;

use EcomDev\Compiler\Statement\Call;
use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\Instance;
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
        $this->export()->shouldBeLike(
            [
                'id' => 'identifier',
                'checksum' => 'checksum',
                'container' => new Call('unserialize', [serialize($this->container)])
            ]
        );
    }
}
