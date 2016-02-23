<?php

namespace EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\Statement\Instance;
use EcomDev\Compiler\Storage\ReferenceInterface;

class Index implements IndexInterface
{
    /**
     * Stored references
     *
     * @var ReferenceInterface[]
     */
    private $data;

    /**
     * Constructs an index with data
     *
     * @param ReferenceInterface[] $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

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

    public function export()
    {
        return new Instance(get_class($this), [$this->data]);
    }

    public function count()
    {
        return count($this->data);
    }
}
