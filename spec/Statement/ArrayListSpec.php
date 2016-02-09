<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayListSpec extends ObjectBehavior
{
    function it_is_possible_to_add_array_items(ExportInterface $export)
    {
        $this->add('item1')->shouldReturn($this);
        $this->add('item2')->shouldReturn($this);

        $export->export('item1')->willReturn("'item1'");
        $export->export('item2')->willReturn("'item2'");

        $this->compile($export)->shouldReturn("['item1', 'item2']");
    }

    function it_exports_array_multiple_line_if_more_than_two_items_are_added(ExportInterface $export)
    {
        $this->add('item1')->shouldReturn($this);
        $this->add('item2')->shouldReturn($this);
        $this->add('item3')->shouldReturn($this);

        $export->export('item1')->willReturn("'item1'");
        $export->export('item2')->willReturn("'item2'");
        $export->export('item3')->willReturn("'item3'");

        $this->compile($export)->shouldReturn("[\n    'item1',\n    'item2',\n    'item3'\n]");
    }

    function it_takes_argument_as_initial_list(ExportInterface $export)
    {
        $this->beConstructedWith(['item1', 'item2']);
        $this->add('item3')->shouldReturn($this);
        $export->export('item1')->willReturn("'item1'");
        $export->export('item2')->willReturn("'item2'");
        $export->export('item3')->willReturn("'item3'");
        $this->compile($export)->shouldReturn("[\n    'item1',\n    'item2',\n    'item3'\n]");
    }
}
