<?php

namespace grigor\generator\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Context implements ServiceExporter
{
    /** @phpstan-var list<string> */
    public $value;

    public function __construct(array $values)
    {
        if (!isset($values['value']) || !is_string($values['value'])) {
            throw new InvalidConfigurationException('The value "context" must be a string.');
        }
        if (empty($values['value'])) {
            throw new InvalidConfigurationException('The value "context" must not be empty.');
        }
        $this->value = $values['value'];
    }

    public function export(): array
    {
        return [
            'context' => $this->value
        ];
    }
}