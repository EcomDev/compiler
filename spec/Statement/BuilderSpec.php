<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\Exporter;
use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\Operator;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BuilderSpec extends ObjectBehavior
{
    function it_creates_scalar_statement()
    {
        $statement = $this->scalar('value');
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Scalar');
        $statement->compile(new Exporter())->shouldReturn("'value'");
    }

    function it_creates_call_statement()
    {
        $statement = $this->call('strpos', [1, 2, 3]);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Call');
        $statement->compile(new Exporter())->shouldReturn('strpos(1, 2, 3)');
    }

    function it_creates_operator_statement()
    {
        $statement = $this->operator('item', 'value', Operator::EQUAL_STRICT);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Operator');
        $statement->compile(new Exporter())->shouldReturn("'item' === 'value'");
    }

    function it_creates_operator_statement_with_statement(StatementInterface $left, StatementInterface $right)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$this->method');
        $right->compile($export)->willReturn('$var2');

        $statement = $this->operator($left, $right, Operator::EQUAL_STRICT);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Operator');
        $statement->compile($export)->shouldReturn("\$this->method === \$var2");
    }

    function it_creates_assignment_operator(StatementInterface $left)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$var');
        $statement = $this->assign($left, 'value');
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Operator');
        $statement->compile($export)->shouldReturn("\$var = 'value'");
    }

    function it_creates_assignment_operator_with_statement(StatementInterface $left, StatementInterface $right)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$foo');
        $right->compile($export)->willReturn('$bar');
        $statement = $this->assign($left, $right);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Operator');
        $statement->compile($export)->shouldReturn("\$foo = \$bar");
    }

    function it_creates_and_operator()
    {
        $export = new Exporter();
        $statement = $this->andX(true, false);
        $statement->compile($export)->shouldReturn("true && false");
    }


    function it_creates_and_with_statements(StatementInterface $left, StatementInterface $right)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$var1');
        $right->compile($export)->willReturn('$var2');
        $statement = $this->andX($left, $right);
        $statement->compile($export)->shouldReturn('$var1 && $var2');
    }

    function it_creates_multiple_and_conditions()
    {
        $export = new Exporter();
        $statement = $this->andX(1, 2, 3, 4, 5);
        $statement->compile($export)->shouldReturn('1 && 2 && 3 && 4 && 5');
    }

    function it_creates_or_operator()
    {
        $export = new Exporter();
        $statement = $this->orX(true, false);
        $statement->compile($export)->shouldReturn("true || false");
    }


    function it_creates_or_with_statements(StatementInterface $left, StatementInterface $right)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$var1');
        $right->compile($export)->willReturn('$var2');
        $statement = $this->orX($left, $right);
        $statement->compile($export)->shouldReturn('$var1 || $var2');
    }

    function it_creates_multiple_or_conditions()
    {
        $export = new Exporter();
        $statement = $this->orX(1, 2, 3, 4, 5);
        $statement->compile($export)->shouldReturn('1 || (2 || (3 || (4 || 5)))');
    }

    function it_creates_contianer()
    {
        $container = $this->container();
        $container->shouldHaveType('EcomDev\Compiler\Statement\Container');
    }

    function it_creates_container_with_items_as_argument(StatementInterface $item)
    {
        $container = $this->container([$item->getWrappedObject()]);
        $container->shouldHaveType('EcomDev\Compiler\Statement\Container');
        $container->getIterator()->shouldMatchArray([$item->getWrappedObject()]);
    }

    function it_creates_array_list()
    {
        $statement = $this->arrayList();
        $statement->shouldHaveType('EcomDev\Compiler\Statement\ArrayList');
        $statement->compile(new Exporter())->shouldReturn('[]');
    }

    function it_creates_array_list_with_array_as_argument()
    {
        $statement = $this->arrayList([1, 2, 3]);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\ArrayList');
        $statement->compile(new Exporter())->shouldReturn("[\n    1,\n    2,\n    3\n]");
    }

    function it_creates_array_access_statement(StatementInterface $left)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$this->property');
        $statement = $this->arrayAccess($left, 'value');
        $statement->shouldHaveType('EcomDev\Compiler\Statement\ArrayAccess');
        $statement->compile($export)->shouldReturn('$this->property[\'value\']');
    }

    function it_creates_array_object_statement(StatementInterface $left)
    {
        $export = new Exporter();
        $left->compile($export)->willReturn('$this');
        $statement = $this->objectAccess($left, 'value');
        $statement->shouldHaveType('EcomDev\Compiler\Statement\ObjectAccess');
        $statement->compile($export)->shouldReturn('$this->value');
    }

    function it_creates_variable_statement(StatementInterface $left)
    {
        $statement = $this->variable('foo');
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Variable');
        $statement->compile(new Exporter())->shouldReturn('$foo');
    }

    function it_crates_closure_statement(StatementInterface $argument)
    {
        $export = new Exporter();
        $argument->compile($export)->willReturn('$argument');
        $statement = $this->closure([$argument]);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Closure');
        $statement->compile($export)->shouldReturn("function (\$argument) {\n\n}");
    }

    function it_creates_closure_and_allows_to_add_statement(StatementInterface $argument, StatementInterface $body)
    {
        $export = new Exporter();
        $argument->compile($export)->willReturn('$argument');
        $body->compile($export)->willReturn('return 0');
        $statement = $this->closure([$argument]);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Closure');
        $statement->add($body)->shouldReturn($statement);
        $statement->compile($export)->shouldReturn("function (\$argument) {\n    return 0;\n}");
    }

    function it_creates_closure_and_allows_to_specify_own_container(
        StatementInterface $argument,
        StatementInterface $body
    )
    {
        $export = new Exporter();
        $body->compile($export)->willReturn('return 0');
        $argument->compile($export)->willReturn('$argument');
        $container = new Container([$body->getWrappedObject()]);
        $statement = $this->closure([$argument], $container);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Closure');
        $statement->compile($export)->shouldReturn("function (\$argument) {\n    return 0;\n}");
    }

    function it_creates_new_instance_statement()
    {
        $statement = $this->instance('SplFileObject', [1, 2, 3]);
        $statement->shouldHaveType('EcomDev\Compiler\Statement\Instance');
        $statement->compile(new Exporter())->shouldReturn('new SplFileObject(1, 2, 3)');
    }

    function it_creates_new_chain_builder(StatementInterface $body)
    {
        $chain = $this->chain($body);
        $chain->shouldImplement('EcomDev\Compiler\Statement\Builder\Chain');
        $chain->end()->shouldReturn($body);
    }

    function it_creates_new_chain_builder_with_this_as_a_chain_starting_point()
    {
        $export = new Exporter();
        $chain = $this->this();
        $chain->shouldImplement('EcomDev\Compiler\Statement\Builder\Chain');
        $chain->callSomeMethod();
        $chain->end()->compile($export)->shouldReturn('$this->callSomeMethod()');
    }

    public function getMatchers()
    {
        return [
            'matchArray' => function ($subject, $array) {
                if ($subject instanceof \Traversable) {
                    $subject = iterator_to_array($subject);
                }

                return $subject == $array;
            }
        ];
    }
}
