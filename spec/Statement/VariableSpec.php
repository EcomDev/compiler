<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VariableSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('varName');
    }

    function it_implements_statement_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\StatementInterface');
    }

    function it_compiles_a_variable_statement(ExporterInterface $export)
    {
        $this->compile($export)->shouldReturn('$varName');
    }

    function it_wraps_another_statement_in_block(ExporterInterface $export, StatementInterface $statement)
    {
        $this->beConstructedWith($statement);
        $statement->compile($export)->willReturn('$another->var');
        $this->compile($export)->shouldReturn('${$another->var}');
    }
}
