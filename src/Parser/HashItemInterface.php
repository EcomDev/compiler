<?php

namespace EcomDev\Compiler\Parser\Item;

use EcomDev\Compiler\StatementInterface;

/**
 * Hash (array) node parser interface
 *
 * `match()` method is used for validating if array item is applicable for this parser
 */
interface HashItemInterface
{
    /**
     * Matches hash item for parser
     *
     * @param int|string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function match($key, $value);
    
    /**
     * Parses hash item into PHP statement or list of PHP statements
     *
     * `$parser` is used for internal structure processing if any needed
     *
     * @param int|string $key
     * @param mixed $value
     * @param \Closure $parser
     *
     * @return StatementInterface[]|StatementInterface|bool
     */
    public function parse($key, $value, \Closure $parser);
}
