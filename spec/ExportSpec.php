<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\ExportableInterface;
use EcomDev\Compiler\Statement\Source\StaticData;
use EcomDev\Compiler\StatementInterface;
use EcomDev\Compiler\Storage\Reference;
use PDepend\Source\AST\State;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExportSpec extends ObjectBehavior
{
    function it_should_export_float_as_float()
    {
        $this->export(1.01)->shouldReturn('1.01');
    }

    function it_should_export_int_as_int()
    {
        $this->export(1)->shouldReturn('1');
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

    function it_should_be_possible_to_export_exportable_ojects(ExportableInterface $exportable)
    {
        $exportable->export()->willReturn('true');
        $this->export($exportable)->shouldEqual("'true'");
    }

    function it_should_be_possible_to_export_statement_returned_by_exportoable_object(
        ExportableInterface $exportable, StatementInterface $statement
    )
    {
        $exportable->export()->willReturn($statement);
        $statement->compile($this)->willReturn('new ClassStatement()');
        $this->export($exportable)->shouldEqual('new ClassStatement()');
    }

    function it_should_rise_an_exception_if_object_is_passed_that_does_not_implement_statement_interface()
    {
        $message = 'stdClass does not implement'
                 . ' EcomDev\Compiler\StatementInterface or EcomDev\Compiler\ExportableInterface';
        $this->shouldThrow(new \InvalidArgumentException($message))->during('export', [new \stdClass()]);
    }
}
