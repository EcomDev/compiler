<?php

namespace EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\Storage\ReferenceInterface;

class Index implements IndexInterface
{
    /**
     * Stored references
     *
     * @var ReferenceInterface[]
     */
    private $data = [];

    public function add(ReferenceInterface $reference)
    {
        $this->data[$reference->getId()] = $reference;
        return $this;
    }

    public function has($id)
    {
        return isset($this->data[$id]);
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \InvalidArgumentException(
                sprintf('Reference with identifier "%s" does not exists in current index', $id)
            );
        }

        return $this->data[$id];
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }

    public function count()
    {
        return count($this->data);
    }
}
