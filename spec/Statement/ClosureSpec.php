<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\ContainerInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClosureSpec extends ObjectBehavior
{
    function it_renders_closure_with_container(
        ExportInterface $export,
        StatementInterface $argument1,
        StatementInterface $body1,
        StatementInterface $body2,
        ContainerInterface $container
    )
    {
        $argument1->compile($export)->willReturn('$argument1');
        $body1->compile($export)->willReturn('echo $argument1');
        $body2->compile($export)->willReturn('return $argument1');

        $container->getIterator()
            ->willReturn(new \ArrayIterator([$body1->getWrappedObject(), $body2->getWrappedObject()]));

        $this->beConstructedWith([$argument1], $container);
        $this->compile($export)
            ->shouldReturn("function (\$argument1) {\n    echo \$argument1;\n    return \$argument1;\n}");
    }

    function it_renders_closure_with_container_without_arguments(
        ExportInterface $export,
        StatementInterface $body,
        ContainerInterface $container
    )
    {
        $body->compile($export)->willReturn('return $argument1');

        $container->getIterator()->willReturn(new \ArrayIterator([$body->getWrappedObject()]));

        $this->beConstructedWith([], $container);
        $this->compile($export)
            ->shouldReturn("function () {\n    return \$argument1;\n}");
    }


    function it_throws_an_exception_if_argument_is_not_a_statement()
    {
        $this->beConstructedWith(['argument1'], new Container());
        $message = 'Argument #0 does not implement EcomDev\Compiler\StatementInterface';
        $this->shouldThrow(new \InvalidArgumentException($message))->duringInstantiation();
    }

    function it_allows_adding_items_to_container(StatementInterface $body,
                                                 ContainerInterface $container)
    {
        $container->add($body)->willReturn($container);
        $this->beConstructedWith([], $container);
        $this->add($body)->shouldReturn($this);
    }
}
