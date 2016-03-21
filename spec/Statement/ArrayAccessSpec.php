<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayAccessSpec extends ObjectBehavior
{
    function it_compiles_array_access_with_key(ExporterInterface $export, StatementInterface $statement)
    {
        $this->beConstructedWith($statement, 'key');
        $this->shouldImplement('EcomDev\Compiler\StatementInterface');
        $statement->compile($export)->willReturn('$this->table');
        $export->export('key')->willReturn("'key'");
        $this->compile($export)->shouldReturn('$this->table[\'key\']');
    }

    function it_compiles_array_access_without_key(ExporterInterface $export, StatementInterface $statement)
    {
        $this->beConstructedWith($statement);
        $statement->compile($export)->willReturn('$this->table');
        $this->compile($export)->shouldReturn('$this->table[]');
    }
}
