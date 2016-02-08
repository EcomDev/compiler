<?php

namespace EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\Storage\DriverInterface;

/**
 * Driver pool
 *
 */
interface PoolInterface extends DriverInterface
{
    /**
     * Adds a driver
     *
     * @param DriverInterface $driver
     * @param int $priority
     * @return $this
     */
    public function addDriver(DriverInterface $driver, $priority = 0);

    /**
     * Removes a driver
     *
     * @param DriverInterface $driver
     * @return $this
     */
    public function removeDriver(DriverInterface $driver);
}
