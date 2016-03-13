<?php

namespace spec\EcomDev\Compiler;

use EcomDev\Compiler\SourceInterface;
use EcomDev\Compiler\Storage\ReferenceInterface;
use EcomDev\Compiler\StorageInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompilerSpec extends ObjectBehavior
{
    function let(StorageInterface $storage)
    {
        $this->beConstructedWith($storage);
    }

    function it_flushes_storage_on_flush(StorageInterface $storage)
    {
        $storage->flush()
            ->shouldBeCalled();

        $this->flush()
            ->shouldEqual($this);
    }

    function it_recompiles_source_if_it_is_not_compiled(
        StorageInterface $storage,
        SourceInterface $source,
        ReferenceInterface $reference
    )
    {
        $storage->find($source)->willReturn(false);
        $storage->store($source)->willReturn($reference);

        $this->compile($source)->shouldReturn($reference);
    }

    function it_does_not_compile_if_reference_exists_for_source(
        StorageInterface $storage,
        SourceInterface $source,
        ReferenceInterface $reference
    )
    {
        $storage->find($source)
            ->willReturn($reference);

        $this->compile($source)->shouldReturn($reference);
    }

    function it_recompiles_source_if_checksum_is_different(
        StorageInterface $storage,
        SourceInterface $source,
        ReferenceInterface $reference,
        ReferenceInterface $newReference
    )
    {
        $reference->getChecksum()->willReturn('123');
        $source->getChecksum()->willReturn('111');

        $storage->find($source)
            ->willReturn($reference);

        $storage->store($source)
            ->willReturn($newReference);

        $this->compile($source)->shouldReturn($newReference);
    }

    public function it_interprets_reference_via_storage(
        StorageInterface $storage,
        ReferenceInterface $reference
    )
    {
        $storage->interpret($reference)->willReturn(['1', '2', '3']);
        $this->interpret($reference)->shouldReturn(['1', '2', '3']);
    }
}
