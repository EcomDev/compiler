<?php

namespace EcomDev\Compiler\Statement;

use EcomDev\Compiler\ExportInterface;
use EcomDev\Compiler\StatementInterface;

/**
 * Array list
 */
class ArrayList implements StatementInterface
{
    /**
     * List of items
     *
     * @var array
     */
    private $list;

    /**
     * Initializes the list
     *
     * @param array $list
     */
    public function __construct(array $list = [])
    {
        $this->list = $list;
    }


    /**
     * Adds an item to list
     *
     * @param  mixed $item
     * @return $this
     */
    public function add($item)
    {
        $this->list[] = $item;
        return $this;
    }


    /**
     * Exports array list
     *
     * @param  ExportInterface $export
     * @return string
     */
    public function compile(ExportInterface $export)
    {
        $itemPrefix = '';
        $itemWhitespace = ' ';
        $pattern = '[%s]';

        if (count($this->list) > 2) {
            $itemWhitespace = "\n";
            $itemPrefix = str_pad('', 4, ' ');
            $pattern = "[\n%s\n]";
        }

        $exportedList = [];

        foreach ($this->list as $item) {
            $exportedList[] = sprintf('%s%s', $itemPrefix, $export->export($item));
        }

        return sprintf($pattern, implode(sprintf(',%s', $itemWhitespace), $exportedList));
    }
}
