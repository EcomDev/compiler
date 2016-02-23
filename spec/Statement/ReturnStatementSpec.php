<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReturnStatementSpec extends ObjectBehavior
{
    function it_renders_statement_as_return(
        StatementInterface $statement,
        ExportInterface $export
    )
    {
        $statement->compile($export)->willReturn('$this');
        $this->beConstructedWith($statement);
        $this->compile($export)->shouldReturn('return $this');
    }

    function it_renders_scalar_as_return(
        ExportInterface $export
    )
    {
        $export->export('$this')->willReturn("'\$this'");
        $this->beConstructedWith('$this');
        $this->compile($export)->shouldReturn("return '\$this'");
    }
}
