<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InstanceSpec extends ObjectBehavior
{
    function it_generates_a_new_class(ExportInterface $export)
    {
        $export->export(1)->willReturn('1');
        $export->export(2)->willReturn('2');
        $export->export(3)->willReturn('3');
        $this->beConstructedWith('SplFileObject', [1, 2, 3]);
        $this->compile($export)->shouldReturn('new SplFileObject(1, 2, 3)');
    }

    function it_generates_a_new_class_from_another_statement(ExportInterface $export, StatementInterface $item)
    {
        $export->export(1)->willReturn('1');
        $export->export(2)->willReturn('2');
        $export->export(3)->willReturn('3');
        $item->compile($export)->willReturn('$className');
        $this->beConstructedWith($item, [1, 2, 3]);
        $this->compile($export)->shouldReturn('new $className(1, 2, 3)');
    }
}
