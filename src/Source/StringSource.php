<?php

namespace EcomDev\Compiler\Source;

use EcomDev\Compiler\ParserInterface;
use EcomDev\Compiler\Statement;

class StringSource extends AbstractSource
{
    /**
     * Parser for content
     *
     * @var ParserInterface
     */
    private $parser;

    /**
     * File to load
     *
     * @var string
     */
    private $string;

    /**
     * Configures string source
     *
     * @param ParserInterface $parser
     * @param string $string
     * @param string $id
     */
    public function __construct(ParserInterface $parser, $string, $id = null)
    {
        $checksum = md5($string);

        if ($id === null) {
            $id = 'inline_string_' . $checksum;
        }

        parent::__construct($id, $checksum);
        $this->parser = $parser;
        $this->string = $string;
    }

    public function export()
    {
        return [
            'parser' => $this->parser,
            'string' => $this->string,
            'id' => $this->getId()
        ];
    }

    public function load()
    {
        return $this->parser->parse($this->string);
    }

}
