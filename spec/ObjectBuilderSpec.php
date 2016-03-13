<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\ExportableInterface;
use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\Statement\Instance;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectBuilderSpec extends ObjectBehavior
{
    function it_should_implement_object_builder_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\ObjectBuilderInterface');
    }

    function it_should_build_an_exportable_object_into_named_instance_statement(
        ExportableInterface $exportable
    )
    {
        $exportable->export()->willReturn(['name1' => 'value1', 'name2' => 'value2']);
        $this->build($exportable)->shouldBeLike(
            new Instance(get_class($exportable->getWrappedObject()), ['name1' => 'value1', 'name2' => 'value2'])
        );
    }

    function it_should_resolve_exportables_in_argument_list(
        ExportableInterface $exportableMain,
        ExportableInterface $exportableChild,
        ExportableInterface $exportableChildInArray
    )
    {
        $exportableChildInArray->export()->willReturn([]);
        $exportableChild->export()->willReturn([
            'name1' => 'value1',
            'value' => ['data_key' => $exportableChildInArray->getWrappedObject()]
        ]);

        $exportableMain->export()->willReturn(['name1' => 'value1', 'child' => $exportableChild->getWrappedObject()]);
        $this->build($exportableMain)->shouldBeLike(
            new Instance(
                get_class($exportableMain->getWrappedObject()),
                [
                    'name1' => 'value1',
                    'child' => new Instance(
                        get_class($exportableChild->getWrappedObject()),
                        [
                            'name1' => 'value1',
                            'value' => [
                                'data_key' => new Instance(get_class($exportableChildInArray->getWrappedObject()))
                            ]
                        ]
                    )
                ]
            )
        );
    }

    function it_should_attach_itself_to_closure()
    {
        $boundClosure = $this->bind(function () {
            return get_class($this);
        });

        $boundClosure->shouldImplement('Closure');
        $boundClosure->shouldHaveClosureReturnValue('EcomDev\Compiler\ObjectBuilder');
    }

    public function getMatchers()
    {
        return [
            'haveClosureReturnValue' => function ($closure, $expectedReturnValue) {
                return $closure() == $expectedReturnValue;
            }
        ];
    }
}
