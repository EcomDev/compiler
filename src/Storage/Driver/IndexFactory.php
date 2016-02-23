<?php

namespace EcomDev\Compiler\Storage\Driver;

class IndexFactory
{
    /**
     * Returns a new index instance
     *
     * @return IndexInterface
     */
    public function create()
    {
        return new Index();
    }
}
