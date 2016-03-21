<?php

namespace EcomDev\Compiler;

class Exporter implements ExporterInterface
{
    private $objectBuilder;

    public function __construct(ObjectBuilderInterface $objectBuilder = null)
    {
        $this->objectBuilder = $objectBuilder;
    }

    /**
     * Exports php value into var export statement
     *
     * @param mixed $value
     *
     * @return string
     */
    public function export($value)
    {
        if ($this->objectBuilder && $value instanceof ExportableInterface) {
            $value = $this->objectBuilder->build($value);
        }

        if ($value instanceof StatementInterface) {
            return $value->compile($this);
        }

        // PHP always exports it as NULL
        if ($value === null) {
            return 'null';
        }

        if (is_object($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s does not implement %s',
                    get_class($value),
                    'EcomDev\Compiler\StatementInterface'
                )
            );
        }

        if (is_array($value)) {
            $string = [];
            foreach ($value as $key => $item) {
                $string []= sprintf('%s => %s', $this->export($key), $this->export($item));
            }

            return sprintf('[%s]', implode(', ', $string));
        }

        return var_export($value, true);
    }
}
