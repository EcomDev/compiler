<?php

namespace EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\Storage\ReferenceInterface;

interface IndexInterface extends \Serializable, \Countable
{
    /**
     * Adds reference by identifier
     *
     * @param  ReferenceInterface $interface
     * @return $this
     */
    public function add(ReferenceInterface $interface);

    /**
     * Checks if reference with such id exists in index
     *
     * @param  $id
     * @return boolean
     */
    public function has($id);

    /**
     * Returns a reference by identifier
     *
     * @param  $id
     * @return ReferenceInterface
     */
    public function get($id);
}
