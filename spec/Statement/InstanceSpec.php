<?php

namespace spec\EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InstanceSpec extends ObjectBehavior
{
    /**
     * @param ExporterInterface $export
     * @param array $values
     */
    private function stubExport($export, array $values)
    {
        foreach ($values as $value) {
            $export->export($value)->willReturn(var_export($value, true));
        }
    }

    function it_generates_a_new_class(ExporterInterface $export)
    {
        $this->stubExport($export, [1, 2, 3]);
        $this->beConstructedWith('SplFileObject', [1, 2, 3]);
        $this->compile($export)->shouldReturn('new SplFileObject(1, 2, 3)');
        $this->shouldImplement('EcomDev\Compiler\StatementInterface');
    }

    function it_generates_a_new_class_from_another_statement(ExporterInterface $export, StatementInterface $item)
    {
        $this->stubExport($export, [1, 2, 3]);
        $item->compile($export)->willReturn('$className');
        $this->beConstructedWith($item, [1, 2, 3]);
        $this->compile($export)->shouldReturn('new $className(1, 2, 3)');
    }

    function it_should_resolve_named_arguments(ExporterInterface $export)
    {
        $this->stubExport($export, ['value1', 'value2', 'value3']);
        $this->beConstructedWith(
            __NAMESPACE__ . '\Fixture\NamedArgumentConstructorInstance',
            ['name3' => 'value3', 'name1' => 'value1', 'name2' => 'value2']
        );

        $this->compile($export)->shouldReturn(sprintf(
            'new %s(%s, %s, %s)',
            __NAMESPACE__ . '\Fixture\NamedArgumentConstructorInstance',
            var_export('value1', true),
            var_export('value2', true),
            var_export('value3', true)
        ));
    }

    function it_should_resolve_named_arguments_and_fill_required_values_with_null(ExporterInterface $export)
    {
        $this->stubExport($export, ['value1', null, 'value3']);
        $this->beConstructedWith(
            __NAMESPACE__ . '\Fixture\NamedArgumentConstructorInstance',
            ['name3' => 'value3', 'name1' => 'value1']
        );

        $this->compile($export)->shouldReturn(sprintf(
            'new %s(%s, %s, %s)',
            __NAMESPACE__ . '\Fixture\NamedArgumentConstructorInstance',
            var_export('value1', true),
            var_export(null, true), // Missing argument
            var_export('value3', true)
        ));
    }

    function it_should_ommit_optional_arguments_if_value_for_it_is_not_specified(ExporterInterface $export)
    {
        $this->stubExport($export, ['value1']);
        $this->beConstructedWith(
            __NAMESPACE__ . '\Fixture\NamedOptionalArgumentConstructorInstance',
            ['name1' => 'value1']
        );

        $this->compile($export)->shouldReturn(sprintf(
            'new %s(%s)',
            __NAMESPACE__ . '\Fixture\NamedOptionalArgumentConstructorInstance',
            var_export('value1', true)
        ));
    }

    function it_should_fills_default_argument_value_if_it_is_available(ExporterInterface $export)
    {
        $this->stubExport($export, ['value1', 'value2', 'value_non_default']);
        $this->beConstructedWith(
            __NAMESPACE__ . '\Fixture\NamedOptionalArgumentConstructorInstance',
            ['name3' => 'value_non_default', 'name1' => 'value1']
        );

        $this->compile($export)->shouldReturn(sprintf(
            'new %s(%s, %s, %s)',
            __NAMESPACE__ . '\Fixture\NamedOptionalArgumentConstructorInstance',
            var_export('value1', true),
            var_export('value2', true),
            var_export('value_non_default', true)
        ));
    }



    function it_should_throw_invalid_argument_exception_if_named_arguments_are_combined_with_statement_as_class_name(
        StatementInterface $item
    )
    {
        $this->beConstructedWith($item, ['name1' => 'value1', 'name2' => 'value2', 'name3' => 'value3']);
        $this->shouldThrow(
            new \InvalidArgumentException('You cannot use named arguments together with statement based class name')
        )->duringInstantiation();
    }
}

