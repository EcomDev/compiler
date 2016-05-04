<?php

namespace spec\EcomDev\Compiler\Source;

use EcomDev\Compiler\ChecksumInterface;
use EcomDev\Compiler\FileChecksumInterface;
use EcomDev\Compiler\ParserInterface;
use EcomDev\Compiler\Statement\ContainerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var FileChecksumInterface
     */
    private $fileChecksum;

    /**
     * Root vfs directory
     *
     * @var vfsStreamDirectory
     */
    private $vfs;

    function let(ParserInterface $parser, FileChecksumInterface $fileChecksum)
    {
        $this->parser = $parser;
        $this->fileChecksum = $fileChecksum;

        $this->vfs = vfsStream::setup('root', null, [
            'dummy_file1.txt' => 'some_file_content1'
        ]);

        $this->beConstructedWith($parser, $this->vfs->url() . '/dummy_file1.txt', $this->fileChecksum);
    }

    function it_should_implement_source_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\SourceInterface');
    }

    function it_should_return_checksum_from_checksum_model()
    {
        $this->fileChecksum->calculate($this->vfs->getChild('dummy_file1.txt')->url())
            ->shouldBeCalled()
            ->willReturn('this_is_checksum');

        $this->getChecksum()->shouldReturn('this_is_checksum');
    }

    function it_should_use_base_file_name_as_identifier()
    {
        $this->getId()->shouldReturn('dummy_file1_5fd908b1ad49099ee5d639589c95f7a2');
    }

    function it_allows_specify_checksum_manually()
    {
        $this->beConstructedWith($this->parser, $this->vfs->url() . '/dummy_file1.txt', 'checksum1');
        $this->getChecksum()->shouldReturn('checksum1');
    }

    function it_returns_constructed_arguments_with_generated_checksum_for_export()
    {
        $file = $this->vfs->getChild('dummy_file1.txt')->url();
        $this->fileChecksum->calculate($file)->willReturn('our_calculated_checksum');
        $this->export()->shouldReturn([
            'parser' => $this->parser->getWrappedObject(),
            'file' => $file,
            'checksum' => 'our_calculated_checksum'
        ]);
    }

    function it_calls_parser_on_loading_the_data(ContainerInterface $container)
    {
        $this->parser->parse('some_file_content1')->shouldBeCalled()->willReturn($container);
        $this->load()->shouldReturn($container);
    }
}
