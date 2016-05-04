<?php

namespace EcomDev\Compiler\Source;

use EcomDev\Compiler\FileChecksumInterface;
use EcomDev\Compiler\ParserInterface;
use EcomDev\Compiler\Statement;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * File source implementation
 */
class File extends AbstractSource
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
    private $file;

    /**
     * Configures source with provided arguments
     *
     * @param ParserInterface $parser
     * @param string $file
     * @param string|FileChecksumInterface $checksum
     */
    public function __construct(ParserInterface $parser, $file, $checksum)
    {
        $id = $this->generateId($file);
        parent::__construct($id, $checksum);
        $this->parser = $parser;
        $this->file = $file;
    }

    /**
     * Generates identifier
     *
     * @param string $file
     *
     * @return string
     */
    private function generateId($file)
    {
        $id = basename($file);
        if (($dot = strrpos($id, '.')) !== false) {
            $id = substr($id, 0, $dot);
        }
        $id .= '_' . md5($file);
        return $id;
    }

    /**
     * Exports arguments for constructor
     *
     * @return mixed[]
     */
    public function export()
    {
        return [
            'parser' => $this->parser,
            'file' => $this->file,
            'checksum' => $this->getChecksum()
        ];
    }

    public function getChecksum()
    {
        $checksum = parent::getChecksum();

        if ($checksum instanceof FileChecksumInterface) {
            return $checksum->calculate($this->file);
        }

        return $checksum;
    }


    /**
     * Loads a file via parser
     *
     * @return ContainerInterface
     */
    public function load()
    {
        return $this->parser->parse(file_get_contents($this->file));
    }
}
