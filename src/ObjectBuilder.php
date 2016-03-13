<?php

namespace EcomDev\Compiler;

use EcomDev\Compiler\Statement\Instance;

class ObjectBuilder implements ObjectBuilderInterface
{
    /**
     * Builds statement based on exportable object
     *
     * @param ExportableInterface $exportable
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
     * @return mixed[]
     */
    protected function resolveArray(array $array)
    {
        foreach ($array as $index => $item) {
            if ($item instanceof ExportableInterface) {
                $array[$index] = $this->build($item);
            }

            if (is_array($item)) {
                $array[$index] = $this->resolveArray($item);
            }
        }

        return $array;
    }
}
