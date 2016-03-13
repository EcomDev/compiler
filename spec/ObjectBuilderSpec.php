<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\ExportableInterface;
use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\Statement\Builder;
use EcomDev\Compiler\Statement\Call;
use EcomDev\Compiler\Statement\Instance;
use EcomDev\Compiler\Statement\ObjectAccess;
use EcomDev\Compiler\Statement\Variable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectBuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Builder());
    }

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

    function it_should_allow_shared_objects_as_arguments(
        ExportableInterface $exportable
    )
    {
        $sharedObject = new \stdClass();
        $this->share('shared_instance', $sharedObject);
        $exportable->export()->willReturn(['name1' => 'value1', 'name2' => 'value2', 'name3' => $sharedObject]);
        $this->build($exportable)->shouldBeLike(
            new Instance(get_class($exportable->getWrappedObject()), [
                'name1' => 'value1',
                'name2' => 'value2',
                'name3' => new Call(new ObjectAccess(new Variable('this'), 'shared'), ['shared_instance'])
            ])
        );
    }

    function it_should_be_possible_to_specify_shared_objects()
    {
        $sharedObject = new \stdClass();
        $this->share('shared_instance', $sharedObject)->shouldReturn($this);
        $this->shared('shared_instance')->shouldReturn($sharedObject);
    }

    function it_should_rise_an_exception_if_requested_shared_object_is_not_available()
    {
        $this
            ->shouldThrow(
                new \InvalidArgumentException(
                    'Unknown shared object "shared_instance" requested. '
                    . 'Consider calling share("shared_instance", $object) before interpreting compiled code.'
                )
            )
            ->duringShared('shared_instance');
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
