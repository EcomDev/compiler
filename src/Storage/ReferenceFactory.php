<?php

namespace EcomDev\Compiler\Storage;

use EcomDev\Compiler\Statement\SourceInterface;

class ReferenceFactory
{
    /**
     * Reflection object of the passed class name
     *
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * Reference factory constructor
     *
     * @param string $className
     */
    public function __construct($className = 'EcomDev\Compiler\Storage\Reference')
    {
        $this->reflection = new \ReflectionClass($className);
        $interface = 'EcomDev\Compiler\Storage\ReferenceInterface';
        if (!$this->reflection->implementsInterface($interface)) {
            throw new \InvalidArgumentException(sprintf('%s does not implement %s', $className, $interface));
        }
    }

    /**
     * Creates a new reference based source objec
     *
     * @param SourceInterface $source
     *
     * @return ReferenceInterface
     */
    public function create(SourceInterface $source)
    {
        return $this->reflection->newInstance($source->getId(), $source->getChecksum(), $source);
    }
}
