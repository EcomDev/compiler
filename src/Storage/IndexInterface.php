<?php

namespace EcomDev\Compiler\Storage;

use EcomDev\Compiler\ExportableInterface;

interface IndexInterface extends ExportableInterface, \Countable
{
    /**
     * Adds reference by identifier
     *
     * @param ReferenceInterface $interface
     *
     * @return $this
     */
    public function add(ReferenceInterface $interface);

    /**
     * Checks if reference with such id exists in index
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id);

    /**
     * Returns a reference by identifier
     *
     * @param string $id
     *
     * @return ReferenceInterface
     */
    public function get($id);

    /**
     * Removes a reference by identifier
     *
     * @param string $id
     *
     * @return $this
     */
    public function remove($id);

    /**
     * Returns all stored reference ids in index
     *
     * @return string[]
     */
    public function inspect();

    /**
     * Check if index has been changed
     *
     * @return bool
     */
    public function isChanged();
}
