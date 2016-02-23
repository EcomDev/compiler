<?php

namespace EcomDev\Compiler;

class Export implements ExportInterface
{
    /**
     * Exports php value into var export statement
     *
     * @param mixed $value
     *
     * @return string
     */
    public function export($value)
    {
        if ($value instanceof ExportableInterface) {
            $value = $value->export();
        }

        if ($value instanceof StatementInterface) {
            return $value->compile($this);
        }

        if (is_object($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s does not implement %s or %s',
                    get_class($value),
                    'EcomDev\Compiler\StatementInterface',
                    'EcomDev\Compiler\ExportableInterface'
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
