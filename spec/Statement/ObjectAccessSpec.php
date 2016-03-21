<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectAccessSpec extends ObjectBehavior
{
    function it_renders_string_as_object_property(
        StatementInterface $objectStatement,
        ExporterInterface $export)
    {
        $objectStatement->compile($export)->willReturn('$this');
        $this->beConstructedWith($objectStatement, 'dummy');
        $this->compile($export)->shouldReturn('$this->dummy');
        $this->shouldImplement('EcomDev\Compiler\StatementInterface');
    }

    function it_renders_statement_as_object_property_and_wraps_it_in_block(
        StatementInterface $objectStatement,
        StatementInterface $propertyStatement,
        ExporterInterface $export
    )
    {
        $objectStatement->compile($export)->willReturn('$this');
        $propertyStatement->compile($export)->willReturn('$another->call');
        $this->beConstructedWith($objectStatement, $propertyStatement);
        $this->compile($export)->shouldReturn('$this->{$another->call}');
    }
}
