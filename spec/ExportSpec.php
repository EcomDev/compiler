<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExportSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('EcomDev\Compiler\Export');
        $this->shouldImplement('EcomDev\Compiler\ExportInterface');
    }

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

    function it_should_rise_an_exception_if_object_is_passed_that_does_not_implement_statement_interface()
    {
        $message = 'stdClass does not implement EcomDev\Compiler\StatementInterface';
        $this->shouldThrow(new \InvalidArgumentException($message))->during('export', [new \stdClass()]);
    }
}
