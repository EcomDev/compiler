<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Class instance statement
 */
class Instance implements StatementInterface
{
    /**
     * Class name for instance
     *
     * @var string
     */
    private $className;

    /**
     * Arguments for instance
     *
     * @var StatementInterface[]|mixed[]
     */
    private $arguments;

    /**
     * Instance statement constructor
     *
     * @param string                       $className
     * @param StatementInterface[]|mixed[] $arguments
     */
    public function __construct($className, array $arguments = [])
    {
        $this->className = $className;
        $this->arguments = $arguments;

        if (!is_int(key($this->arguments)) && $this->className instanceof StatementInterface) {
            throw new \InvalidArgumentException(
                'You cannot use named arguments together with statement based class name'
            );
        }
    }

    /**
     * Compiles statement
     *
     * @param ExporterInterface $export
     *
     * @return string
     */
    public function compile(ExporterInterface $export)
    {
        $className = $this->className;
        if ($className instanceof StatementInterface) {
            $className = $className->compile($export);
        }

        $arguments = [];

        foreach ($this->sortedArguments($this->arguments) as $argument) {
            $arguments[] = $export->export($argument);
        }

        return sprintf('new %s(%s)', $className, implode(', ', $arguments));
    }

    /**
     * Returns ordered list of arguments
     *
     * @param array $arguments
     *
     * @return mixed[]
     */
    private function sortedArguments(array $arguments)
    {
        if (!$arguments || is_int(key($arguments))) {
            return $arguments;
        }

        $sortedArguments = [];
        $reflection = new \ReflectionClass($this->className);
        $parameters = $reflection->getConstructor()->getParameters();

        foreach ($parameters as $parameter) {
            if (empty($arguments) && $parameter->isOptional()) {
                return $sortedArguments;
            }

            $name = $parameter->getName();

            $value = null;

            if ($parameter->isDefaultValueAvailable()) {
                $value = $parameter->getDefaultValue();
            }

            if (isset($arguments[$name])) {
                $value = $arguments[$name];
                unset($arguments[$name]);
            }

            $sortedArguments[] = $value;
        }

        return $sortedArguments;
    }
}
