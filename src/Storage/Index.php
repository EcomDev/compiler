<?php

namespace EcomDev\Compiler\Storage;


/**
 * Index Implementation
 *
 * Used by driver to store information about references and their locations
 * As it gives information about its state (if changed), we don't need to perform redundant IO on file chunks.
 */
class Index implements IndexInterface
{
    /**
     * Stored references
     *
     * @var ReferenceInterface[]
     */
    private $data;

    /**
     * Is changed flag
     *
     * @var bool
     */
    private $isChanged;

    /**
     * Constructs an index with data
     *
     * @param ReferenceInterface[] $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->isChanged = false;
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
        $this->isChanged = true;
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
     * Removes reference by identifier from index
     *
     * @param string $id
     *
     * @return $this
     */
    public function remove($id)
    {
        unset($this->data[$id]);
        $this->isChanged = true;
        return $this;
    }

    /**
     * Returns all added reference identifiers to the index
     *
     * @return string[]
     */
    public function inspect()
    {
        return array_keys($this->data);
    }


    /**
     * Exports class constructor arguments
     *
     * @return array
     */
    public function export()
    {
        return ['data' => $this->data];
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

    /**
     * Returns true if any modification has been done to index
     *
     * @return bool
     */
    public function isChanged()
    {
        return $this->isChanged;
    }
}
