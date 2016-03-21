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

    function it_allows_to_use_property_directly_on_the_chain_object(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$foo');
        $this->foo->bar->foo;
        $this->end()->compile($export)->shouldReturn('$foo->foo->bar->foo');
    }

    function it_allows_to_use_method_directly_on_the_chain_object(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$foo');
        $this->foo(1, 2)->shouldReturn($this);
        $this->end()->compile($export)->shouldReturn('$foo->foo(1, 2)');
    }

    function it_allows_to_use_array_access_directly_on_the_chain_object(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$foo');
        $this->foo[1]->bar(1, 2)->foo;
        $this->end()->compile($export)->shouldReturn('$foo->foo[1]->bar(1, 2)->foo');
    }

    function it_prohibits_unset_from_array_access_interface()
    {
        $this->shouldThrow(new \RuntimeException('You cannot unset array property on chain object'))
            ->duringOffsetUnset('test');
    }

    function it_prohibits_isset_for_array_access_interface()
    {
        $this->shouldThrow(new \RuntimeException('You cannot check if array property is set on chain object'))
            ->duringOffsetExists('test');;
    }

    function it_allows_setting_property_value_in_chain(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$foo');
        $this->foo[1]->bar(1, 2)->foo = 'test';
        $this->end()->compile($export)->shouldReturn('$foo->foo[1]->bar(1, 2)->foo = \'test\'');
    }

    function it_allows_setting_array_item_value_in_chain(StatementInterface $statement)
    {
        $export = new Exporter();
        $statement->compile($export)->willReturn('$foo');
        $this->foo[1]->bar(1, 2)->foo[1] = 'test';
        $this->end()->compile($export)->shouldReturn('$foo->foo[1]->bar(1, 2)->foo[1] = \'test\'');
    }

    function it_throws_error_if_any_new_chain_item_added_after_set_operation()
    {
        // Set up some set opration
        $this->foo[1]->bar(1, 2)->foo = 'test';

        $this
            ->shouldThrow(new \RuntimeException('You cannot add items to chain after assignment'))
            ->duringProperty('propertyName');

        $this
            ->shouldThrow(new \RuntimeException('You cannot add items to chain after assignment'))
            ->duringMethod('methodName', ['1']);

        $this
            ->shouldThrow(new \RuntimeException('You cannot add items to chain after assignment'))
            ->duringAssoc('key');
    }
}
