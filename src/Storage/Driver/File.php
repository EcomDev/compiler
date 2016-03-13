<?php

namespace EcomDev\Compiler\Storage\Driver;

use EcomDev\Compiler\DispersionInterface;
use EcomDev\Compiler\ExportableInterface;
use EcomDev\Compiler\ExporterInterface;
use EcomDev\Compiler\ObjectBuilderInterface;
use EcomDev\Compiler\Statement\ContainerInterface;
use EcomDev\Compiler\SourceInterface;
use EcomDev\Compiler\StatementInterface;
use EcomDev\Compiler\Storage\DriverInterface;
use EcomDev\Compiler\Storage\ReferenceFactory;
use EcomDev\Compiler\Storage\ReferenceInterface;

/**
 * File storage driver for compiler
 *
 */
class File implements DriverInterface
{
    /**
     * Directory permission key
     *
     * @var string
     */
    const PERMISSION_DIRECTORY = 'permission_directory';

    /**
     * File permission key
     *
     * @var string
     */
    const PERMISSION_FILE = 'permission_file';

    /**
     * Flag for validating reference before including it
     *
     * @var string
     */
    const VALIDATE_REFERENCE = 'validate_reference';

    /**
     * File path
     *
     * @var string
     */
    private $filePath;

    /**
     * Dispersion instance
     *
     * @var DispersionInterface
     */
    private $dispersion;


    /**
     * Index factory for creating new instances
     *
     * @var IndexFactory
     */
    private $indexFactory;

    /**
     * Factory for creating references
     *
     * @var ReferenceFactory
     */
    private $referenceFactory;

    /**
     * Exporter for exporting data to file system
     *
     * @var ExporterInterface
     */
    private $exporter;

    /**
     * Loaded index references
     *
     * @var IndexInterface[]
     */
    private $index = [];

    /**
     * Index metadata
     *
     * @var string[]
     */
    private $indexMeta;

    /**
     * Safe include closure,
     * that is detached from the scope of compiler
     *
     * @var \Closure
     */
    private $safeInclude;

    /**
     * Flag for changed metadata
     *
     * @var bool
     */
    private $metaChanged;

    /**
     * Object builder
     *
     * @var ObjectBuilderInterface
     */
    private $objectBuilder;

    /**
     * Default permissions
     *
     * @var int[]
     */
    private $options = [
        self::PERMISSION_DIRECTORY => 0750,
        self::PERMISSION_FILE => 0640,
        self::VALIDATE_REFERENCE => true
    ];

    /**
     * Creates a new file system
     *
     * @param string $filePath
     * @param DispersionInterface $dispersion
     * @param IndexFactory $indexFactory
     * @param ReferenceFactory $referenceFactory
     * @param ExporterInterface $export
     * @param int[] $options
     */
    public function __construct(
        $filePath,
        DispersionInterface $dispersion,
        IndexFactory $indexFactory,
        ReferenceFactory $referenceFactory,
        ExporterInterface $export,
        ObjectBuilderInterface $objectBuilder,
        array $options = []
    ) {
        $this->filePath = $filePath;
        $this->dispersion = $dispersion;
        $this->indexFactory = $indexFactory;
        $this->referenceFactory = $referenceFactory;
        $this->objectBuilder = $objectBuilder;
        $this->exporter = $export;
        $this->metaChanged = false;

        // Prevents access to private properties of driver, but uses object builder instead as this bind
        $this->safeInclude = $this->objectBuilder->bind(function ($file) {
            return include $file;
        });

        if ($options) {
            $this->options = $options + $this->options;
        }

        $this->validateDirectory($this->filePath);
    }

    /**
     * Stores source into storage if it is not available
     * or if checksum is different
     *
     * @param SourceInterface $source
     * @return ReferenceInterface
     */
    public function store(SourceInterface $source)
    {
        $existingReference = $this->find($source);

        if ($existingReference && $existingReference->getChecksum() === $source->getChecksum()) {
            return $existingReference;
        }

        $newReference = $this->referenceFactory->create($source);
        $dispersion = $this->dispersion->calculate($newReference->getId());
        $index = $this->loadIndex($dispersion);

        if (!$index) {
            $index = $this->createIndex($dispersion);
        }

        $index->add($newReference);

        $filePath = $this->getReferenceFilePath($newReference, $dispersion);
        $container = $source->load();
        $this->writePhpFile($filePath, $container);
        return $newReference;
    }

    /**
     * Finds reference in available index by source identifier
     *
     * @param SourceInterface $source
     * @return bool|ReferenceInterface
     */
    public function find(SourceInterface $source)
    {
        $id = $source->getId();
        return $this->findById($id);
    }

    /**
     * It interprets the reference related PHP file
     *
     * If self::VALIDATE_REFERENCE is set to true,
     * it will automatically create a file if it does not exists
     *
     * @param ReferenceInterface $reference
     *
     * @return mixed
     */
    public function interpret(ReferenceInterface $reference)
    {
        $filePath = $this->getReferenceFilePath($reference);
        $this->validateReference($reference, $filePath);
        return $this->includeFile($filePath);
    }

    /**
     * Returns string content of the file
     *
     * @param ReferenceInterface $reference
     * @return string
     */
    public function get(ReferenceInterface $reference)
    {
        $filePath = $this->getReferenceFilePath($reference);
        $this->validateReference($reference, $filePath);
        return @file_get_contents($filePath);
    }

    /**
     * Find reference by a identifier
     *
     * @param string $id
     *
     * @return ReferenceInterface|bool
     */
    public function findById($id)
    {
        $index = $this->loadIndex($this->dispersion->calculate($id));

        if (!$index) {
            return false;
        }

        if (!$index->has($id)) {
            return false;
        }

        return $index->get($id);
    }

