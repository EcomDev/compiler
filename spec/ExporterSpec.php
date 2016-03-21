<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\ExportableInterface;
use EcomDev\Compiler\ObjectBuilderInterface;
use EcomDev\Compiler\Source\StaticData;
use EcomDev\Compiler\Statement\Instance;
use EcomDev\Compiler\StatementInterface;
use EcomDev\Compiler\Storage\Reference;
use PDepend\Source\AST\State;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExporterSpec extends ObjectBehavior
{
    function it_should_export_float_as_float()
    {
        $this->export(1.01)->shouldReturn('1.01');
    }

    function it_should_export_int_as_int()
    {
        $this->export(1)->shouldReturn('1');
    }


    function it_should_export_true_as_lowecased_true()
    {
        $this->export(true)->shouldReturn('true');
    }

    function it_should_export_false_as_lowecased_false()
    {
        $this->export(false)->shouldReturn('false');
    }

    function it_should_export_null_as_lowecased_null()
    {
        $this->export(null)->shouldReturn('null');
    }

    function it_should_export_string_as_string()
    {
        $this->export('test_string')->shouldReturn("'test_string'");
    }

    function it_should_export_array_as_simplified_array_syntax()
    {
        $this->export([1, 2, 3])->shouldReturn("[0 => 1, 1 => 2, 2 => 3]");
    }

    function it_should_export_statement_via_its_compile_method(StatementInterface $statement)
    {
        $statement->compile($this)->willReturn('new PHP\\Class()');
        $this->export($statement)->shouldEqual('new PHP\\Class()');
    }

    function it_should_rise_an_exception_if_object_is_passed_that_does_not_implement_statement_interface()
    {
        $message = 'stdClass does not implement EcomDev\Compiler\StatementInterface';
        $this->shouldThrow(new \InvalidArgumentException($message))->during('export', [new \stdClass()]);
    }

    function it_should_be_possible_use_exportable_interface_for_exporting_if_object_builder_is_provided(
        ExportableInterface $exportable,
        ObjectBuilderInterface $objectBuilder
    )
    {
        $objectBuilder->build($exportable)->shouldBeCalled()->willReturn(new Instance('stdClass'));
        $this->beConstructedWith($objectBuilder);
        $this->export($exportable)->shouldReturn('new stdClass()');
    }
}
