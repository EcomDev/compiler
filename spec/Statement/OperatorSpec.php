<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\Exporter;
use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\Statement\Operator;
use EcomDev\Compiler\Statement\Scalar;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OperatorSpec extends ObjectBehavior
{
    function it_compiles_a_statement_with_assign_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::ASSIGN);

        $this->shouldImplement('EcomDev\Compiler\StatementInterface');

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var = \'test\'');
    }

    function it_compiles_a_statement_with_assign_multiply_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::ASSIGN_MULTIPLY);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var *= \'test\'');
    }

    function it_compiles_a_statement_with_assign_add_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::ASSIGN_ADD);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var += \'test\'');
    }

    function it_compiles_a_statement_with_assign_sub_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::ASSIGN_SUB);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var -= \'test\'');
    }


    function it_compiles_a_statement_with_multiply_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::MULTIPLY);

        $left->compile($export)->willReturn('2');
        $right->compile($export)->willReturn('1');

        $this->compile($export)->shouldReturn('2 * 1');
    }

    function it_compiles_a_statement_with_add_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::ADD);

        $left->compile($export)->willReturn('1');
        $right->compile($export)->willReturn('2');

        $this->compile($export)->shouldReturn('1 + 2');
    }

    function it_compiles_a_statement_with_sub_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::SUB);

        $left->compile($export)->willReturn('5');
        $right->compile($export)->willReturn('2');

        $this->compile($export)->shouldReturn('5 - 2');
    }


    function it_compiles_a_statement_with_equal_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::EQUAL);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var == \'test\'');
    }

    function it_compiles_a_statement_with_equal_strict_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::EQUAL_STRICT);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var === \'test\'');
    }

    function it_compiles_a_statement_with_not_equal_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::NOT_EQUAL);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var != \'test\'');
    }

    function it_compiles_a_statement_with_not_equal_strict_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::NOT_EQUAL_STRICT);

        $left->compile($export)->willReturn('$var');
        $right->compile($export)->willReturn("'test'");

        $this->compile($export)->shouldReturn('$var !== \'test\'');
    }

    function it_compiles_a_statement_with_and_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::BOOL_AND);

        $left->compile($export)->willReturn('true');
        $right->compile($export)->willReturn('true');

        $this->compile($export)->shouldReturn('true && true');
    }

    function it_compiles_a_statement_with_or_operator(
        StatementInterface $left,
        StatementInterface $right,
        ExporterInterface $export
    )
    {
        $this->beConstructedWith($left, $right, Operator::BOOL_OR);

        $left->compile($export)->willReturn('true');
        $right->compile($export)->willReturn('false');

        $this->compile($export)->shouldReturn('true || false');
    }

    function it_compiles_a_statement_with_or_operator_and_groups_another_operator_statement()
    {
        $export = new Exporter();
        $left = new Operator(new Scalar(1), new Scalar(true), Operator::BOOL_AND);
        $right = new Operator(new Scalar(2), new Scalar(false), Operator::BOOL_AND);

        $this->beConstructedWith($left, $right, Operator::BOOL_OR);

        $this->compile($export)->shouldReturn('(1 && true) || (2 && false)');
    }

    function it_compiles_a_statement_with_or_operator_and_groups_only_one_another_operator_statement()
    {
        $export = new Exporter();
        $left = new Scalar(1);
        $right = new Operator(new Scalar(2), new Scalar(false), Operator::BOOL_AND);

        $this->beConstructedWith($left, $right, Operator::BOOL_OR);

        $this->compile($export)->shouldReturn('1 || (2 && false)');
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
