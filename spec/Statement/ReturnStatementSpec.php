<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReturnStatementSpec extends ObjectBehavior
{
    function it_renders_statement_as_return(
        StatementInterface $statement,
        ExporterInterface $export
    )
    {
        $statement->compile($export)->willReturn('$this');
        $this->beConstructedWith($statement);
        $this->shouldImplement('EcomDev\Compiler\StatementInterface');
        $this->compile($export)->shouldReturn('return $this');
    }

    function it_renders_scalar_as_return(
        ExporterInterface $export
    )
    {
        $export->export('$this')->willReturn("'\$this'");
        $this->beConstructedWith('$this');
        $this->compile($export)->shouldReturn("return '\$this'");
    }
}
