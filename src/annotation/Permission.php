<?php


namespace grigor\generator\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Permission implements ServiceExporter
{
    /** @phpstan-var list<string> */
    public $value;

    public function __construct(array $values)
    {
        if (!isset($values['value']) || !is_array($values['value'])) {
            throw new InvalidConfigurationException('The value "permissions" must be a array.');
        }
        if (empty($values['value'])) {
            throw new InvalidConfigurationException('The value "permissions" must not be empty.');
        }
        $this->value = $values['value'];
    }

    public function export(): array
    {
        return [
            'permissions' => $this->value
        ];
    }
}