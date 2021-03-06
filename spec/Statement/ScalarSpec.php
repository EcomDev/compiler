<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScalarSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('dummy');
    }

    function it_implements_statement_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\StatementInterface');
    }

    function it_uses_export_model_to_export_a_value(ExporterInterface $export)
    {
        $export->export('dummy')->willReturn("'dummy'");
        $this->compile($export)->shouldBe("'dummy'");
    }
}
