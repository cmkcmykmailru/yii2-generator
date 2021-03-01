<?php

namespace grigor\generator\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use grigor\generator\scanner\visitor\ServiceExtractor;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Serializer implements ServiceExtractor
{
    /** @phpstan-var list<string> */
    public $value;

    public function __construct(array $values)
    {
        if (!isset($values['value']) || !is_string($values['value'])) {
            throw new InvalidConfigurationException('The value "serializer" must be a string.');
        }
        if (empty($values['value'])) {
            throw new InvalidConfigurationException('The value "serializer" must not be empty.');
        }
        $this->value = $values['value'];
    }

    public function extract(): array
    {
        return [
            'serializer' => $this->value
        ];
    }
}