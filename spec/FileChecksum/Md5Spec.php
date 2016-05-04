<?php

namespace spec\EcomDev\Compiler\Checksum;

use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Md5Spec extends ObjectBehavior
{
    function it_should_implement_checksum_interface()
    {
        $this->shouldImplement('EcomDev\Compiler\FileChecksumInterface');
    }
    function it_should_calculate_checksum_based_on_file_modification_and_size()
    {
        $directory = vfsStream::setup('root', null, [
            'file1.txt' => 'aaaa',
            'file2.txt' => 'bbbbbbbbb',
        ]);

        $this->calculate($directory->getChild('file1.txt')->url())
            ->shouldReturn('74b87337454200d4d33f80c4663dc5e5');

        $this->calculate($directory->getChild('file2.txt')->url())
            ->shouldReturn('57f365f09200a0ee7c1243d545447cb1');
    }

    function it_should_throw_an_exception_if_file_does_not_exists()
    {
        $this
            ->shouldThrow(
                new \InvalidArgumentException(
                    'Invalid argument supplied for checksum calculation, only existing files are allowed'
                )
            )
            ->duringCalculate('lets_consider_this_file_name_random_and_it_does_not_exists.txt');
    }
}
