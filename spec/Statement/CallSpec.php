<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallSpec extends ObjectBehavior
{
    function it_generates_a_function_call(ExportInterface $export)
    {
        $export->export(1)->willReturn('1');
        $export->export(2)->willReturn('2');
        $export->export(3)->willReturn('3');
        $this->beConstructedWith('strpos', [1, 2, 3]);
        $this->compile($export)->shouldReturn('strpos(1, 2, 3)');
    }

    function it_allows_statement_as_callee(ExportInterface $export, StatementInterface $statement)
    {
        $export->export('argument')->willReturn("'argument'");
        $statement->compile($export)->willReturn('$this->methodName');
        $export->export(3)->willReturn('3');
        $this->beConstructedWith($statement, ['argument']);
        $this->compile($export)->shouldReturn('$this->methodName(\'argument\')');
    }
}
