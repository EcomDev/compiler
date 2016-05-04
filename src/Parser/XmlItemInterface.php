<?php

namespace EcomDev\Compiler\Parser;

use EcomDev\Compiler\StatementInterface;

/**
 * XML node parser interface
 *
 * `match()` method is used for validating if node is applicable for this parser
 */
interface XmlItemInterface
{
    /**
     * Matches xml node for parser
     *
     * @param \SimpleXMLElement $element
     *
     * @return bool
     */
    public function match(\SimpleXMLElement $element);

    /**
     * Parses xml node into PHP statement or list of PHP statements
     *
     * `$parser` is used for internal structure processing if any needed
     *
     * @param \SimpleXMLElement $element
     * @param \Closure $parser
     *
     * @return StatementInterface[]|StatementInterface|bool
     */
    public function parse(\SimpleXMLElement $element, \Closure $parser);
}
