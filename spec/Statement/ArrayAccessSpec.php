<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayAccessSpec extends ObjectBehavior
{
    function it_compiles_array_access_with_key(ExportInterface $export, StatementInterface $statement)
    {
        $this->beConstructedWith($statement, 'key');
        $statement->compile($export)->willReturn('$this->table');
        $export->export('key')->willReturn("'key'");
        $this->compile($export)->shouldReturn('$this->table[\'key\']');
    }

    function it_compiles_array_access_without_key(ExportInterface $export, StatementInterface $statement)
    {
        $this->beConstructedWith($statement);
        $statement->compile($export)->willReturn('$this->table');
        $this->compile($export)->shouldReturn('$this->table[]');
    }
}
