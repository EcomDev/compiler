<?php

namespace spec\EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\DispersionInterface;
use EcomDev\Compiler\Exporter;
use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\ObjectBuilder;
use EcomDev\Compiler\ObjectBuilderInterface;
use EcomDev\Compiler\Statement\Container;
use EcomDev\Compiler\Statement\Instance;
use EcomDev\Compiler\Statement\ReturnStatement;
use EcomDev\Compiler\Statement\Scalar;
use EcomDev\Compiler\Source\StaticData;
use EcomDev\Compiler\Storage\Driver\File;
use EcomDev\Compiler\Storage\Driver\Index;
use EcomDev\Compiler\Storage\Driver\IndexFactory;
use EcomDev\Compiler\Storage\ReferenceFactory;
use org\bovigo\vfs\vfsStreamDirectory;
use PhpSpec\Exception\Example\MatcherException;
use PhpSpec\Exception\Example\NotEqualException;
use Symfony\Component\Filesystem\Filesystem;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamAbstractContent;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\WrapperInterface;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    /**
     * VFS stream directory
     *
     * @var vfsStreamDirectory
     */
    private $vfs;

    /**
     * Index repository
     *
     * @var IndexFactory
     */
    private $indexFactory;

    /**
     * Index reference
     *
     * @var ReferenceFactory
     */
    private $referenceFactory;

    /**
     * Dispersion model
     *
     * @var DispersionInterface|WrapperInterface
     */
    private $dispersion;

    /**
     * Exporter instance
     *
     * @var Exporter
     */
    private $export;

    /**
     * Instance of object builder
     *
     * @var ObjectBuilder
     */
    private $objectBuilder;

    function let(DispersionInterface $dispersion)
    {
        $this->indexFactory = new IndexFactory();
        $this->referenceFactory = new ReferenceFactory();
        $this->dispersion = $dispersion;
        $this->export = new Exporter();
        $this->objectBuilder = new ObjectBuilder();

        $this->vfs = $this->setupFileSystem();

        $this->beConstructedWith(
            $this->vfs->url() . '/directory',
            $this->dispersion,
            $this->indexFactory,
            $this->referenceFactory,
            $this->export,
            $this->objectBuilder
        );

        $this->dispersion->calculate(Argument::any())->will(function ($args) {
            if ($args[0] === 'identifier3') {
                return 'custom';
            }

            return 'default';
        });
    }

    function it_creates_directory_if_it_is_unavailable()
    {
        $this->vfs->removeChild('directory'); // Directory is removed prior instantiation
        $this->shouldCreateDirectory($this->vfs->url() . '/directory');
        $this->shouldHavePermissions('directory', 0750);
    }

    function it_bind_a_closure_to_object_builder(ObjectBuilderInterface $builder)
    {
        $this->beConstructedWith(
            $this->vfs->url() . '/directory',
            $this->dispersion,
            $this->indexFactory,
            $this->referenceFactory,
            $this->export,
            $builder
        );

        $builder->bind(Argument::type('Closure'))->shouldBeCalled()->willReturn(function () {});
        $this->shouldImplement('EcomDev\Compiler\Storage\DriverInterface');
    }

    function it_allows_to_specify_permission_for_created_directory()
    {
        $this->beConstructedWith(
            $this->vfs->url() . '/directory',
            $this->dispersion,
            $this->indexFactory,
            $this->referenceFactory,
            $this->export,
            $this->objectBuilder,
            [File::PERMISSION_DIRECTORY => 0777]
        );

        $this->vfs->removeChild('directory'); // Directory is removed prior instantiation
        $this->shouldCreateDirectory($this->vfs->url() . '/directory');
        $this->shouldHavePermissions('directory', 0777);
    }

    function it_return_references_from_index_based_on_dispersion_of_id()
    {
        $referenceOne = $this->findById('identifier1');
        $referenceOne->shouldImplement('EcomDev\Compiler\Storage\ReferenceInterface');
        $referenceOne->getId()->shouldReturn('identifier1');

        $referenceThree = $this->findById('identifier3');
        $referenceThree->shouldImplement('EcomDev\Compiler\Storage\ReferenceInterface');
        $referenceThree->getId()->shouldReturn('identifier3');

        $this->findById('unknown')
            ->shouldReturn(false);
    }

    function it_return_references_from_indexe_by_source_object_and_it_should_be_identical_to_findById()
    {
        $referenceOne = $this->find($this->newSource('identifier1'));
        $referenceOne->shouldImplement('EcomDev\Compiler\Storage\ReferenceInterface');
        $referenceOne->getId()->shouldReturn('identifier1');

        $this->findById('identifier1')->shouldReturn($referenceOne);
    }

    function it_returns_compiled_file_code()
    {
        $referenceOne = $this->findById('identifier1');
        $this->get($referenceOne)->shouldReturn('<?php '
            . PHP_EOL . 'return function ($item, $item2) {'
            . PHP_EOL . '  return spritnf("%s-%s", $item, $item2); '
            . PHP_EOL . '};');
    }

    function it_stores_new_source()
    {
        $source = $this->newSource('identifier6', 'checksum6', [new ReturnStatement(true)]);
        $referenceSix = $this->store($source);
        $this->find($source)->shouldReturn($referenceSix);
        $storageFilePath = 'directory/default/identifier6.php';
        $this->shouldHaveFileContent($this->vfs->url() . '/' . $storageFilePath, '<?php ' . PHP_EOL . 'return true;');
        $this->shouldHavePermissions($storageFilePath, 0640);
    }

    function it_stores_new_source_with_custom_permissions()
    {
        $this->beConstructedWith(
            $this->vfs->url() . '/directory',
            $this->dispersion,
            $this->indexFactory,
            $this->referenceFactory,
            $this->export,
            $this->objectBuilder,
            [File::PERMISSION_FILE => 0666]
        );

        $source = $this->newSource('identifier6', 'checksum6', [new ReturnStatement(true)]);
        $referenceSix = $this->store($source);
        $this->find($source)->shouldReturn($referenceSix);
        $storageFilePath = 'directory/default/identifier6.php';
        $this->shouldHaveFileContent($this->vfs->url() . '/' . $storageFilePath, '<?php ' . PHP_EOL . 'return true;');
        $this->shouldHavePermissions($storageFilePath, 0666);
    }

    function it_does_not_store_new_version_of_source_if_checksum_is_the_same()
    {
        $reference = $this->findById('identifier1');
        $originalFileContent = $this->get($reference);

        $source = $this->newSource('identifier1', 'checksum1', [new ReturnStatement(true)]);

        $this->store($source)->shouldReturn($reference);
        $storageFilePath = $this->vfs->url() . '/directory/default/identifier1.php';

        $this->shouldHaveFileContent($storageFilePath, $originalFileContent);
    }

    function it_stores_new_version_of_source_if_checksum_is_different()
    {
        $reference = $this->findById('identifier1');

        $source = $this->newSource('identifier1', 'checksum2', [new ReturnStatement(true)]);
        $newReference = $this->store($source);
        $newReference->shouldImplement('EcomDev\Compiler\Storage\ReferenceInterface');
        $newReference->shouldNotBe($reference);

        $this->find($source)->shouldReturn($newReference);

        $storageFilePath = 'directory/default/identifier1.php';
        $this->shouldHaveFileContent($this->vfs->url() . '/' .  $storageFilePath, '<?php ' . PHP_EOL . 'return true;');
        $this->shouldHavePermissions($storageFilePath, 0640);
    }

    function it_stores_reference_source_if_original_was_lost_on_get()
    {
        $reference = $this->findById('identifier2');
        $storageFilePath = 'directory/default/identifier2.php';
        $this->shouldNotHaveFile($this->vfs->url() . $storageFilePath);
        $this->get($reference)->shouldReturn('<?php ' . PHP_EOL . 'return true;');

        $this->shouldHaveFile($this->vfs->url() . '/' .  $storageFilePath);
        $this->shouldHaveFileContent($this->vfs->url() . '/' .  $storageFilePath, '<?php ' . PHP_EOL . 'return true;');
        $this->shouldHavePermissions($storageFilePath, 0640);
    }

    function it_does_not_store_source_if_option_is_not_specified_on_get()
    {
        $this->beConstructedWith(
            $this->vfs->url() . '/directory',
            $this->dispersion,
            $this->indexFactory,
            $this->referenceFactory,
            $this->export,
            $this->objectBuilder,
            [File::VALIDATE_REFERENCE => false] // Do not validate reference
        );

        $reference = $this->findById('identifier2');
        $storageFilePath = 'directory/default/identifier2.php';
        $this->shouldNotHaveFile($this->vfs->url() . $storageFilePath);
        $this->get($reference)->shouldReturn(false);
        $this->shouldNotHaveFile($this->vfs->url() . '/' .  $storageFilePath);
    }

    function it_interprets_a_reference()
    {
        $referenceOne = $this->findById('identifier1');
        $referenceTwo = $this->findById('identifier2');
        $referenceThree = $this->findById('identifier3');
        $referenceFour = $this->findById('identifier4');

        $this->interpret($referenceOne)->shouldImplement('Closure');
        $this->interpret($referenceTwo)->shouldReturn(true);
        $this->interpret($referenceThree)->shouldReturn(false);
        $this->interpret($referenceFour)->shouldReturn(9999);
    }

    function it_stores_modified_indexer_on_flush_if_record_is_modified()
    {
        $source = $this->newSource('identifier1', 'checksum2', [new ReturnStatement(true)]);
        $this->store($source);
        $this->flush()->shouldReturn($this);

        $this->shouldHaveFileContent(
            $this->vfs->url() . '/directory/index_default.php',
            sprintf(
                '<?php %s%s;',
                PHP_EOL,
                (new ReturnStatement($this->objectBuilder->build(new Index([
                    'identifier1' => $this->referenceFactory->create($source), // There should be new reference
                    'identifier2' => $this->referenceFactory->create(
                        $this->newSource('identifier2', 'checksum2', [new ReturnStatement(true)])
                    ),
                    'identifier4' => $this->referenceFactory->create(
                        $this->newSource('identifier4', 'checksum4')
                    )
                ]))))->compile($this->export)
            )
        );
    }

    function it_stores_new_indexer_on_flush()
    {
        $this->dispersion->calculate(Argument::any())->willReturn('new_directory');

        $source = $this->newSource('new_identifier1', 'new_checksum1', [new ReturnStatement(true)]);
        $reference = $this->referenceFactory->create($source);

        $this->store($source);
        $this->flush()->shouldReturn($this);

        $this->shouldHaveFileContent(
            $this->vfs->url() . '/directory/index_new_directory.php',
            sprintf(
                '<?php %s%s;',
                PHP_EOL,
                (new ReturnStatement($this->objectBuilder->build(new Index(['new_identifier1' => $reference]))))
                    ->compile($this->export)
            )
        );

        $this->shouldHaveFileContent(
            $this->vfs->url() . '/directory/_index_meta.php',
            sprintf(
                '<?php %s%s;',
                PHP_EOL,
                (new ReturnStatement((new Scalar([
                    'default' => 'index_default',
                    'custom' => 'index_custom',
                    'new_directory' => 'index_new_directory'
                ]))))->compile($this->export)
            )
        );
    }

    function it_does_not_store_any_index_on_flush_if_nothing_is_changed()
    {
        $this->findById('identifier1')->getId()->shouldReturn('identifier1');
        $this->shouldRemoveFile($this->vfs->url() . '/directory/_index_meta.php');
        $this->shouldRemoveFile($this->vfs->url() . '/directory/index_default.php');

        $this->flush()->shouldReturn($this);

        $this->shouldNotHaveFile($this->vfs->url() . '/directory/_index_meta.php');
        $this->shouldNotHaveFile($this->vfs->url() . '/directory/index_default.php');
    }

    function it_creates_new_dispersion_directory_and_new_index_if_unknown_one_specified()
    {
        $this->dispersion->calculate(Argument::any())->willReturn('new_directory');

        $source = $this->newSource('unknown', 'checksum1', [new ReturnStatement(true)]);
        $newReference = $this->store($source);
        $newReference->shouldImplement('EcomDev\Compiler\Storage\ReferenceInterface');
        $this->find($source)->shouldReturn($newReference);

        $storageFilePath = $this->vfs->url() . '/directory/new_directory/unknown.php';
        $this->shouldHaveFileContent($storageFilePath, '<?php ' . PHP_EOL . 'return true;');
    }

    private function setupFileSystem()
    {
        $instanceIndexOne = new Index([
            'identifier1' => $this->referenceFactory->create($this->newSource('identifier1', 'checksum1')),
            'identifier2' => $this->referenceFactory->create(
                $this->newSource('identifier2', 'checksum2', [new ReturnStatement(true)])
            ),
            'identifier4' => $this->referenceFactory->create($this->newSource('identifier4', 'checksum4'))
        ]);

        $instanceIndexTwo = new Index([
            'identifier3' => $this->referenceFactory
                ->create($this->newSource('identifier3', 'checksum3'))
        ]);

        $this->vfs = vfsStream::setup('root', null, [
            'directory' => [
                'index_default.php' => sprintf(
                    '<?php %s%s;',
                    PHP_EOL,
                    (new ReturnStatement($this->objectBuilder->build($instanceIndexOne)))->compile($this->export)
                ),
                'index_custom.php' => sprintf(
                    '<?php %s%s;',
                    PHP_EOL,
                    (new ReturnStatement($this->objectBuilder->build($instanceIndexTwo)))->compile($this->export)
                ),
                '_index_meta.php' => sprintf(
                    '<?php %s%s;',
                    PHP_EOL,
                    (new ReturnStatement(new Scalar(['default' => 'index_default', 'custom' => 'index_custom'])))
                        ->compile($this->export)
                ),
                'default' => [
                    'identifier1.php' => '<?php '
                        . PHP_EOL . 'return function ($item, $item2) {'
                        . PHP_EOL . '  return spritnf("%s-%s", $item, $item2); '
                        . PHP_EOL . '};',
                    'identifier4.php' => '<?php return 9999;' // Returns a number
                ],
                'custom' => [
                    'identifier3.php' => '<?php return false;' // Returns false!
                ]
            ]
        ]);

        return $this->vfs;
    }

    /**
     * Returns new static data source
     *
     * @param string $id
     * @param string $checksum
     * @param array $statements
     * @return StaticData
     */
    private function newSource($id, $checksum = 'checksum', array $statements = [])
    {
        return new StaticData($id, $checksum, new Container($statements));
    }

    public function getMatchers()
    {
        return [
            // Trick to remove file inbetween...
            'removeFile' => function ($subject, $file) {
                unlink($file);
                return true;
            },
            'havePermissions' => function ($subject, $file, $permissions) {
                $paths = explode('/', $file);
                $item = $this->vfs->getChild(array_shift($paths));
                $currentPermissions = $item->getPermissions();

                foreach ($paths as $path) {
                    if (!$path) {
                        continue;
                    }
                    $item = $item->getChild($path);
                    $currentPermissions = $item->getPermissions();
                }

                if ($currentPermissions !== $permissions) {
                    throw new NotEqualException(
                        sprintf('File permissions do not match for "%s"', $file),
                        $permissions,
                        $currentPermissions
                    );
                }

                return true;
            }
        ];
    }


}
