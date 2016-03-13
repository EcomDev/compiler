<?php

namespace spec\EcomDev\Compiler\Statement\Builder;

use EcomDev\Compiler\Exporter;
use EcomDev\Compiler\Statement\Builder;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChainSpec extends ObjectBehavior
{
    function let(StatementInterface $statement)
    {
        $this->beConstructedWith($statement, new Builder());
    }

    function it_allows_to_create_property_chain_on_statement(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$this');
        $this->property('foo1')->shouldReturn($this);
        $this->property('foo2')->shouldReturn($this);
        $this->property('foo3')->shouldReturn($this);
        $end = $this->end();
        $end->shouldImplement('EcomDev\Compiler\Statement\ObjectAccess');
        $end->compile($export)->shouldReturn('$this->foo1->foo2->foo3');
    }

    function it_allows_to_create_method_chain_on_statement(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$this');
        $this->method('foo1')->shouldReturn($this);
        $this->method('foo2', [1, 2, 3])->shouldReturn($this);
        $end = $this->end();
        $end->shouldImplement('EcomDev\Compiler\Statement\Call');
        $end->compile($export)->shouldReturn('$this->foo1()->foo2(1, 2, 3)');
    }

    function it_allows_to_create_array_chain_on_statement(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$item');
        $this->assoc('foo1')->shouldReturn($this);
        $this->assoc('foo2')->shouldReturn($this);
        $end = $this->end();
        $end->shouldImplement('EcomDev\Compiler\Statement\ArrayAccess');
        $end->compile($export)->shouldReturn('$item[\'foo1\'][\'foo2\']');
    }

    function it_allows_to_create_array_property_method_chain_on_statement(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$foo');
        $this->assoc('bar')->property('foo')->method('bar', ['foo']);
        $end = $this->end();
        $end->shouldImplement('EcomDev\Compiler\Statement\Call');
        $end->compile($export)->shouldReturn('$foo[\'bar\']->foo->bar(\'foo\')');
    }
}