    /**
     * Saves all index info on disk
     * and saves metadata file
     *
     * @return $this
     */
    public function flush()
    {
        $saveMeta = false;

        foreach ($this->index as $dispersion => $index) {
            if ($index->isChanged()) {
                $saveMeta = true;
                $this->saveIndex($dispersion, $index);
            }
        }

        if ($saveMeta) {
            $this->saveIndexMeta();
        }

        return $this;
    }

    /**
     * Validate reference existence before retrieving file
     *
     * @param ReferenceInterface $reference
     * @param $filePath
     * @return $this
     */
    private function validateReference(ReferenceInterface $reference, $filePath)
    {
        if ($this->options[self::VALIDATE_REFERENCE] && !file_exists($filePath)) {
            $container = $reference->getSource()->load();
            $this->writePhpFile($filePath, $container);
        }

        return $this;
    }

    /**
     * Loads or creates a new index
     *
     * @param string $dispersion
     * @return IndexInterface|false
     */
    private function createIndex($dispersion)
    {
        $index = $this->indexFactory->create();
        $this->index[$dispersion] = $index;
        $this->indexMeta[$dispersion] = sprintf('index_%s', $dispersion);
        return $index;
    }

    /**
     * Creates directory if it does not exist
     *
     * @param string $path
     * @return $this
     */
    private function validateDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, $this->options[self::PERMISSION_DIRECTORY], true);
        }

        return $this;
    }

    /**
     * Returns dispersed reference file path
     *
     * @param ReferenceInterface $reference
     * @param null|string $dispersion
     * @return string
     */
    private function getReferenceFilePath(ReferenceInterface $reference, $dispersion = null)
    {
        if ($dispersion === null) {
            $dispersion = $this->dispersion->calculate($reference->getId());
        }

        return sprintf('%s/%s/%s.php', $this->filePath, $dispersion, $reference->getId());
    }

    /**
     * Writes php file in atomic way,
     * e.g. there is no half written file
     *
     * @param string $filePath
     * @param ContainerInterface|array|string $content
     * @return $this
     */
    private function writePhpFile($filePath, $content)
    {
        $fileText = $content;

        if (is_array($content) || $content instanceof \Traversable) {
            $lines = [];
            /** @var StatementInterface $line */
            foreach ($content as $line) {
                $lines[] = sprintf('%s;', $this->export($line));
            }

            $fileText = implode(PHP_EOL, $lines);
        }

        $this->validateDirectory(dirname($filePath));

        $tmpFile = dirname($filePath) . '/' . uniqid('.tmpfile');

        // Writes to tmp file and moves it over file path
        if (file_put_contents($tmpFile, sprintf('<?php %s%s', PHP_EOL, $fileText))) {
            rename($tmpFile, $filePath);
            chmod($filePath, $this->options[self::PERMISSION_FILE]);
        }

        return $this;
    }

    /**
     * Return exported PHP code
     *
     * @param ExportableInterface|StatementInterface|mixed $value
     * @return string
     */
    private function export($value)
    {
        if ($value instanceof ExportableInterface) {
            $value = $this->objectBuilder->build($value);
        }

        return $this->exporter->export($value);
    }

    /**
     * Initializes file index
     *
     * @return $this
     */
    private function initIndexMeta()
    {
        if ($this->indexMeta !== null) {
            return $this;
        }

        $metaFile = sprintf('%s/_index_meta.php', $this->filePath);

        $this->indexMeta = $this->includeFile($metaFile);

        if (!is_array($this->indexMeta)) {
            $this->indexMeta = [];
        }

        return $this;
    }

    /**
     * Check for index availability
     *
     * @param string $dispersion
     * @return IndexInterface|false
     */
    private function loadIndex($dispersion)
    {
        if (isset($this->index[$dispersion])) {
            return $this->index[$dispersion];
        }

        $this->initIndexMeta();

        if (isset($this->indexMeta[$dispersion])) {
            $prefix = $this->indexMeta[$dispersion];
            $index = $this->includeFile(sprintf('%s/%s.php', $this->filePath, $prefix));
            if ($index instanceof IndexInterface) {
                $this->index[$dispersion] = $index;
                return $this->index[$dispersion];
            }
            unset($this->indexMeta[$dispersion]);
        }

        return false;
    }

    /**
     * Saves index into file system
     *
     * @param string $dispersion
     * @param IndexInterface $index
     * @return $this
     */
    private function saveIndex($dispersion, IndexInterface $index)
    {
        $prefix = $this->indexMeta[$dispersion];
        $filePath = sprintf('%s/%s.php', $this->filePath, $prefix);
        $content = sprintf('return %s;', $this->export($index));
        $this->writePhpFile($filePath, $content);
        return $this;
    }

    /**
     * Saves index meta file
     *
     * @return $this
     */
    private function saveIndexMeta()
    {
        $filePath = sprintf('%s/_index_meta.php', $this->filePath);
        $content = sprintf('return %s;', $this->export($this->indexMeta));
        $this->writePhpFile($filePath, $content);
        return $this;
    }

    /**
     * Includes file via safe include mechanism
     *
     * @param string $fileName
     * @param bool $validateFile
     * @return mixed
     */
    private function includeFile($fileName, $validateFile = true)
    {
        $includeClosure = $this->safeInclude;

        if ($validateFile && !file_exists($fileName)) {
            return false;
        }

        $result = $includeClosure($fileName);
        return $result;
    }
}
