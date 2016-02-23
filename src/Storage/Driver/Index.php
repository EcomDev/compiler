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

    /**
     * Adds new reference to index
     *
     * @param ReferenceInterface $reference
     *
     * @return $this
     */
    public function add(ReferenceInterface $reference)
    {
        $this->data[$reference->getId()] = $reference;
        return $this;
    }

    /**
     * Checks existence of reference with specified identifier in index
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->data[$id]);
    }

    /**
     * Returns reference by identifier
     *
     * @param string $id
     *
     * @return ReferenceInterface
     * @throws \InvalidArgumentException
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \InvalidArgumentException(
                sprintf('Reference with identifier "%s" does not exists in current index', $id)
            );
        }

        return $this->data[$id];
    }

    /**
     * Exports index as compilable value
     *
     * @return Instance
     */
    public function export()
    {
        return new Instance(get_class($this), [$this->data]);
    }

    /**
     * Return number of records in index
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
}
