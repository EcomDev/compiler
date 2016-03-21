<?php

namespace EcomDev\Compiler;

use EcomDev\Compiler\Statement\Instance;
use PDepend\Source\Builder\Builder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Default object builder implementation for storage load/save process
 */
class ObjectBuilder implements ObjectBuilderInterface
{
    /**
     * Shared objects
     *
     * @var mixed[]
     */
    private $shared = [];

    /**
     * Relation of shared object to spl hash
     *
     * @var string[]
     */
    private $sharedIdToSplHash;

    /**
     * Statements builder
     *
     * @var Builder
     */
    private $builder;

    /**
     * Accepts builder as statement factory
     */
    public function __construct(Statement\Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Builds statement based on exportable object
     *
     * @param ExportableInterface $exportable
     *
     * @return Instance
     */
    public function build(ExportableInterface $exportable)
    {
        $arguments = $this->resolveArray($exportable->export());
        return new Instance(get_class($exportable), $arguments);
    }

    /**
     * Binds Closure to itself
     *
     * @param \Closure $closure
     *
     * @return \Closure
     */
    public function bind(\Closure $closure)
    {
        return \Closure::bind($closure, $this, get_class($this));
    }

    /**
     * Returns array for possible exportable objects
     *
     * @param array $array
     *
     * @return mixed[]
     */
    protected function resolveArray(array $array)
    {
        foreach ($array as $index => $item) {
            if ($this->sharedIdToSplHash
                && is_object($item)
                && isset($this->sharedIdToSplHash[spl_object_hash($item)])) {
                $id = $this->sharedIdToSplHash[spl_object_hash($item)];
                $array[$index] = $this->builder->this()->shared($id)->end();
            }

            if ($item instanceof ExportableInterface) {
                $array[$index] = $this->build($item);
            }

            if (is_array($item)) {
                $array[$index] = $this->resolveArray($item);
            }
        }

        return $array;
    }

    /**
     * Shares an object into builder
     *
     * @param string $id
     * @param mixed $object
     *
     * @return $this
     */
    public function share($id, $object)
    {
        $this->shared[$id] = $object;
        $this->sharedIdToSplHash[spl_object_hash($object)] = $id;
        return $this;
    }

    /**
     * Returns a shared object
     *
     * @param string $id
     *
     * @return mixed
     */
    public function shared($id)
    {
        if (!isset($this->shared[$id])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown shared object "%1$s" requested. '
                . 'Consider calling share("%1$s", $object) before interpreting compiled code.',
                $id
            ));
        }

        return $this->shared[$id];
    }
}
