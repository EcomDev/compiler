<?php

namespace spec\EcomDev\Compiler\Storage\Driver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexFactorySpec extends ObjectBehavior
{
    function it_creates_a_new_index_instance()
    {
        $indexOne = $this->create();
        $indexTwo = $this->create();
        $indexOne->shouldImplement('EcomDev\Compiler\Storage\Driver\Index');
        $indexTwo->shouldImplement('EcomDev\Compiler\Storage\Driver\Index');
        $indexOne->shouldNotEqual($indexTwo);
    }
}
