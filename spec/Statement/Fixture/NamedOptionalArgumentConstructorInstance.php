<?php

namespace spec\EcomDev\Compiler\Statement\Fixture;

/**
 * Dummy class for testing named arguments match
 *
 */
class NamedOptionalArgumentConstructorInstance
{
    public function __construct($name1, $name2 = 'value2', $name3 = 'value3')
    {

    }
}
