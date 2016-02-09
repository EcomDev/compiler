<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\Statement\Assign;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssignSpec extends ObjectBehavior
{
    function let(StatementInterface $left, StatementInterface $right)
    {
        $this->beConstructedWith($left, $right);
    }

    function it_compiles_a_statement_with_default_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExportInterface $export
    )
    {
        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var = \'test\'');
    }

    function it_compiles_a_statement_with_multiply_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExportInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Assign::MULTIPLY);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var *= \'test\'');
    }

    function it_compiles_a_statement_with_add_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExportInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Assign::ADD);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var += \'test\'');
    }

    function it_compiles_a_statement_with_sub_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExportInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Assign::SUB);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var -= \'test\'');
    }

    function it_throws_an_exception_with_wrong_operator_type(
        StatementInterface $left,
        StatementInterface $right
    )
    {
        $this->beConstructedWith($left, $right, 'dummy_operator');

        $this->shouldThrow(new \InvalidArgumentException('Unknown operator type "dummy_operator"'))
            ->duringInstantiation();
    }
}
