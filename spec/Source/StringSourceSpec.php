<?php

namespace spec\EcomDev\Compiler\Source;

use EcomDev\Compiler\ParserInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StringSourceSpec extends ObjectBehavior
{
    /**
     * @var ParserInterface
     */
    private $parser;


    function let(ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->beConstructedWith($parser, 'some_string_dummy');
    }

    function it_should_implement_source_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\SourceInterface');
    }

    function it_should_calculate_checksum_automatically()
    {
        $this->getChecksum()->shouldReturn('643ddfad51ea84f3f18471b47d263d7b');
    }

    function it_should_use_content_checksum_based_if_none_specified()
    {
        $this->getId()->shouldReturn('inline_string_643ddfad51ea84f3f18471b47d263d7b');
    }

    function it_allows_specify_id_manually()
    {
        $this->beConstructedWith($this->parser, 'some_string_dummy', 'my_custom_identifier');
        $this->getId()->shouldReturn('my_custom_identifier');
    }

    function it_returns_constructed_arguments_with_generated_checksum_for_export()
    {
        $this->export()->shouldReturn([
            'parser' => $this->parser->getWrappedObject(),
            'string' => 'some_string_dummy',
            'id' => 'inline_string_643ddfad51ea84f3f18471b47d263d7b'
        ]);
    }

    function it_calls_parser_on_loading_the_data(ContainerInterface $container)
    {
        $this->parser->parse('some_string_dummy')->shouldBeCalled()->willReturn($container);
        $this->load()->shouldReturn($container);
    }
}
