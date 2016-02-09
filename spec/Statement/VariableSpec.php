<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VariableSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('varName');
    }

    function it_compiles_a_variable_statement(ExportInterface $export)
    {
        $this->compile($export)->shouldReturn('$varName');
    }

    function it_wraps_another_statement_in_block(ExportInterface $export, StatementInterface $statement)
    {
        $this->beConstructedWith($statement);
        $statement->compile($export)->willReturn('$another->var');
        $this->compile($export)->shouldReturn('${$another->var}');
    }
}
