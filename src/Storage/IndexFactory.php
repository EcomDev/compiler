<?php

namespace EcomDev\Compiler\Storage;

class IndexFactory
{
    /**
     * Reflection class
     *
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * IndexFactory constructor.
     *
     * @param null|string $className
     */
    public function __construct($className = 'EcomDev\Compiler\Storage\Index')
    {
        $this->reflection = new \ReflectionClass($className);

        $interface = 'EcomDev\Compiler\Storage\IndexInterface';

        if (!$this->reflection->implementsInterface($interface)) {
            throw new \InvalidArgumentException(sprintf('%s does not implement %s', $className, $interface));
        }
    }

    /**
     * Returns a new index instance
     *
     * @return IndexInterface
     */
    public function create()
    {
        return $this->reflection->newInstance();
    }
}
